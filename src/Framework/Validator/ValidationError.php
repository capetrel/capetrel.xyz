<?php
namespace App\Framework\Validator;

class ValidationError
{

    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rule;

    /**
     * @var array
     */
    private $attributes;

    private $messages = [
        'required' => 'Le champ %s est requis',
        'empty' => 'Le champ %s ne peut pas être vide',
        'slug' => 'Le champ %s n\'est pas un slug valide',
        'minLength' => 'Le champ %s doit contenir plus de %d caractères',
        'maxLength' => 'Le champ %s doit contenir moins de %d caractères',
        'betweenLength' => 'Le champ %s doit contenir entre %d et %d caractères',
        'numeric' => 'Le champ %s doit être un chiffre',
        'numeric_range' => 'Le champ %s doit être entre %d et %d',
        'datetime' => 'Le champ %s doit être une date valide (%s)',
        'date' => 'Le champ %s doit être une date valide (%s)',
        'exists' => 'Cet enregistrement de %s n\'existe pas dans la table %s',
        'unique' => 'Cet enregistrement de %s existe déjà',
        'filetype' => 'Le champs %s n\'est pas au format valide (%s)',
        'uploaded' => 'Vous devez uploader un fichier',
        'email' => 'Cet email ne semble pas valide',
        'confirm' => 'Vous n\'avez pas confirmé le champs %s',
        'max_file_size' => 'Le fichier %s est supérieur à %s Mo'
    ];


    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        if (!array_key_exists($this->rule, $this->messages)) {
            return "Le champs {$this->key} ne correspond pas à la règle {$this->rule}.";
        } else {
            $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
            return (string)call_user_func_array('sprintf', $params);
        }
    }
}
