<?php
/**
 * ChangeProfile
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage userActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\userActions;

use common\helpers\FileHelper;
use Exception;
use frontend\modules\v1\models\User;
use frontend\modules\v1\models\UserLocation;
use frontend\modules\v1\models\UserStatus;
use Yii;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class ChangeProfileAction extends Action
{
    /**
     * @api {POST} /users/profile Change profile
     * @apiHeaderExample {json} Headers
     *     {
     *       "Content-type": "multipart/form-data"
     *     }
     *
     * @apiVersion 1.0.0
     * @apiName PostChangeProfile
     * @apiGroup User
     * @apiPermission UserAccess
     *
     * @apiParam {String{2..60}} User[fName] Mandatory User[fName]
     * @apiParam {String{2..60}} User[lName] Mandatory  User[lName]
     * @apiParam {String{2..60}} User[username] Mandatory User[username]
     * @apiParam {String{..100}} [User[bio]]
     * @apiParam {String="en","ru","fr","de","ja","zh-Hant"} User[lang]
     *
     * @apiParam {String{..255}} UserLocation[countryName] Mandatory UserLocation[countryName]
     * @apiParam {String{..255}} UserLocation[countryCode] Mandatory UserLocation[countryCode]
     * @apiParam {String{..255}} UserLocation[cityName] Mandatory UserLocation[cityName]
     * @apiParam {String{..255}} UserLocation[cityCode] Mandatory UserLocation[cityCode]
     * @apiParam {float{..255}} UserLocation[lat] Mandatory UserLocation[lat]
     * @apiParam {float{..255}} UserLocation[lng] Mandatory UserLocation[lng]
     *
     * @apiParam {File="png","jpg","jpeg"} [avatar]
     *
     * @apiParamExample {json} Request
     *     {
     *          "UserLocation[fName]":"Jone",
     *          "UserLocation[lName]":"Smith",
     *          "UserLocation[username]":"jone",
     *          "UserLocation[bio]":"Some information",
     *          "UserLocation[lang]": "am",
     *          "UserLocation[countryName]":"Armenia",
     *          "UserLocation[countryCode]":"AM",
     *          "UserLocation[cityName]":"Erevan",
     *          "UserLocation[cityCode]":"erevan",
     *          "UserLocation[lat]":"45.56",
     *          "UserLocation[lng]":"42.15"
     *          "avatar":"uploaded file instance"
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
     *          }
     *      ]
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code":422,
     *      "name":"Data Validation Failed.",
     *      "errors": [
     *          {
     *              "field": "avatar",
     *              "message": "err.type_not_allowed"
     *          }
     *      ]
     *    }
     *
     * @apiErrorExample Response error ▼
     *    HTTP/1.1 422 Uncrossable Entity
     *    {
     *      "code": 422,
     *      "name": "Data Validation Failed.",
     *      "errors": [
     *          {
     *              "field": "avatar",
     *              "message": "err.image_too_large"
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
     *          }
     *      ]
     *    }
     *
     *@apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *    {
     *       "code": 200,
     *        "name": "OK",
     *        "data": {
     *             "id": "24",
     *             "fName": "Jone",
     *             "lName": "Smith",
     *             "username": "jone21",
     *             "gender": 1,
     *             "lang": "fr",
     *             "phone": "+37444746579",
     *             "bio": "I am looking for a life partner and someone who share the same interests as me",
     *             "age": 29,
     *             "avatar": "http://smite.sixelitprojects.com/avatars/2018/2/5a86e10455da0.jpg",
     *             "location": [
     *                 {
     *                     "countryName": "India",
     *                     "countryCode": "IN",
     *                     "cityName": "Mumbai",
     *                     "cityCode": "mumbai",
     *                     "lat": 19.1005,
     *                     "lng": 73.0303
     *                 }
     *             ]
     *        }
     *    }
     *
     * @return User|UserLocation|null
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $userId = Yii::$app->user->identity->getId();

        /** @var $model User */
        $model = User::findOne($userId);
        $model->setScenario(User::SCENARIO_CHANGE_PROFILE);
        $model->load(Yii::$app->request->post());

        if (is_null($model)) {
            throw new NotFoundHttpException();
        } elseif ($model->statusId != UserStatus::ACTIVE) {
            throw new ForbiddenHttpException();
        }

        /** @var $model UserLocation */
        $userLocation = UserLocation::findOne(['userId' => $userId]);
        $userLocation->setScenario(User::SCENARIO_CHANGE_PROFILE);
        $userLocation->load(Yii::$app->request->post());

        $oldAvatar = $model->avatar;
        $model->avatar = UploadedFile::getInstanceByName('avatar');

        $transaction = Yii::$app->db->beginTransaction();

        if (!$model->validate()) return $model;
        $userValid = true;

        if (!$userLocation->validate()) return $userLocation;
        $userLocationValid = true;

        $isValid = $userValid && $userLocationValid;

        if ($isValid) {
            try {
                $isChangedAvatar = false;
                $newAvatar = '';

                if ($model->avatar instanceof UploadedFile) {
                    $avatarName = sprintf('%s.%s', uniqid(), $model->avatar->extension);
                    $avatarPath = sprintf("%s/%d/%d", User::PATH_AVATARS, date('Y', time()), date('m', time()));
                    $newAvatar = sprintf('%s/%s', $avatarPath, $avatarName);
                    $path = sprintf('%s/%s', Yii::getAlias('@fWeb'), $avatarPath);

                    FileHelper::createDirectory($path);

                    if (FileHelper::upload($newAvatar, $model->avatar)) {
                        $model->avatar = $newAvatar;
                        $isChangedAvatar = true;
                    }
                } else {
                    $model->avatar = $oldAvatar;
                }

                if ($model->save(false) && $userLocation->save(false)) {
                    if ($isChangedAvatar) {
                        FileHelper::deleteFile($oldAvatar);
                    }

                    $transaction->commit();
                    return $model;
                }
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'app');
            }

            $transaction->rollBack();

            FileHelper::deleteFile($newAvatar);
        }

        if (!$model->hasErrors() && !$userLocation->hasErrors()) {
            throw new ServerErrorHttpException();
        }

        return null;
    }
}