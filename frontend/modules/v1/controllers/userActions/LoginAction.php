<?php
/**
 * LoginAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage userActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\userActions;

use frontend\modules\v1\models\UserStatus;
use Yii;
use frontend\modules\v1\models\User;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\components\Constants;
use yii\web\ServerErrorHttpException;

class LoginAction extends Action
{
    /**
     * @api {POST} /users/login App login
     * @apiHeaderExample {json} Headers
     *     {
     *       "Content-type": "application/json",
     *       "Os": "ios"
     *     }
     * @apiVersion 1.0.0
     * @apiName LoginUser
     * @apiGroup User
     * @apiPermission none
     *
     * @apiParam {String} phone/username Mandatory field.
     * @apiParam {String} password Mandatory password.
     *
     * @apiParamExample {json} Request
     *    {
     *      "username":"+37477646579",
     *      "password":"123456",
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 404 Not Found
     *    {
     *      "code":404,
     *      "name":"Not Found",
     *      "message":"system.err.loginOrPass"
     *    }
     *
     * @apiErrorExample  Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code":422,
     *      "name":"Data Validation Failed.",
     *      "errors":[
     *        {
     *          "field":"username",
     *          "message":"err.phone_or_username.required"
     *        },
     *        {
     *          "field":"password",
     *          "message":"err.required"
     *        }
     *      ]
     *    }
     *
     * @apiErrorExample  Response error ▼
     *    HTTP/1.1 403 Forbidden
     *      {
     *          "code": 403,
     *          "name": "Forbidden",
     *          "message": "system.err.profile_not_active"
     *      }
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *      {
     *          "code": 200,
     *          "name": "OK",
     *          "data": {
     *              "token": "LbL5rvBEtiMx2ltoyxSgsCQcQKXfx3NU",
     *              "user": {
     *                  "id": "1",
     *                  "fName": "Jone",
     *                  "lName": "Smith",
     *                  "username": "jone",
     *                  "gender": 1,
     *                  "phone": "+37477646579",
     *                  "age": 29,
     *                  "avatar": "http://smite.sixelitprojects.com/avatars/2018/2/5a867ea9ecc98.png",
     *                  "location": [
     *                      {
     *                          "countryName": "Armenia",
     *                          "countryCode": "AM",
     *                          "cityName": "Erevan",
     *                          "cityCode": "erevan"
     *                      }
     *                  ]
     *              }
     *          }
     *      }
     *
     * @return array|User
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var $model User */
        $model = new $this->modelClass;
        $model->setScenario(User::SCENARIO_LOGIN);

        $model->phone = Yii::$app->request->getBodyParam('phone');
        $model->username = Yii::$app->request->getBodyParam('username');
        $model->password = Yii::$app->request->getBodyParam('password');

        if ($model->validate()) {
            $credential = $model->phone ?: $model->username;

            /** @var $user User */
            $user = $model::find()->byUsernameOrPhone($credential);

            if (!is_null($user) && !$model->password) {
                throw new ForbiddenHttpException();
            } else if (is_null($user) || !Yii::$app->getSecurity()->validatePassword(md5($model->password), $user->password)) {
                throw new NotFoundHttpException(Constants::SYS_ERR_INVALID_CREDENTIALS);
            }

            switch ($user->statusId) {
                case UserStatus::PENDING:
                    throw new ForbiddenHttpException(Constants::SYS_ERR_PROFILE_NOT_ACTIVE);
                case UserStatus::BLOCKED:
                    throw new ForbiddenHttpException(Constants::SYS_ERR_PROFILE_BLOCKED);
            }

            return [
                'token' => $user->token,
                'user' => $user
            ];
        }

        if (!$model->hasErrors()) {
            throw new ServerErrorHttpException();
        }

        return $model;
    }
}