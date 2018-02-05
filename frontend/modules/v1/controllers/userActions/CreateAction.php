<?php

/**
 * User registration
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage userActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\userActions;

use common\components\Generator;
use frontend\modules\v1\models\SmsCode;
use frontend\modules\v1\models\User;
use frontend\modules\v1\models\UserLocation;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class CreateAction extends Action
{
    /**
     * @return array|User
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /** @var $model User */
        $model = new $this->modelClass;
        $userLocation = new UserLocation();

        $model->setScenario(User::SCENARIO_CREATE);
        $userLocation->setScenario(User::SCENARIO_CREATE);

        $model->fName = Yii::$app->request->getBodyParam('fName');
        $model->lName = Yii::$app->request->getBodyParam('lName');
        $model->username = Yii::$app->request->getBodyParam('username');
        $model->password = Yii::$app->request->getBodyParam('password');
        $model->gender = Yii::$app->request->getBodyParam('gender');
        $model->phone = Yii::$app->request->getBodyParam('phone');
        $model->dob = Yii::$app->request->getBodyParam('dob');
        $model->lng = Yii::$app->request->getBodyParam('lng');
        $location = Yii::$app->request->getBodyParam('location');

        $userLocation->countryName = ArrayHelper::getValue($location, 'countryName');
        $userLocation->countryCode = ArrayHelper::getValue($location, 'countryCode');
        $userLocation->cityName = ArrayHelper::getValue($location, 'cityName');
        $userLocation->cityCode = ArrayHelper::getValue($location, 'cityCode');
        $userLocation->lat = ArrayHelper::getValue($location, 'lat');
        $userLocation->lng = ArrayHelper::getValue($location, 'lng');

        if ($model->validate()) {
            $model->generateAuthKey();
            $model->setPassword($model->password);

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->save()) {
                    $userLocation->userId = $model->id;

                    if (!$userLocation->validate()) {
                        return $userLocation;
                    }

                    if ($userLocation->save()) {
                        if (true) {
                            $smsModel = new SmsCode();

                            $smsModel->userId = $model->id;
                            $smsModel->code = Generator::randomInt(Yii::$app->params['code.min'], Yii::$app->params['code.max']);

                            if ($smsModel->save()) {
                                $transaction->commit();

                                Yii::info('New registration', 'app');
                                Yii::$app->getResponse()->setStatusCode(201);

                                return [
                                    'token' => $model->token,
                                    'code' => $smsModel->code
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), 'app');
            }

            $transaction->rollBack();
        }

        if (!$model->hasErrors()) {
            throw new ServerErrorHttpException();
        }

        return $model;
    }
}