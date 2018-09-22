<?php

use App\Page\PageWidget;
use function DI\get;
use function DI\add;

return [
    'page.prefix' => '/page',
    'admin.widgets' => add([get(PageWidget::class)])
];
