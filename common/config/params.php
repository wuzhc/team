<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    // 登录提示语
    'loginMsg' => [
        '0' => '帐号或密码不正确',
        '1' => '登录成功',
        '2' => '帐号尚未验证',
        '3' => '帐号被冻结'
    ],

    // 角色
    'role' => [
        \common\config\Conf::ROLE_SUPER => '超级管理员',
        \common\config\Conf::ROLE_ADMIN => '普通管理员',
        \common\config\Conf::ROLE_MEMBER => '普通成员',
        \common\config\Conf::ROLE_GUEST => '游客',
    ],

    // 背景颜色
    'bgColor' => [
        'bg-aqua',
        'bg-blue',
        'bg-orange',
        'bg-navy',
        'bg-red',
        'bg-purple',
        'bg-green',
        'bg-fuchsia',
        'bg-yellow',
        'bg-olive',
        'bg-black',
        'bg-maroon',
        'bg-lime',
    ],
];
