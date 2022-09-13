<?php

return [
    [
        'name' => 'Bycodes',
        'flag' => 'bycode.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'bycode.create',
        'parent_flag' => 'bycode.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'bycode.edit',
        'parent_flag' => 'bycode.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'bycode.destroy',
        'parent_flag' => 'bycode.index',
    ],
];
