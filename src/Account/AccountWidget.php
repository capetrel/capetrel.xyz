<?php
namespace App\Account;

use App\Admin\AdminWidgetInterface;
use App\Auth\Table\UserTable;
use App\Framework\Renderer\RendererInterface;

class AccountWidget implements AdminWidgetInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var UserTable
     */
    private $userTable;

    public function __construct(RendererInterface $renderer, UserTable $userTable)
    {
        $this->renderer = $renderer;
        $this->userTable = $userTable;
    }

    public function render(): string
    {
        $users = $this->userTable->count();
        return $this->renderer->render('@account/admin/widget', compact('users'));
    }

    public function renderMenu(): string
    {
        return $this->renderer->render('@account/admin/menu');
    }
}
