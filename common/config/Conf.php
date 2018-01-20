<?php

namespace common\config;


/**
 * 常量定义
 * Class Conf
 * @package common\config
 */
class Conf
{
    const ENABLE = 1; // 可用状态
    const DISABLE = 2; // 不可用

    /** 用户状态 */
    const USER_DISABLE = 0; // 帐号不可用（未验证）
    const USER_ENABLE = 1; // 帐号可用
    const USER_FREEZE = 2; // 帐号被冻结

    /** 用户角色 */
    const ROLE_GUEST = 0; // 游客
    const ROLE_SUPER = 1; // 超级管理员
    const ROLE_ADMIN = 2; // 普通管理员
    const ROLE_MEMBER = 3; // 普通成员

    /** mongo集合名称 */
    const M_USER_LOGIN_LOG = 'userLoginLog';

    /** redis键 */
    const R_COUNTER_PROJ_TASK_NUM = 'counter:proj:';
}
