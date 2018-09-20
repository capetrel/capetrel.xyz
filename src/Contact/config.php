<?php
return [
    'contact.to' => \DI\get('mail.to'),
    \App\Contact\ContactAction::class => \DI\autowire()->constructorParameter('to', \DI\get('contact.to'))
];
