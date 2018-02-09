<?php
/**
 * ApplyAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage friendActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\friendActions;

use frontend\modules\v1\models\FriendStatus;
use frontend\modules\v1\models\Friends;
use yii\rest\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ApplyAction extends Action
{

    /**
     * @api {PUT} /friends/{id} Apply friend request
     * @apiVersion 1.0.0
     * @apiName ApplyFriend
     * @apiGroup Friend
     * @apiPermission UserAccess
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *     {
     *         "code": 202,
     *         "name": "Accepted"
     *     }
     *
     * @apiErrorExample Response error: Not Found
     *    HTTP/1.1 404 Not Found
     *  {
     *      "code": 404,
     *      "name": "Not Found"
     *  }
     *
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        $identityId = Yii::$app->user->identity->getId();
        $model = Friends::findOne(['from' => $id, 'to' => $identityId]);

        if (Friends::find()->friendExists($identityId, $id) && ($model->statusId != FriendStatus::FRIEND)) {

            $model->statusId = FriendStatus::FRIEND;

            try {
                if ($model->save()) {
                    Yii::$app->getResponse()->setStatusCode(202);
                    return;
                }
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), 'app');
            }

            if (!$model->hasErrors()) {
                throw new ServerErrorHttpException();
            }

            return $model;
        }
        throw new NotFoundHttpException();
    }
}