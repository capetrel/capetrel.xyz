<?php
namespace App\Cv\Entity;

class Cv
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $cvName;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        if (!is_null($this->id)) {
            return $this->id;
        }
        return null;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCvName(): ?string
    {
        if (!is_null($this->cvName)) {
            return $this->cvName;
        }
        return "";
    }

    /**
     * @param string $cvName
     */
    public function setCvName(string $cvName): void
    {
        $this->cvName = $cvName;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ? int
    {
        if (!is_null($this->userId)) {
            return $this->userId;
        }
        return null;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

}
