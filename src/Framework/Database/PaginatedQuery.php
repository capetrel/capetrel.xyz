<?php
namespace App\Framework\Database;

use Pagerfanta\Adapter\AdapterInterface;

class PaginatedQuery implements AdapterInterface
{
    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * PaginatedQuery constructor.
     * @param QueryBuilder $query Requête qui récupère X résultats
     */
    public function __construct(QueryBuilder $query)
    {

        return $this->query = $query;
    }

    /**
     * Renvoie le nombre de resultats
     * @return integer The number of results.
     */
    public function getNbResults(): int
    {
        return $this->query->count();
    }

    /**
     * Returns an slice of the results.
     * @param integer $offset The offset.
     * @param integer $length The length.
     * @return QueryResult The slice.
     */
    public function getSlice($offset, $length): QueryResult
    {
        $query = clone $this->query;
        return $query->limit($length, $offset)->fetchAll();
    }
}
