<?php
namespace App\Auth;

use App\Auth\Table\UserTable;
use App\Framework\Auth\UserInterface;
use App\Framework\AuthInterface;
use App\Framework\Database\NoRecordException;
use App\Framework\Session\SessionInterface;

class DatabaseAuth implements AuthInterface
{

    /**
     * @var UserTable
     */
    private $userTable;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var User
     */
    private $user;

    public function __construct(UserTable $userTable, SessionInterface $session)
    {
        $this->userTable = $userTable;
        $this->session = $session;
    }

    public function login(string $username, string $password): ?User
    {
        if (empty($username) || empty($password)) {
            return null;
        }

        /** @var  $user User */
        $user = $this->userTable->findBy('username', $username);
        if ($user && password_verify($password, $user->password)) {
            $this->setUser($user);
            return $user;
        }
        return null;
    }

    public function logout(): void
    {
        $this->session->delete('auth.user');
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        if ($this->user) {
            return $this->user;
        }
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                $this->user = $this->userTable->find($userId);
                return $this->user;
            } catch (NoRecordException $exception) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    public function setUser(User $user): void
    {
        $this->session->set('auth.user', $user->id);
        $this->user = $user;
    }
}
