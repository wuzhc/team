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
    const M_MSG_NOTICE = 'msgNotice';

    /** redis键 */
    const R_COUNTER_PROJ_TASK_NUM = 'counter:proj:';

    /** 默认用户头像 */
    const USER_PORTRAIT = 'http://file.cnweike.cn/content/0/0/0/251/255072.png?r=1516088302';

    /** 操作对象 */
    const OBJECT_PROJECT = 1; // 项目
    const OBJECT_TEAM = 2; // 团队
    const OBJECT_USER = 3; // 用户
    const OBJECT_TASK = 4; // 任务
    const OBJECT_DOC = 5; // 文档

    /** 消息通知 */
    const MSG_SYSTEM = 1; // 系统通知
    const MSG_ADMIN = 2; // 管理员通知
    const MSG_HANDLE = 3; // 操作通知（包括指派任务，其他人操作你的文档，任务等等）

    const MSG_READ = 1; // 已读
    const MSG_UNREAD = 0; // 未读
}
