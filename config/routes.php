<?php

return [
    ['pattern' => "^/?$", 'controller' => ['controllers' => 'Main', 'action' => 'index']],
    ['pattern' => "^/?(?P<action>[a-zA-Z0-9-]+)$", 'controller' => ['controllers' => 'Main']],
    ['pattern' => "^/?product/(?P<slug>[a-zA-Zа-яА-Я0-9-]+)/?$", 'controller' => ['controllers' =>
        'Product', 'action' => 'view']],
];