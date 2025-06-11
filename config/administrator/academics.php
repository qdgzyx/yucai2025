<?php

use App\Models\Academic;
use Illuminate\Support\Facades\Auth;

return [
    'title'   => '学年管理',
    'single'  => '学年',
    'model'   => Academic::class,

    'action_permissions' => [
        'delete' => function () {
            return Auth::user()->hasRole('Founder');
        },
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '学年名称',
            'sortable' => false,
        ],
        'start_date' => [
            'title'    => '起始年份',
            'sortable' => false,
        ],
        'end_date' => [
            'title'    => '结束年份',
            'sortable' => false,
        ],
        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '学年名称',
        ],
        'start_date' => [
            'title' => '起始年份',
            'type'  => 'number',
        ],
        'end_date' => [
            'title' => '结束年份',
            'type'  => 'number',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '学年名称',
        ],
    ],
    'rules' => [
        'name' => 'required|min:2|unique:academics',
        'start_date' => 'required|integer|min:2000',
        'end_date' => 'required|integer|gt:start_year'
    ],
    'messages' => [
        'name.unique' => '学年名称已存在',
        'start_date.gt' => '结束年份必须大于起始年份'
    ]
];