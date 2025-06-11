<?php

use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

return [
    'title'   => '学期管理',
    'single'  => '学期',
    'model'   => Semester::class,

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
            'title'    => '学期名称',
            'sortable' => false,
        ],
        'academic' => [
            'title'    => '所属学年',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return model_admin_link($model->academic->name, $model->academic);
            }
        
        ],
        'start_date' => [
            'title'    => '开始日期',
            'sortable' => false,
        ],
        'end_date' => [
            'title'    => '结束日期',
            'sortable' => false,
        ],
        'is_current' => [
            'title'    => '当前学期',
            'sortable' => false,
            'output'   => function ($value) {
                return $value ? '是' : '否';
            }
        ],
        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '学期名称',
        ],
        'academic' => [
            'title'              => '所属学年',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'autocomplete'       => true,
            'search_fields'      => ["CONCAT(id, ' ', name)"],
            'options_sort_field' => 'id',
        ],
        'start_date' => [
            'title' => '开始日期',
            'type'  => 'date',
        ],
        'end_date' => [
            'title' => '结束日期',
            'type'  => 'date',
        ],
        'is_current' => [
            'title' => '设为当前学期',
            'type'  => 'bool',
        ],
    ],
    'filters' => [
        'academic' => [
            'title'              => '所属学年',  // 修正中文描述
            'type'               => 'relationship',
            'name_field'         => 'name',
            'autocomplete'       => true,
            'search_fields'      => ["CONCAT(id, ' ', name)"],
            'options_sort_field' => 'id',
        ],
        'is_current' => [
            'title' => '当前学期',
            'type'  => 'bool',
        ]
    ],
    'rules' => [
        'name' => 'required|min:2|unique:semesters',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date'
    ],
    'messages' => [
        'name.unique' => '学期名称已存在',
        'end_date.after' => '结束日期必须晚于开始日期'
    ]
];