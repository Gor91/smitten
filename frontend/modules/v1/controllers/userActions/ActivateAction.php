<?php
/**
 * User Activate
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage userActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\userActions;

use frontend\modules\v1\models\SmsCode;
use frontend\modules\v1\models\User;
use frontend\modules\v1\models\UserStatus;
use Yii;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ActivateAction extends Action
{
    /**
     * @api {POST} /users/activate User activate
     *
     * @apiVersion 1.0.0
     * @apiName PostUserActivation
     * @apiGroup User
     * @apiPermission UserAccess
     *
     *
     * @apiParamExample {json} Request
     *    {
     *      "code":"4587"
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 404 Not Found
     *    {
     *       "code": 404,
     *       "name": "Not Found"
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code": 422,
     *      "name": "Data Validation Failed.",
     *      "errors": [
     *           {
     *             "field": "code",
     *             "message": "err.out_of_range"
     *           }
     *       ]
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code": 422,
     *      "name": "Data Validation Failed.",
     *      "errors": [
     *           {
     *             "field": "code",
     *             "message": "err.wrong_value"
     *           }
     *      ]
     *    }
     *
     * @apiSuccessExample  Response success
     *   {
     *       "code": 201,
     *       "name": "Created",
     *       "data": {
     *          "user": {
     *             "id": "1",
     *             "fName": "Jone",
     *             "lName": "Smith",
     *             "username": "jone",
     *             "gender": 1,
     *             "phone": "+37444646549",
     *             "dob": "1989-02-02"
     *          }
     *       }
     *    }
     *
     * @return SmsCode|array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /**@var $user User */
        $user = Yii::$app->user->getIdentity();
        $code = Yii::$app->request->getBodyParam('code');

        $smsCode = SmsCode::findOne(['userId' => $user->getId()]);

        if (!is_null($smsCode) && !empty($smsCode)) {
            $smsCode->code = $code;
            $smsCode->setScenario(SmsCode::SCENARIO_ACTIVATION);

            if ($smsCode->validate()) {
                $validSmsCode = SmsCode::existsCode($code);

                if (!is_null($validSmsCode) && !empty($validSmsCode)) {
                    $model = User::findOne($user->getId());
                    $model->statusId = UserStatus::ACTIVE;

                    $transaction = Yii::$app->db->beginTransaction();

                    try {
                        if ($model->save()) {
                            SmsCode::deleteAll(['userId' => $user->getId()]);
                            $transaction->commit();

                            Yii::$app->getResponse()->setStatusCode(201);

                            return [
                                'user' => $model
                            ];
                        }
                    } catch (\Exception $e) {
                        Yii::error($e->getMessage(), 'app');
                    }

                    $transaction->rollBack();

                    if (!$model->hasErrors()) {
                        Throw new ServerErrorHttpException();
                    }
                }

                Throw new NotFoundHttpException();
            }

            return $smsCode;
        }

        Throw new NotFoundHttpException();
    }
}