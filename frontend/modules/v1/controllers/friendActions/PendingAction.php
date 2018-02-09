<?php
/**
 * PendingAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage friendActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\friendActions;

use frontend\modules\v1\models\Friends;
use Yii;
use yii\rest\Action;

class PendingAction extends Action
{
    /**
     * @api {GET} /friends/pending friend request list
     * @apiVersion 1.0.0
     * @apiName GetList
     * @apiGroup Friend
     * @apiPermission UserAccess
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *   {
     *       "code": 200,
     *       "name": "OK",
     *       "data": {
     *           "result": [
     *               {
     *                   "id": "3",
     *                   "fName": "John",
     *                   "lName": "Lennon",
     *                   "username": "Johnlen",
     *                   "gender": 2,
     *                   "phone": "845845625",
     *                   "bio": "Some description"
     *               }
     *           ],
     *           "_links": {
     *               "self": {
     *                   "href": "http://api.smite.sixelitprojects.com/v1/friends/pending?offset=1"
     *               }
     *           },
     *           "_meta": {
     *               "totalCount": 1,
     *               "pageCount": 1,
     *               "currentPage": 1,
     *               "perPage": 10
     *           }
     *       }
     *   }
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function run()
    {
        $identityUser = Yii::$app->user->identity->getId();

        return Friends::find()->checkPendingList($identityUser);
    }
}