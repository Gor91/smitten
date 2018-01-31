<?php
/**
 * UserStatus
 *
 * @package    frontend\modules\v1
 * @subpackage models
 * @author     SIXELIT <SixelIt.com>
 */

namespace frontend\modules\v1\models;

/**
 * This is the model class for table "status".
 *
 * @property integer $id
 * @property string $label
 *
 * @property User[] $users
 */
class UserStatus extends \common\models\UserStatus
{


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        return false;
    }
}