<?php
namespace App\Cv\Entity;

class Skill
{

    /*
        s.skill_name,
        s.skill_level,
        s.picto,
        s.started_at,
        s.ended_at,
        s.description,
        s.place,
        ca.c_name
     */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $skillName;

    /**
     * @var string
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
     * @return string
     */
    public function getSkillName(): string
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
     * @return string
     */
    public function getSkillLevel(): string
    {
        return $this->skillLevel;
    }

    /**
     * @param string $skillLevel
     */
    public function setSkillLevel(string $skillLevel): void
    {
        $this->skillLevel = $skillLevel;
    }

    /**
     * @return string
     */
    public function getPicto(): string
    {
        return DIRECTORY_SEPARATOR .
            'uploads' . DIRECTORY_SEPARATOR .
            'skill' . DIRECTORY_SEPARATOR .
            'logos' . DIRECTORY_SEPARATOR .
            $this->picto;
    }

    /**
     * @param string $picto
     */
    public function setPicto(string $picto): void
    {
        $this->picto = $picto;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param $datetime
     */
    public function setStartedAt($datetime): void
    {
        if (is_string($datetime)) {
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
        return $this->endedAt;
    }

    /**
     * @param $datetime
     */
    public function setEndedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->endedAt = new \DateTime($datetime);
        } else {
            $this->endedAt = $datetime;
        }
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace(string $place): void
    {
        $this->place = $place;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
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