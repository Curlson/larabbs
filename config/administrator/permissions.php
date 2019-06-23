<?php
/**
 * Created by PhpStorm.
 * User: idmin
 * Date: 2019/6/23
 * Time: 23:18
 */

use Spatie\Permission\Models\Permission;

return [
    'title' => '权限',
    'single' => '权限',
    'model' => Permission::class,

    'permission' => function () {
        return Auth::user()->can('manage_users');
    },

    // 对 CURD 动作的单独权限控制，通过返回bool值来控制权限
    'action_permissions' => [
        // 控制【新建按钮】的显示
        'create' => function ($model) {
            return true;
        },
        // 允许更新
        'update' => function ($model) {
            return true;
        },
        // 不允许删除
        'delete' => function ($model) {
            return false;
        },
        // 允许查看
        'view' => function ($model) {
            return true;
        }

    ],

    // 列表列
    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'name' => [
            'title' => '标示',
        ],
        'operation' => [
            'title' => '管理',
            'sortable' => false,
        ],
    ],

    // 可编辑字段
    'edit_fields' => [
        'name' => [
            'title' => '标示（请慎重修改）',

            // 表单条目标题旁的【提示信息】
            'hint' => '修改权限标识会影响代码的调用，请不要轻易更改。',
        ],
        'roles' => [
            'title' => '角色',
            'type' => 'relationship',
            'name_field' => 'name',
        ],
    ],

    'filters' => [
        'name' => [
            'title' => '标识',
        ]
    ]
];