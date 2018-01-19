<?php

namespace common\models;

use common\config\Conf;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%User}}".
 *
 * @property int $id
 * @property string $fdName 真实姓名
 * @property string $fdLogin 账号
 * @property string $fdPassword 密码
 * @property int $fdStatus 账号状态0未完成注册，1正常，2冻结
 * @property int $fdRoleID 身份，0超级管理员
 * @property string $fdPhone 手机号码
 * @property string $fdEmail 邮箱地址
 * @property string $fdPortrait 头像url
 * @property string $fdCreate 注册时间
 * @property string $fdVerify 账号通过验证时间
 * @property string $fdLastIP 最后登录IP
 * @property string $fdLastTime 最后登录时间
 * @property string $fdSalt 密码干扰项
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%User}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fdName', 'fdLogin', 'fdCreate', 'fdSalt', 'fdPassword'], 'required'],
            [['fdStatus', 'fdRoleID'], 'integer'],
            [['fdCreate', 'fdVerify', 'fdLastTime'], 'safe'],
            [['fdName', 'fdLogin', 'fdPassword'], 'string', 'max' => 32],
            [['fdPhone'], 'string', 'max' => 11],
            [['fdEmail'], 'string', 'max' => 64],
            [['fdPortrait'], 'string', 'max' => 255],
            [['fdLastIP'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fdName' => 'Fd Name',
            'fdLogin' => 'Fd Login',
            'fdPassword' => 'Fd Password',
            'fdStatus' => 'Fd Status',
            'fdRoleID' => 'Fd Role ID',
            'fdPhone' => 'Fd Phone',
            'fdEmail' => 'Fd Email',
            'fdPortrait' => 'Fd Portrait',
            'fdCreate' => 'Fd Create',
            'fdVerify' => 'Fd Verify',
            'fdLastIP' => 'Fd Last Ip',
            'fdLastTime' => 'Fd Last Time',
            'fdSalt' => 'Fd Salt',
        ];
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return Yii::$app->security->validatePassword($password, $this->fdPwdHash);
        return md5(md5($password).$this->fdSalt) === $this->fdPassword;
    }

    /**
     * Generates password hash from password and sets it to the model
     * @param string $password
     */
    public function setPassword($password)
    {
//        $this->fdPwdHash = Yii::$app->security->generatePasswordHash($password);
        $this->fdPassword = md5(md5($password).$this->fdSalt);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
//        $this->fdAuthKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
//        $this->fdPwdResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
//        $this->fdPwdResetToken = null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'fdStatus' => Conf::ENABLE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
//        return $this->fdAuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
//        return $this->getAuthKey() === $authKey;
    }
}
