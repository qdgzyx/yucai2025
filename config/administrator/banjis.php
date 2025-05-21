<?php

use App\Models\Banji;
use Illuminate\Support\Facades\Auth;  

return [
    'title'   => '班级管理',
    'single'  => '班级',
    'model'   => Banji::class,

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
            'title'    => '班级名称',
            'sortable' => false,
        ],
        'student_count' => [
            'title'    => '学生总数',
            'sortable' => false,
        ],
        'grade' => [
            'title'    => '年级',
            'sortable' => false,
            'output'   => function ($value, $model) {
                return model_admin_link($model->grade->name, $model->grade);
            },
        ],
        'user' => [
            'title'    => '班主任',
            'sortable' => false,
            'output'   => function ($value, $model) {
                $avatar = $model->user->avatar;
                $value = empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" style="height:22px;width:22px"> ' . $model->user->name;
                return model_link($value, $model->user);
            },
        ],


        'operation' => [
            'title'  => '管理',
            'sortable' => false,
        ],
    ],
    'edit_fields' => [
        'name' => [
            'title' => '班级名称',
        ],
        'student_count' => [
            'title' => '学生总数',
        ],
        'user' => [
            'title'              => '班主任',
            'type'               => 'relationship',
            'name_field'         => 'name',

            // 自动补全，对于大数据量的对应关系，推荐开启自动补全，
            // 可防止一次性加载对系统造成负担
            'autocomplete'       => true,

            // 自动补全的搜索字段
            'search_fields'      => ["CONCAT(id, ' ', name)"],

            // 自动补全排序
            'options_sort_field' => 'id',
        ],
        'grade' => [
            'title'              => '年级',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'search_fields'      => ["CONCAT(id, ' ', name)"],
            'options_sort_field' => 'id',
        ],

    ],
    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '班级名称',
        ],
        'student_count' => [
            'title' => '学生总数',
        ],
        'user' => [
            'title'              => '用户',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'autocomplete'       => true,
            'search_fields'      => array("CONCAT(id, ' ', name)"),
            'options_sort_field' => 'id',
        ],
        'grade' => [
            'title'              => '分类',
            'type'               => 'relationship',
            'name_field'         => 'name',
            'search_fields'      => array("CONCAT(id, ' ', name)"),
            'options_sort_field' => 'id',
        ],
    ],
    'rules'   => [
        'name' => 'required'
    ],
    'messages' => [
        'name.unique'   => '班级名在数据库里有重复，请选用其他名称。',
        'name.required' => '请填写',
    ],
];