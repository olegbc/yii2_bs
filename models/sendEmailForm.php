<?php
/**
 * Created by PhpStorm.
 * User: BC
 * Date: 10.08.2015
 * Time: 15:54
 */

namespace app\models;

use Yii;
use yii\base\Model;

class sendEmailForm extends Model {
    public $email;

    public function rules(){
        User::className();
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => [
                    'status' =>  User::STATUS_ACTIVE
                ],
                'message' => 'Данная электронная почта не зарегистрирована.'
            ],
        ];
    }

    public function attributeLabels(){
        return [
            'email' => 'Электронная почта'
        ];
    }

    public function sendEmail(){
        /* @var $user User */
        $user = User::findOne(
            [
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email
            ]
        );

        if($user):
            $user->generateSecretKey();
            if($user->save()):
                return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
//                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'(отправлено роботом)'])
                    ->setFrom('testtasktesttask@yandex.ru')
                    ->setTo($this->email)
                    ->setSubject('Сброс пароля для '.Yii::$app->name)
                    ->send();
            endif;
        endif;

        return false;
    }
} 