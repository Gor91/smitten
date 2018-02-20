<?php
/**
 * IndexAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage interestActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\interestActions;

use frontend\modules\v1\models\InterestList;
use yii\rest\Action;

class IndexAction extends Action
{
    /**
     * @api {GET} /interest Interest list
     * @apiVersion 1.0.0
     * @apiName GetInterestList
     * @apiGroup Interest
     * @apiPermission UserAccess
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *       {
     *       "code": 200,
     *       "name": "OK",
     *       "data": {
     *          "interests": [
     *              {
     *                  "tag": "Astrology"
     *              },
     *              {
     *                  "tag": "Board Games"
     *              },
     *              {
     *                  "tag": "Camping"
     *              },
     *              {
     *                  "tag": "Cooking"
     *              },
     *              {
     *                  "tag": "Creative arts"
     *              },
     *              {
     *                  "tag": "Dancing"
     *              },
     *              {
     *                  "tag": "Extreme"
     *              },
     *              {
     *                  "tag": "Fashion"
     *              }
     *            ],
     *           "_links": {
     *              "self": {
     *                 "href": "http://smite.sixelitprojects.com/v1/interest/index?offset=1"
     *              }
     *           },
     *           "_meta": {
     *              "totalCount": 18,
     *              "pageCount": 1,
     *              "currentPage": 1,
     *              "perPage": 20
     *             }
     *          }
     *       }
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function run()
    {
        return InterestList::find()->listTags();
    }
}