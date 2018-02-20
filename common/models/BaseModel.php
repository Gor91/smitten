<?php
/**
 * BaseModel
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created = time();
        }

        $this->updated = time();
        return parent::beforeSave($insert);
    }
}