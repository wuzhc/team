<?php
namespace frontend\form;

use common\models\Member;
use Yii;
use yii\base\Model;

/**
 * 找回密码
 */
class ModifyPwdForm extends Model
{
    public $oldPwd;
    public $newPwd;
    public $reNewPwd;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['oldPwd', 'validateOldPwd'],
            [['oldPwd', 'newPwd'], 'required','message'=>'{attribute}不能为空'],
            ['reNewPwd','compare','compareAttribute'=>'newPwd','message' => '两次密码不一致'],
        ];
    }

    /**
     * 验证原密码
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     * @since 2016-02-28
     */
    public function validateOldPwd($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->checkOldPwd()) {
                $this->addError($attribute, '原密码不正确');
            }
        }
    }

    /**
     * 检测旧密码是否正确
     *
     * @return bool
     */
    private function checkOldPwd()
    {
        $oldPwd = md5(Yii::$app->user->identity->authKey.$this->oldPwd);
        return $oldPwd == Yii::$app->user->identity->password;
    }

    /**
     * 修改密码
     *
     * @return bool
     * @since 2016-02-27
     */
    public function modifyPwd()
    {
        if (!$this->checkOldPwd()) {
            return false;
        }

        return Member::findIdentity(Yii::$app->user->id)->updateAttributes([
            'password' => md5(Yii::$app->user->identity->authKey.$this->newPwd),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPwd' => '新密码',
            'oldPwd' => '旧密码',
        ];
    }
}
