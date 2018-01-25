<?php

namespace common\config;


/**
 * 常量定义
 * Class Conf
 * @package common\config
 */
class Conf
{
    const SUCCESS = 1;
    const FAILED = 0;

    const ENABLE = 1; // 可用状态
    const DISABLE = 2; // 不可用

    /** 用户状态 */
    const USER_DISABLE = 0; // 帐号不可用（未验证）
    const USER_ENABLE = 1; // 帐号可用
    const USER_FREEZE = 2; // 帐号被冻结

    /** 用户角色 */
    const ROLE_SUPER = 0; // 超级管理员
    const ROLE_ADMIN = 1; // 普通管理员
    const ROLE_MEMBER = 2; // 普通成员
    const ROLE_GUEST = 3; // 游客

    /** 任务完成进度 */
    const TASK_STOP = 0; // 停止（默认状态）
    const TASK_BEGIN = 1; // 开始处理
    const TASK_FINISH = 2; // 已完成

    /** mongo集合名称 */
    const M_USER_LOGIN_LOG = 'userLoginLog';
    const M_HANDLE_LOG = 'handleLog';

    /** redis键 */
    const R_COUNTER_PROJ_TASK_NUM = 'counter:proj:';

    /** 默认用户头像 */
    const USER_PORTRAIT = 'http://file.cnweike.cn/content/0/0/0/251/255072.png?r=1516088302';

    /** 操作常量定义 */
    /*action 动作*/
    const ACTION_CREATE = 1; // 创建
    const ACTION_EDIT = 2; // 编辑
    const ACTION_DEL = 3; // 删除
    const ACTION_ASSIGN = 4; // 指派
    const ACTION_MOVE = 5; // 移动
    const ACTION_ALERT = 6; // 提醒

    /*target 操作目标*/
    const TARGET_PROJECT = 1; // 项目
    const TARGET_TEAM = 2; // 团队
    const TARGET_USER = 3; // 用户
    const TARGET_TASK = 4; // 任务
    const TARGET_DOC = 5; // 文档

    /*type 操作目标类型*/
    const TYPE_PROJECT = 1; // 项目
    const TYPE_TEAM = 2; // 团队
    const TYPE_USER = 3; // 用户
    const TYPE_TASK = 4; // 任务
    const TYPE_DOC = 5; // 文档
}
