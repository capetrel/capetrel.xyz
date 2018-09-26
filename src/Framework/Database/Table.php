<?php
namespace App\Framework\Database;

use PDO;

/**
 * Class Table
 * Représente des requêtes SQL générique à toute l'application
 * @package App\Framework\Database
 */
class Table
{

    /**
     * @var PDO|null
     */
    protected $pdo;

    /**
     * Nom de la table en bdd
     * @var string|null
     */
    protected $table;

    /**
     * Entité à utiliser
     * @var string|null
     */
    protected $entity = \stdClass::class;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère une liste clef / valeur de nos enregistrements
     * @param string $field
     * @return array
     */
    public function findList(string $field): array
    {
        $results = $this->pdo
            ->query("SELECT id, $field FROM {$this->table}")
            ->fetchAll(PDO::FETCH_NUM);
        $list = [];
        foreach ($results as $result) {
            $list[$result[0]] = $result[1];
        }
        return $list;
    }

    /**
     * @return QueryBuilder
     */
    public function makeQuery(): QueryBuilder
    {
        return (new QueryBuilder($this->pdo))
            ->from($this->table, $this->table[0])
            ->into($this->entity);
    }

    /**
     * Récupère tous les enregistrements d'une table
     * @return QueryBuilder
     */
    public function findAll(): QueryBuilder
    {
        return $this->makeQuery();
    }

    /**
     * Récupère une ligne par rapport à un champs
     * @param string $field
     * @param string $value
     * @return mixed
     * @throws NoRecordException
     */
    public function findBy(string $field, string $value)
    {
        return $this->makeQuery()->where("$field = :field")->params(["field" => $value])->fetchOrFail();
    }

    /**
     * Récupère un élément à partir de son id
     * @param int $id
     * @return mixed
     * @throws NoRecordException
     */
    public function find(int $id)
    {
        return $this->makeQuery()->where("id = $id")->fetchOrFail();
    }

    /**
     * Récupère le nombre d'enregistrement
     * @return int
     */
    public function count(): int
    {
        return $this->makeQuery()->count();
    }

    /**
     * Met à jour les champs d'un enregistrement de la base de doonées
     * @param int $id
     * @param array $datas
     * @return bool
     */
    public function update(int $id, array $datas): bool
    {
        $fieldQuery = $this->buildFieldQuery($datas);
        $datas["id"] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $fieldQuery WHERE id = :id");
        return $stmt->execute($datas);
    }

    /**Insert des données dans les chanmps de la table
     * @param array $datas
     * @return bool
     */
    public function insert(array $datas): bool
    {
        $fields = array_keys($datas);
        $values = join(', ', array_map(function ($field) {
            return ':'.$field;
        }, $fields));
        $fields = join(', ', $fields);
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
        return $stmt->execute($datas);
    }

    /**
     * Supprime un enregistrement en base de données
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Construit un morceau de requête SQL
     * @param array $datas
     * @return string
     */
    private function buildFieldQuery(array $datas)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($datas)));
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Vérifie qu'un enregistrement existe dans la table
     * @param $id
     * @return bool
     */
    public function exists($id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
