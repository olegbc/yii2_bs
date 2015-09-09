<?php
/**
 * Created by PhpStorm.
 * User: BC
 * Date: 26.07.2015
 * Time: 15:11
 */
namespace app\models;

use yii\base\Model;
use yii;

class RegForm extends Model{

    public $username;
    public $password;
    public $email;
    public $status;

    public function rules(){
        return[
            [['username', 'email', 'password'], 'filter', 'filter' => 'trim'],
            [['username', 'email', 'password'], 'required'],
            ['username', 'string', 'min'=>2, 'max' => 255],
            ['password', 'string', 'min' => 6, 'max' => 255],
            ['username', 'unique',
                'targetClass'=> User::className(),
                'message' => 'Это имя уже занято.'],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass'=> User::className(),
                'message' => 'Эта почта уже занята'],
            ['status', 'default', 'value'=> User::STATUS_ACTIVE, 'on' => 'default'],
            ['status', 'default', 'value'=> User::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],
            ['status', 'in', 'range' => [
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE
            ]],
        ];
    }

    public function attributeLabels(){
        return [
            'username' => 'Имя пользователя',
            'email' => 'Эл.почта',
            'password' => 'Пароль'
        ];
    }

    public function reg(){
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if($this->scenario === 'emailActivation'){
            $user->generateSecretKey();
        }
        return $user->save() ? $user: null;
    }

    public function sendActivationEmail($user){
        return Yii::$app->mailer->compose('activationEmail', ['user'=>$user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (отправлено роботом).'])
            ->setTo($this->email)
            ->setSubject('Активация для '.Yii::$app->name)
            ->send();
    }
}
