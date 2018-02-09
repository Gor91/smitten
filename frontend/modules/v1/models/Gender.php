<?php
/**
 * Gender
 *
 * @package    frontend\modules\v1
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\models;


class Gender extends \common\models\Gender
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
     * @param bool $insert
     * @return bool
     */
    public function afterSave($insert)
    {
        return false;
    }
}