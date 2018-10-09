<?php

use App\Portfolio\PortfolioWidget;
use function DI\get;
use function DI\add;

return [
    'portfolio.prefix' => '/portfolio',
    'admin.widgets' => add([get(PortfolioWidget::class)])
];
