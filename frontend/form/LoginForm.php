<?php

namespace frontend\form;


use common\services\UserService;
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
     * 登录成功更新最后登录时间和最后登录IP
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            // 注册登录后事件
            Yii::$app->user->on('afterLogin', function ($event) {
                UserService::factory()->saveLoginLog($event->identity->id);
                $event->identity->fdLastIP = Yii::$app->getRequest()->getUserIP();
                $event->identity->fdLastTime = date('Y-m-d H:i:s', time());
                $event->identity->save();
            });

            if (Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) {
                return true;
            }

        }
        return false;
    }

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
