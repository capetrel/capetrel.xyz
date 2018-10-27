<?php
namespace App\Auth;

use App\Framework\Auth\UserInterface;

class User implements UserInterface
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    // public $password;

    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $lastname;

    /**
     * @var \DateTime
     */
    public $birthday;

    /**
     * @var string
     */
    public $tel1;

    /**
     * @var string
     */
    public $tel2;

    /**
     * @var string
     */
    public $driverLicence;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $avatar;

    /**
     * @var string
     */
    public $passwordReset;

    /**
     * @var \DateTime
     */
    public $passwordResetAt;

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setBirthday($datetime): void
    {
        if (is_string($datetime)) {
            $this->birthday = new \DateTime($datetime);
        } else {
            $this->birthday = $datetime;
        }
    }

    /**
     * @return string
     */
    public function getTel1(): string
    {
        return $this->tel1;
    }

    /**
     * @param string $tel1
     */
    public function setTel1(string $tel1): void
    {
        $this->tel1 = $tel1;
    }

    /**
     * @return string
     */
    public function getTel2(): string
    {
        return $this->tel2;
    }

    /**
     * @param string $tel2
     */
    public function setTel2(string $tel2): void
    {
        $this->tel2 = $tel2;
    }

    /**
     * @return string
     */
    public function getDriverLicence(): string
    {
        return $this->driverLicence;
    }

    /**
     * @param string $driverLicence
     */
    public function setDriverLicence(string $driverLicence): void
    {
        $this->driverLicence = $driverLicence;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
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
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }



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
