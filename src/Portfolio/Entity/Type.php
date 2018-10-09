<?php
namespace App\Portfolio\Entity;

class Type
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $pTitle
     */
    public $tName;

    /**
     * @var string $pSlug
     */
    public $tSlug;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getTName(): string
    {
        return $this->tName;
    }

    /**
     * @param string $tName
     */
    public function setTName(string $tName): void
    {
        $this->tName = $tName;
    }

    /**
     * @return string
     */
    public function getTSlug(): string
    {
        return $this->tSlug;
    }

    /**
     * @param string $tSlug
     */
    public function setTSlug(string $tSlug): void
    {
        $this->tSlug = $tSlug;
    }

}
