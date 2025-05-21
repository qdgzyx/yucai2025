<?php

use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

return [
    'title'   => '年级管理',
    'single'  => '年级',
    'model'   => Grade::class,

    // 对 CRUD 动作的单独权限控制，其他动作不指定默认为通过
    'action_permissions' => [
        // 删除权限控制
        'delete' => function () {
            // 只有站长才能删除话题分类
            return Auth::user()->hasRole('Founder');
        },
    ],

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title'    => '年级',
            'sortable' => false,
        ],
        'year' => [
            'title'    => '年份',
            'sortable' => false,
        ],
        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '年级',
        ],
        'year' => [
            'title' => '年份',
            'type'  => 'textarea',
        ],
    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '年级',
        ],
        'year' => [
            'title' => '年份',
        ],
    ],
    'rules'   => [
        'name' => 'required|min:1|unique:grades'
    ],
    'messages' => [
        'name.unique'   => '年级在数据库里有重复，请选用其他名称。',
        'name.required' => '请确保名字至少一个字符以上',
    ],
];