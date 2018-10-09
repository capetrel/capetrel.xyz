<?php
namespace App\Portfolio\Entity;

class Portfolio
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $pTitle
     */
    private $pTitle;

    /**
     * @var string $pSlug
     */
    private $pSlug;

    /**
     * @var string $pDescription
     */
    private $pDescription;

    /**
     * @var string $image
     */
    private $image;

    /**
     * @var string $link
     */
    private $link;

    /**
     * @var int $typeId
     */
    private $typeId;

    /**
     * @var int $userId
     */
    private $userId;

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
    public function getPTitle(): ?string
    {
        return $this->pTitle;
    }

    /**
     * @param string $pTitle
     */
    public function setPTitle(string $pTitle): void
    {
        $this->pTitle = $pTitle;
    }

    /**
     * @return string
     */
    public function getPSlug(): ?string
    {
        return $this->pSlug;
    }

    /**
     * @param string $pSlug
     */
    public function setPSlug(string $pSlug): void
    {
        $this->pSlug = $pSlug;
    }

    /**
     * @return string
     */
    public function getPDescription(): ?string
    {
        return $this->pDescription;
    }

    /**
     * @param string $pDescription
     */
    public function setPDescription(string $pDescription): void
    {
        $this->pDescription = $pDescription;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getThumb(): ?string
    {
        if ($this->image === null) {
            return null;
        }
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);
        return DIRECTORY_SEPARATOR .
            'uploads' . DIRECTORY_SEPARATOR .
            'portfolio' . DIRECTORY_SEPARATOR .
            $filename .'_thumb.' . $extension;
    }

    public function getImageUrl()
    {
        return DIRECTORY_SEPARATOR .
            'uploads' . DIRECTORY_SEPARATOR .
            'portfolio' . DIRECTORY_SEPARATOR .
            $this->image;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    /**
     * @param int $typeId
     */
    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }


}
