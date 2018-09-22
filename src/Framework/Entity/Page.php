<?php
namespace App\Framework\Entity;

class Page
{
    /**
     * int
     */
    private $id;

    /**
     * string
     */
    private $moduleName;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $menuTitle;

    /**
     * @var string
     */
    private $content;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param mixed $moduleName
     */
    public function setModuleName($moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        if (is_null($this->title)) {
            return '';
        }
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getMenuTitle(): ?string
    {
        if (is_null($this->menuTitle)) {
            return '';
        }
        return $this->menuTitle;
    }

    /**
     * @param string $menuTitle
     */
    public function setMenuTitle(string $menuTitle): void
    {
        $this->menuTitle = $menuTitle;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        if (is_null($this->content)) {
            return '';
        }
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
