<?php

namespace app\controllers;


use Yii;
use app\models\RegForm;
use app\models\LoginForm;
use app\models\User;
use app\models\Profile;
use app\models\SendEmailForm;
use app\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AccountActivation;

class MainController extends BehaviorsController
//class MainController extends \yii\web\Controller
{
    public $layout = 'basic';
    public $defaultAction = 'index';

    public function actionIndex()
    {
        $hello = 'Привет Мир';
        return $this->render(
            'index',
            [
                'hello' => $hello
            ]);
    }

    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(['/main/index']);
    }

    public function actionSearch()
    {
        $search = Yii::$app->session->get('search');
        Yii::$app->session->remove('search');
        if ($search):
            Yii::$app->session->setFlash(
                'success',
                'Результат поиска'
            );
        else:
            Yii::$app->session->setFlash(
                'error',
                'Не заполнена форма поиска'
            );
        endif;
        return $this->render(
            'search',
            [
                'search' => $search
            ]
        );
    }

    public function actionProfile(){
        $model = ($model = Profile::findOne(Yii::$app->user->id)) ? $model : new Profile();

        if($model->load(Yii::$app->request->post()) && $model->validate()):
            if($model->updateProfile()):
                Yii::$app->session->setFlash('success','Профиль измнен');
            else:
                Yii::$app->session->setFlash('error','Профиль не изменен');
                Yii::error('Ошибка записи.  Профиль не изменен');
                return $this->refresh();
            endif;
        endif;

        $model->updateProfile($model);

        return $this->render('profile'
            ,
            ['model' => $model]
        );
    }

    public function actionReg(){

        $emailActivation = Yii::$app->params['emailActivation'];

        $model = $emailActivation ? new RegForm(['scenario' => 'emailActivation']) : new RegForm();

        if($model->load(Yii::$app->request->post()) && $model->validate()):
            if($user = $model->reg()):
                if($user->status === User::STATUS_ACTIVE):
                    if(Yii::$app->getUser()->login($user)):
                        return $this->goHome();
                    endif;
                else:
                    if($model->sendActivationEmail($user)):
                        Yii::$app->session->setFlash('success', 'Письмо отправлено на емейл <strong>'.Html::encode($user->email).'</strong> (проверьте папку спам).');
                    else:
                        Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        Yii::error('Ошибка отправки письма.');
                    endif;
                    return $this->refresh();
                endif;
            else:
                Yii::$app->session->setFlash('error','Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            endif;
        endif;

        return $this->render(
            'reg',
            [
                'model' => $model
            ]
        );
    }

    public function actionLogin(){
        if(!Yii::$app->user->isGuest):
            return $this->goHome();
        endif;

        $loginWithEmail = Yii::$app->params['loginWithEmail'];

        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm();

        if($model->load(Yii::$app->request->post()) && $model->login()):
            return $this->goBack();
        endif;

        return $this->render(
            'login',
            [
                'model' => $model
            ]
        );
    }

    public function actionSendEmail()
    {
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('success', 'Проверьте почту');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($key)
    {
        try{
            $model = new ResetPasswordForm($key);
        }
        catch(InvalidParamException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning','Пароль изменен.');
                return $this->redirect(['/main/login']);
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionActivateAccount($key){
        try{
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user->activateAccount()):
            Yii::$app->session->setFlash('success', 'Активация прошла успешно.<strong>'.Html::encode($user->username).'</strong> вы теперь со мной');
        else:
            Yii::$app->session->setFlash('error', 'Ошибка активации.');
            Yii::error('Ошибка при активации.');
        endif;

        return $this->redirect(Url::to(['/main/login']));
    }

}
