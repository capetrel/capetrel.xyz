<?php
use App\Cv\CvWidget;
use function DI\get;
use function DI\add;

return [
    'cv.prefix' => '/cv',
    'admin.widgets' => add([get(CvWidget::class)])
];
