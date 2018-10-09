<?php
namespace App\Framework\Database;

use Pagerfanta\Pagerfanta;
use PDO;
use Traversable;

class QueryBuilder implements \IteratorAggregate
{

    private $select;

    private $from;

    private $where = [];

    private $group;

    private $order = [];

    private $limit;

    private $joins;

    private $params = [];

    private $pdo;

    private $entity;


    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $table
     * @param null|string $alias
     * @return QueryBuilder
     */
    public function from(string $table, ?string $alias = null): self
    {
        if ($alias) {
            $this->from[$table] = $alias;
        } else {
            $this->from[] = $table;
        }

        return $this;
    }

    /**
     * @param string ...$fields
     * @return QueryBuilder
     */
    public function select(string ...$fields): self
    {
        $this->select = $fields;
        return $this;
    }

    /**
     * Spécifie le LIMIT
     * @param int $length
     * @param int $offset
     * @return QueryBuilder
     */
    public function limit(int $length, int $offset = 0): self
    {
        $this->limit = "$offset, $length";
        return $this;
    }

    /**
     * Spécifie l'ordre de récupération
     * @param string $orders
     * @return QueryBuilder
     */
    public function order(string $orders): self
    {
        $this->order[] = $orders;
        return $this;
    }

    public function group(string $field): self
    {
        $this->group[] = $field;
        return $this;
    }

    /**Ajoute une liaison
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return QueryBuilder
     */
    public function join(string $table, string $condition, string $type = 'left'): self
    {
        $this->joins[$type][] = [$table, $condition];
        return $this;
    }

    /**
     * Définit la condition de récupération
     * @param string ...$condition
     * @return QueryBuilder
     */
    public function where(string ...$condition): self
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $query = clone $this;
        $table = current($this->from);
        return $query->select("COUNT($table.id)")->execute()->fetchColumn();
    }

    /**
     * @param array $params
     * @return QueryBuilder
     */
    public function params(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param string $entity
     * @return QueryBuilder
     */
    public function into(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Récupère un résultat
     * @return bool
     */
    public function fetch()
    {
        $record = $this->execute()->fetch(PDO::FETCH_ASSOC);
        if ($record === false) {
            return false;
        }
        if ($this->entity) {
            return Hydrator::hydrate($record, $this->entity);
        }
        return $record;
    }

    /**
     * Retournera un résultat ou renvoie une exception
     * @return bool
     * @throws NoRecordException
     */
    public function fetchOrFail()
    {
        $record = $this->fetch();
        if ($record === false) {
            throw new NoRecordException('Aucun enregistrement');
        }
        return $record;
    }

    /**
     * @return QueryResult
     */
    public function fetchAll(): QueryResult
    {
        return new QueryResult(
            $this->execute()->fetchAll(PDO::FETCH_ASSOC),
            $this->entity
        );
    }

    /**
     * Récupère une ligne
     * @param int $columnNumber
     * @return mixed
     */
    public function fetchColumn(int $columnNumber = 0)
    {
        return $this->execute()->fetchColumn($columnNumber);
    }

    /**
     * Pagine les résultats
     * @param int $perPage
     * @param int $currentPage
     * @return Pagerfanta
     */
    public function paginate(int $perPage, int $currentPage = 1): Pagerfanta
    {
        $paginator = new PaginatedQuery($this);
        return (new Pagerfanta($paginator))->setMaxPerPage($perPage)->setCurrentPage($currentPage);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $parts = ['SELECT'];

        if ($this->select) {
            $parts[] = join(', ', $this->select);
        } else {
            $parts[] = '*';
        }

        $parts[] = 'FROM';
        $parts[] = $this->buildFrom();
        if (!empty($this->joins)) {
            foreach ($this->joins as $type => $joins) {
                foreach ($joins as [$table, $condition]) {
                    $parts[] = strtoupper($type) . " JOIN $table ON $condition";
                }
            }
        }
        if (!empty($this->where)) {
            $parts[] = 'WHERE';
            $parts[] = "(" . join(') AND (', $this->where) . ')';
        }
        if (!empty($this->order)) {
            $parts[] = 'ORDER BY';
            $parts[] = join(', ', $this->order);
        }
        if (!empty($this->group)) {
            $parts[] = 'GROUP BY';
            $parts[] = join(', ', $this->group);
        }
        if ($this->limit) {
            $parts[] = 'LIMIT ' . $this->limit;
        }
        return join(' ', $parts);
    }

    /**
     * @return string
     */
    private function buildFrom(): string
    {
        $from = [];

        foreach ($this->from as $key => $value) {
            if (is_string($key)) {
                $from[] = "$key as $value";
            } else {
                $from[] = $value;
            }
        }
        return join(', ', $from);
    }

    /**
     * @return bool|\PDOStatement
     */
    private function execute()
    {
        $query = $this->__toString();
        if ($this->params) {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($this->params);
            return $stmt;
        }
        return $this->pdo->query($query);
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing Iterator or Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return $this->fetchAll();
    }
}
