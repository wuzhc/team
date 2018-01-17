<?php

namespace frontend\form;

use common\models\User;
use common\services\UserService;
use common\utils\ClientUtil;
use common\utils\VerifyUtil;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $login;
    public $email;
    public $phone;
    public $password;
    public $readMe;
    public $name;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'required', 'message' => '账号不能为空'],
            ['login', 'string', 'min' => 2, 'max' => 32],
            ['login', 'checkLogin'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 64],
            ['email', 'checkEmail'],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'checkPhone'],

            ['password', 'required', 'message' => '密码不能为空'],
            ['password', 'string', 'min' => 6],

            ['name', 'required', 'message' => '姓名不能为空'],

            ['readMe', 'required', 'message' => '请阅读条款']
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkLogin($attribute, $params)
    {
        $isExist = User::find()->andWhere(['fdLogin' => $this->login])->exists();
        if ($isExist) {
            $this->addError($attribute, '改账号已经被注册');
        }
    }

    /**
     * 手机检测
     * @param $attribute
     * @param $params
     */
    public function checkPhone($attribute, $params)
    {
        if (!$this->phone) {
            return;
        }

        if (!VerifyUtil::checkPhone($this->phone)) {
            $this->addError($attribute, '手机格式不正确');
            return;
        }

        $isExist = User::find()->andWhere(['fdPhone' => $this->phone])->exists();
        if ($isExist) {
            $this->addError($attribute, '手机已被注册');
        }
    }

    /**
     * 邮箱检测
     * @param $attribute
     * @param $params
     */
    public function checkEmail($attribute, $params)
    {
        if (!$this->email) {
            return;
        }

        if (!VerifyUtil::checkEmail($this->email)) {
            $this->addError($attribute, '邮箱格式不正确');
            return;
        }

        $isExist = User::find()->andWhere(['fdEmail' => $this->email])->exists();
        if ($isExist) {
            $this->addError($attribute, '邮箱已被注册');
        }
    }

    /**
     * Signs user up.
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $this->_user = UserService::factory()->saveUser([
                'login'    => $this->login,
                'email'    => $this->email,
                'phone'    => $this->phone,
                'name'     => $this->name,
                'password' => $this->password,
            ]);
        } else {
            if (YII_DEBUG) {
                var_dump($this->errors);
                exit;
            }
        }
        return $this->_user;
    }

    /**
     * 登录成功更新最后登录时间和最后登录IP
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        // 注册登录后事件
        Yii::$app->user->on('afterLogin', function ($event) {
            UserService::factory()->saveLoginLog($event->identity->id);
            $event->identity->fdLastIP = ClientUtil::getClientIp();
            $event->identity->fdLastTime = date('Y-m-d H:i:s');
            $event->identity->save();
        });
        if (Yii::$app->user->login($this->_user)) {
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'readMe' => '我同意接受条款'
        ];
    }
}
