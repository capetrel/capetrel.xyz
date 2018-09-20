<?php
namespace App\Home\Entity;

class Home
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $proverb;

    /**
     * @var string
     */
    public $proverbAuthor;

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
    public function getProverb(): string
    {
        return $this->proverb;
    }

    /**
     * @param string $proverb
     */
    public function setProverb(string $proverb): void
    {
        $this->proverb = $proverb;
    }

    /**
     * @return string
     */
    public function getProverbAuthor(): string
    {
        return $this->proverbAuthor;
    }

    /**
     * @param string $proverbAuthor
     */
    public function setProverbAuthor(string $proverbAuthor): void
    {
        $this->proverbAuthor = $proverbAuthor;
    }


}

