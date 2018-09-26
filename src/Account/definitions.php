<?php

use App\Account\AccountWidget;
use function DI\get;
use function DI\add;

return [
    'auth.entity' => \App\Account\User::class,
    'admin.widgets' => add([get(AccountWidget::class)])
];
