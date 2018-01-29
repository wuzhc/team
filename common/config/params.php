<?php
use common\config\Conf;

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

    // 任务等级样式类
    'taskLevel' => [
        0 => 'text-yellow',
        1 => 'text-warning',
        2 => 'text-red',
        3 => 'text-success',
    ],

    // 角色
    'role' => [
        Conf::ROLE_SUPER => '超级管理员',
        Conf::ROLE_ADMIN => '普通管理员',
        Conf::ROLE_MEMBER => '普通成员',
        Conf::ROLE_GUEST => '游客',
    ],

    // 背景颜色样式类
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

    // 颜色样式类1
    'colorOne' => [
        'aqua',
        'blue',
        'orange',
        'navy',
        'red',
        'purple',
        'green',
        'fuchsia',
        'yellow',
        'olive',
        'black',
        'maroon',
        'lime',
    ],

    // 颜色样式类2
    'colorTwo' => [
        'danger',
        'success',
        'warning',
        'primary'
    ],

    // 交通图标
    'transportationIcon' => [
        'fa-bicycle',
        'fa-bus',
        'fa-car',
        'fa-fighter-jet',
        'fa-motorcycle',
        'fa-plane',
        'fa-rocket',
        'fa-ship',
        'fa-space-shuttle',
        'fa-subway',
        'fa-taxi',
        'fa-train',
        'fa-truck'
    ],

    // 默认头像
    'defaultPortrait' => [
        'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1516529870286&di=89856ae3625adc5fd0e31e2c58f47ec9&imgtype=jpg&src=http%3A%2F%2Fportrait3.sinaimg.cn%2F1818230762%2Fblog%2F180',
        'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=2462265311,1790877426&fm=27&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=2711067143,3961645744&fm=27&gp=0.jpg',
        'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=1424212866,1672514568&fm=27&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=861963426,2321602261&fm=27&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3999684833,1365604362&fm=27&gp=0.jpg',
        'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=1277106952,3591051236&fm=27&gp=0.jpg',
        'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=1797640078,4104728626&fm=27&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4261850071,2329649290&fm=27&gp=0.jpg',
        'https://ss0.bdstatic.com/70cFvHSh_Q1YnxGkpoWK1HF6hhy/it/u=1244525275,867989435&fm=27&gp=0.jpg',
        'https://ss0.bdstatic.com/70cFuHSh_Q1YnxGkpoWK1HF6hhy/it/u=253394805,2252880835&fm=27&gp=0.jpg',
        'https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=2374174844,3560175017&fm=27&gp=0.jpg',
        'https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=1965350828,1166357149&fm=27&gp=0.jpg'
    ],

    // 操作目标类型
    'handleTargetType' => [
        Conf::TARGET_PROJECT => '项目',
        Conf::TARGET_TEAM    => '团队',
        Conf::TARGET_USER    => '用户',
        Conf::TARGET_TASK    => '任务',
        Conf::TARGET_DOC     => '文档',
    ],
];
