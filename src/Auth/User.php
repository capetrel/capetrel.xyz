<?php
namespace App\Auth;

use App\Framework\Auth\UserInterface;

class User implements UserInterface
{

    public $id;

    public $username;

    public $email;

    // public $password;

    public $firstname;

    public $lastname;

    public $birthday;

    public $tel1;

    public $tel2;

    public $driverLicence;

    public $address;

    public $description;

    public $role;

    public $passwordReset;

    public $passwordResetAt;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return [$this->role];
    }


    /**
     * @return mixed
     */
    public function getPasswordReset()
    {
        return $this->passwordReset;
    }

    /**
     * @param mixed $passwordReset
     */
    public function setPasswordReset($passwordReset): void
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * @return mixed
     */
    public function getPasswordResetAt(): ?\DateTime
    {
        return $this->passwordResetAt;
    }

    public function setPasswordResetAt($date)
    {
        if (is_string($date)) {
            $this->passwordResetAt = new \DateTime($date);
        } else {
            $this->passwordResetAt = $date;
        }
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}
