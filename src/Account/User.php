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

    /**
     * @var string
     */
    public $avatar;

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

    /**
     * @return string
     */
    public function getAvatar(): ?string
    {
        return
            'uploads' . DIRECTORY_SEPARATOR .
            'avatar' . DIRECTORY_SEPARATOR .
            $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getThumb(): ?string
    {
        if ($this->avatar === null) {
            return null;
        }
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->avatar);
        return
            'uploads' . DIRECTORY_SEPARATOR .
            'avatar' . DIRECTORY_SEPARATOR .
            $filename .'_thumb.' . $extension;
    }
}
