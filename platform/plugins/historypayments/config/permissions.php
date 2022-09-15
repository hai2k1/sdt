<?php

return [
    [
        'name' => 'Historypayments',
        'flag' => 'historypayments.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'historypayments.create',
        'parent_flag' => 'historypayments.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'historypayments.edit',
        'parent_flag' => 'historypayments.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'historypayments.destroy',
        'parent_flag' => 'historypayments.index',
    ],
    [
        'name'        => 'GetStatus',
        'flag'        => 'GetStatus',
        'parent_flag' => 'getStatus',
    ],
];
