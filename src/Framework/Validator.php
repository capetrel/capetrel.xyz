<?php
namespace Framework;

use App\Framework\Database\Table;
use App\Framework\Validator\ValidationError;
use Psr\Http\Message\UploadedFileInterface;

class Validator
{

    private const MIME_TYPE = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'pdf' => 'application/pdf'
    ];

    /**
     * @var array
     */
    private $params;

    /**
     * @var string[]
     */
    private $errors = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Verifie que l'élément est dans le tableau(capture les champs vides)
     * @param mixed[] ...$keys
     * @return Validator
     */
    public function required(...$keys): self
    {
        if (is_array($keys[0])) {
            $keys=$keys[0];
        }
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    /**
     * Verifie que le champ n'est pas vide
     * @param string ...$keys
     * @return Validator
     */
    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    /**
     * Verifie la taille des textes avec des paramètres
     * @param string $key
     * @param int|null $min
     * @param int|null $max
     * @return Validator
     */
    public function textLength(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if (!is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (!is_null($min) &&
            $length < $min
        ) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) &&
            $length > $max
        ) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    /**
     * Vérifie que le slug est valide
     * @param string $key
     * @return Validator
     */
    public function isSlug(string $key): self
    {
        $value = $this->getValue($key);

        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        if (!is_null($value) && !preg_match($pattern, $this->params[$key])) {
            $this->addError($key, 'slug');
        }
        return $this;
    }


    /**
     * Verifie que l'élément est numérique
     * @param string $key
     * @return Validator
     */
    public function isNumeric(string $key):self
    {
        $value = $this->getValue($key);
        if (!empty($value)) {
            if (!is_numeric($value)) {
                $this->addError($key, 'numeric');
            }
        }
        return $this;
    }

    /**
     * Verifie que l'élément est numérique et qu'il est dans l'intervalle définit
     * @param string $key
     * @param int $min
     * @param int $max
     * @return Validator
     */
    public function numericRange(string $key, int $min, int $max):self
    {
        $value = $this->getValue($key);
        if (!empty($value)) {
            if (!is_numeric($value) || $value < $min) {
                $this->addError($key, 'numeric_range', [$min, $max]);
            } elseif (!is_numeric($value) || $value > $max) {
                $this->addError($key, 'numeric_range', [$min, $max]);
            }
        }
        return $this;
    }

    /**
     * Vérifie que la date est au bon format (MYSQL freindly)
     * @param string $key
     * @param string $format
     * @return Validator
     */
    public function isDateTime(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    /**
     * Vérifie que la date est au bon format (MYSQL freindly)
     * @param string $key
     * @param string $format
     * @return Validator
     */
    public function isDate(string $key, string $format = 'Y-m-d'): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'date', [$format]);
        }
        return $this;
    }

    /**
     * Autorise une date vide, sinon une date au bon format (MYSQL freindly)
     * @param string $key
     * @param string $format
     * @return Validator
     */
    public function emptyOrIsDate(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        $value = $this->getValue($key);
        if ($value === '') {
            return $this;
        }
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'date', [$format]);
        }
        return $this;
    }

    /**
     * Varifie que la clé existe dans base de données
     * @param string $key
     * @param string $table
     * @param \PDO $pdo
     * @return Validator
     */
    public function exists(string $key, string $table, \PDO $pdo): self
    {
        $value = $this->getValue($key);
        $query = $pdo->prepare("SELECT id FROM $table WHERE id = ?");
        $query->execute([$value]);
        if ($query->fetchColumn() === false) {
            $this->addError($key, 'exists', [$table]);
        }
        return $this;
    }

    /**
     * Vérife que la clé est unique dans la BDD
     * @param string $key
     * @param string|Table $table
     * @param \PDO $pdo
     * @param int|null $exclude
     * @return Validator
     */
    public function isUnique(string $key, $table, ?\PDO $pdo = null, ?int $exclude = null): self
    {
        if ($table instanceof Table) {
            $pdo = $table->getPdo();
            $table = $table->getTable();
        }
        $value = $this->getValue($key);
        $query = "SELECT id FROM $table WHERE $key = ?";
        $params = [$value];
        if ($exclude !== null) {
            $query .= " AND id != ?";
            $params[] = $exclude;
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        if ($stmt->fetchColumn() !== false) {
            $this->addError($key, 'isUnique', [$value]);
        }
        return $this;
    }

    /**
     * Vérifie que l'extension est dans le tableau d'extensions autorisées
     * @param string $key
     * @param array $extensions
     * @return Validator
     */
    public function extension(string $key, array $extensions): self
    {
        /** @var UploadedFileInterface $file */
        $file = $this->getValue($key);
        if ($file !== null && $file->getError() === UPLOAD_ERR_OK) {
            $type = $file->getClientMediaType();
            $extension = mb_strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
            $expectedType = self::MIME_TYPE[$extension] ?? null;
            if (!in_array($extension, $extensions) || $expectedType !== $type) {
                $this->addError($key, 'filetype', [join(', ', $extensions)]);
            }
        }
        return $this;
    }

    //TODO vérification du poid du fichier avant l'upload

    /**
     * Vérifie que le fichier à bien été téléversé
     * @param string $key
     * @return Validator
     */
    public function uploaded(string $key): self
    {
        /** @var UploadedFileInterface $file */
        $file = $this->getValue($key);
        if (is_string($file)) {
            return $this;
        }
        if ($file === null || $file->getError() !== UPLOAD_ERR_OK) {
            $this->addError($key, 'uploaded');
        }
        return $this;
    }

    /**
     * Vérifie la validité d'une adresse email
     * @param string $key
     * @return Validator
     */
    public function email(string $key): self
    {
        $value = $this->getValue($key);
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($key, 'email');
        }
        return $this;
    }

    public function confirm(string $key): self
    {
        $value = $this->getValue($key);
        $valueConfirm = $this->getValue($key . '_confirm');
        if ($valueConfirm !== $value) {
            $this->addError($key, 'confirm');
        }
        return $this;
    }

    /**
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Ajoute Une erreur
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}
