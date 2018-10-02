<?php
namespace App\Cv\Entity;

class Skill
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $skillName;

    /**
     * @var mixed
     */
    private $skillLevel;

    /**
     * @var string
     */
    private $picto;

    /**
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @var \DateTime
     */
    private $endedAt;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $place;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var int
     */
    private $cvId;

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
     * @return string|null
     */
    public function getSkillName(): ?string
    {
        return $this->skillName;
    }

    /**
     * @param string $skillName
     */
    public function setSkillName(string $skillName): void
    {
        $this->skillName = $skillName;
    }

    /**
     * @return int|null
     */
    public function getSkillLevel(): ?int
    {
        if (is_string($this->skillLevel) && empty($this->skillLevel)) {
            return null;
        }
        return (int)$this->skillLevel;
    }

    /**
     * @param mixed|null $skillLevel
     */
    public function setSkillLevel($skillLevel)
    {
        if (is_string($skillLevel) && $skillLevel === '' || $skillLevel === 0) {
            $this->skillLevel = null;
        }
        $this->skillLevel = (int)$skillLevel;
    }

    /**
     * @return string|null
     */
    public function getPicto(): ?string
    {
        /*
        if (!is_null($this->picto)) {
            return DIRECTORY_SEPARATOR .
                'uploads' . DIRECTORY_SEPARATOR .
                'skill' . DIRECTORY_SEPARATOR .
                'logos' . DIRECTORY_SEPARATOR .
                $this->picto;

        }
        */
        return $this->picto;
    }

    /**
     * @param string|null $picto
     */
    public function setPicto(?string $picto): void
    {
        if (!is_null($picto)) {
            $this->picto = $picto;
        }
        $this->picto = $picto;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartedAt(): ?\DateTime
    {
        if (!is_null($this->startedAt) && !is_string($this->startedAt)) {
            return $this->startedAt;
        }
        return null;
    }

    /**
     * @param $datetime
     */
    public function setStartedAt($datetime): void
    {
        if (is_string($datetime) && !empty($datetime)) {
            $this->startedAt = new \DateTime($datetime);
        } else {
            $this->startedAt = $datetime;
        }
    }

    /**
     * @return \DateTime|null
     */
    public function getEndedAt(): ?\DateTime
    {
        if (!is_null($this->endedAt) && !is_string($this->endedAt)) {
            return $this->endedAt;
        }
        return null;
    }

    /**
     * @param $datetime
     */
    public function setEndedAt($datetime): void
    {
        if (is_string($datetime) && !empty($datetime)) {
            $this->endedAt = new \DateTime($datetime);
        } else {
            $this->endedAt = $datetime;
        }
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        if (!is_null($description)) {
            $this->description = $description;
        } else {
            $this->description = null;
        }
    }

    /**
     * @return string|null
     */
    public function getPlace(): ?string
    {
        return $this->place;
    }

    /**
     * @param string|null $place
     */
    public function setPlace(?string $place): void
    {
        if (!is_null($place)) {
            $this->place = $place;
        } else {
            $this->place = null;
        }
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return int
     */
    public function getCvId(): int
    {
        return $this->cvId;
    }

    /**
     * @param int $cvId
     */
    public function setCvId(int $cvId): void
    {
        $this->cvId = $cvId;
    }

}