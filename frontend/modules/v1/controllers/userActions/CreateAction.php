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
     * @api {POST} /users Create/Registration
     * @apiHeaderExample {json} Headers
     *     {
     *       "Content-type": "application/json",
     *       "Os": "ios"
     *     }
     * @apiVersion 1.0.0
     * @apiName PostUser
     * @apiGroup User
     * @apiPermission none
     *
     * @apiParam {String{2..60}} fName Mandatory fName
     * @apiParam {String{2..60}} lName Mandatory lName
     * @apiParam {String{2..60}} username Mandatory username
     * @apiParam {String{6..60}} password Mandatory password
     * @apiParam {String{5..60}} phone Mandatory phone
     * @apiParam {Int=1,2} gender Mandatory gender (1.male, 2.female)
     * @apiParam {Date} dob Mandatory dob (format yyyy-mm-dd)
     * @apiParam {String="en","am","ch","de","fr","jp","ru",} [lang] with default value "en".
     *
     * @apiParam {String{..255}} countryName Mandatory countryName
     * @apiParam {String{..255}} countryCode Mandatory countryCode
     * @apiParam {String{..255}} cityName Mandatory cityName
     * @apiParam {String{..255}} cityCode Mandatory cityCode
     * @apiParam {String{..255}} lat Mandatory lat
     * @apiParam {String{..255}} lng Mandatory lng
     *
     * @apiParamExample {json} Request
     *     {
     *          "fName":"Jone",
     *          "lName":"Smith",
     *          "username":"jone",
     *          "password":"123456",
     *          "gender":"1",
     *          "dob":"1989-02-02",
     *          "phone":"+37477646579",
     *          "lang": "am",
     *          "location":{
     *              "countryName":"Armenia",
     *              "countryCode":"AM",
     *              "cityName":"Erevan",
     *              "cityCode":"erevan",
     *              "lat":"45.56",
     *              "lng":"42.15"
     *           }
     *      }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *      {
     *          "code": 422,
     *          "name": "Data Validation Failed.",
     *          "errors": [
     *              {
     *                  "field": "fName",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "lName",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "username",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "password",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "phone",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "gender",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "countryName",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "countryCode",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "cityName",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "cityCode",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "lat",
     *                  "message": "err.required"
     *              },
     *              {
     *                  "field": "lng",
     *                  "message": "err.required"
     *              }
     *          ]
     *      }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code":422,
     *      "name":"Data Validation Failed.",
     *      "errors": [
     *          {
     *              "field": "username",
     *              "message": "err.max_length"
     *          },
     *          {
     *              "field": "password",
     *              "message": "err.min_length"
     *          }
     *      ]
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code":422,
     *      "name":"Data Validation Failed.",
     *      "errors":[
     *          {
     *              "field": "username",
     *              "message": "err.exist"
     *          },
     *          {
     *              "field": "phone",
     *              "message": "err.exist"
     *          },
     *          {
     *              "field": "dob",
     *              "message": "err.big"
     *          }
     *      ]
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code":422,
     *      "name":"Data Validation Failed.",
     *      "errors":[
     *          {
     *              "field": "phone",
     *              "message": "err.not_valid"
     *          },
     *          {
     *              "field": "dob",
     *              "message": "err.small"
     *          }
     *      ]
     *    }
     *
     * @apiSuccessExample  Response success
     *    {
     *        "code": 202,
     *        "name": "Accepted",
     *        "data": {
     *            "token": "ERzWSTEJzq0EuK4uIJyDkHvFuTQDa0ck",
     *            "code": 1144
     *        }
     *    }
     *
     * @return array|User
     * @throws ServerErrorHttpException
     * @throws yii\base\Exception
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
        $model->lang = Yii::$app->request->getBodyParam('lang');
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
                                Yii::$app->getResponse()->setStatusCode(202);

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