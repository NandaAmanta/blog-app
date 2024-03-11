
<?php

use App\Const\Action;
use App\Const\Module;

return [
    [
        'role' => 'admin',
        'permissions' => [
            Action::CREATE . '.' . Module::ARTICLE,
            Action::DELETE . '.' . Module::ARTICLE,
            Action::UPDATE . '.' . Module::ARTICLE,
            Action::READ . '.' . Module::ARTICLE,

            Action::CREATE . '.' . Module::CATEGORY,
            Action::DELETE . '.' . Module::CATEGORY,
            Action::UPDATE . '.' . Module::CATEGORY,
            Action::READ . '.' . Module::CATEGORY,

            Action::CREATE . '.' . Module::USER,
            Action::DELETE . '.' . Module::USER,
            Action::UPDATE . '.' . Module::USER,
            Action::READ . '.' . Module::USER,
        ]
    ],
    [
        'role' => 'writer',
        'permissions' => [
            Action::CREATE . '.' . Module::ARTICLE,
            Action::DELETE . '.' . Module::ARTICLE,
            Action::UPDATE . '.' . Module::ARTICLE,
            Action::READ . '.' . Module::ARTICLE,
        ]
    ],
];
