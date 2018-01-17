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

    /** 用户状态 */
    const USER_DISABLE = 0; // 帐号不可用（未验证）
    const USER_ENABLE = 1; // 帐号可用
    const USER_FREEZE = 2; // 帐号被冻结

    /** mongo集合名称 */
    const USER_LOGIN_LOG = 'userLoginLog';
}
