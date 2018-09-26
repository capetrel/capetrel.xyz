<?php
namespace App\Account;

class User extends \App\Auth\User
{

    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $lastname;

    /**
     * @var string
     */
    public $role;

    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstName(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastName(string $lastname): void
    {
        $this->lastname = $lastname;
    }

}
