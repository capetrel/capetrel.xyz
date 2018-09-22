<?php

use App\Home\HomeWidget;
use function DI\get;
use function DI\add;

return [
    'home.prefix' => '/home',
    'admin.widgets' => add([get(HomeWidget::class)])
];
