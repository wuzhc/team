<?php

namespace frontend\form;


use common\config\Conf;
use common\services\UserService;
use common\utils\ClientUtil;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => '{attribute}不能为空'],
            //['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     * @param string $attribute the attribute currently being validated
     * @param array  $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            /** @var User $user */
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '密码不正确');
            }
        }
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|User
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserService::factory()->getUserObjByAccount($this->username);
        }
        return $this->_user;
    }

    /**
     * 登录
     * @return int 0帐号或密码不正确，1成功，2帐号未验证，3帐号被冻结
     * @see Yii::$app->param['loginMsg']
     */
    public function login()
    {
        if ($this->validate()) {

            $user = $this->getUser();
            if ($user->fdStatus != Conf::USER_ENABLE) {
                if ($user->fdStatus == Conf::USER_DISABLE) {
                    return 2;
                } elseif ($user->fdStatus == Conf::USER_FREEZE) {
                    return 3;
                }
            }

            // 注册登录后事件
            Yii::$app->user->on('afterLogin', function ($event) {
                UserService::factory()->saveLoginLog($event->identity->id);
                $event->identity->fdLastIP = ClientUtil::getClientIp();
                $event->identity->fdLastTime = date('Y-m-d H:i:s', time());
                $event->identity->save();
            });

            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                return 1;
            } else {
                return 0;
            }

        }

        return 0;
    }


    /**
     * 标签
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'rememberMe' => '记住密码',
            'username'   => '账号',
            'password'   => '密码'
        ];
    }
}
