<?php

use function DI\get;

/**
 * fichier de configuration spécifique au module Blog
 */

return [
    'blog.prefix' => '/blog',
    'admin.widgets' => \DI\add([
        get(\App\Blog\BlogWidget::class)
    ])
];
