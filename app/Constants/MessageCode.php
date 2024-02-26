<?php

declare(strict_types=1);
/**
 * 状态码配置
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class MessageCode extends AbstractConstants
{
    /**
     * @Message("ok")
     */
    public const DATA_OK = 200;

    /**
     * @Message("未登录")
     */
    public const NO_LOGIN = 400;

    /**
     * @Message("失败")
     */
    public const DATA_ERROR = 1000;

    /**
     * @Message("操作失败")
     */
    public const OPERATION_FAIL = 1001;

    /**
     * @Message("验证码获取失败")
     */
    public const GET_SMS_CODE_FAIL = 2000;

    /**
     * @Message("盒子删除失败")
     */
    public const BOX_DELETE_FAIL = 2001;

    /**
     * @Message("盒子创建失败")
     */
    public const BOX_CREATE_FAIL = 2002;

    /**
     * @Message("盒子编辑失败")
     */
    public const BOX_EDIT_FAIL = 2003;

    /**
     * @Message("收餐失败")
     */
    public const BOX_ORDER_RECEIVE_FAIL = 2004;

    /**
     * @Message("订单完成操作失败")
     */
    public const BOX_ORDER_FINISH_FAIL = 2005;

    /**
     * @Message("重回订单池操作失败")
     */
    public const BOX_ORDER_BACK_FAIL = 2006;

    /**
     * @Message("转单操作失败")
     */
    public const BOX_ORDER_TRANS_FAIL = 2007;

    /**
     * @Message("今日数据获取失败")
     */
    public const STATIC_TODAY_FAIL = 2008;

    /**
     * @Message("提现金额不能小于或等于0.00元")
     */
    public const WITHDRAWAL_ZERO_ERROR = 2009;

    /**
     * @Message("每次提现,金额必须大于100.00元才能提现")
     */
    public const WITHDRAWAL_MIN_ERROR = 2010;

    /**
     * @Message("提现申请失败")
     */
    public const WITHDRAWAL_APP_FAIL = 2011;

    /**
     * @Message("提现申请失败，提现金额不能大于账户余额")
     */
    public const WITHDRAWAL_NO_ENOUGH_FAIL = 2012;

    /**
     * @Message("角色修改失败")
     */
    public const RIDER_ROLE_EDIT_FAIL = 2013;

    /**
     * @Message("骑手删除失败")
     */
    public const RIDER_DELETE_FAIL = 2014;
    /**
     * @Message("60秒内不能重复获取验证码")
     */
    public const SMS_FREQ_ERROR = 2015;

    /**
     * @Message("登录失败")
     */
    public const LOGIN_FAIL = 2016;

    /**
     * @Message("账号或手机号不存在")
     */
    public const LOGIN_ACCOUNT_NO_EXIST = 2017;

    /**
     * @Message("密码错误")
     */
    public const LOGIN_PASSWORD_ERROR = 2018;

    /**
     * @Message("验证码错误")
     */
    public const LOGIN_SMS_CODE_ERROR = 2019;

    /**
     * @Message("验证失败")
     */
    public const LOGIN_SMS_CODE_VERITY_FAIL = 2020;
    /**
     * @Message("未注册")
     */
    public const NO_REGISTER = 2021;
    /**
     * @Message("审核中")
     */
    public const OVER_STATE_ING = 2022;

    /**
     * @Message("审核中")
     */
    public const OVER_STATE_FAIL = 2023;
    /**
     * @Message("注册失败")
     */
    public const REGISTER_FAIL = 2024;
    /**
     * @Message("已注册")
     */
    public const HAD_REGISTERED = 2025;
    /**
     * @Message("盒子名称已存在")
     */
    public const BOX_TITLE_EXIST = 2026;
}
