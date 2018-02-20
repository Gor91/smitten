<?php
/**
 * FileHelper
 *
 * @package    common
 * @subpackage helpers
 * @author     SIXELIT <sixelit.com>
 */

namespace common\helpers;

use yii\web\UploadedFile;

class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @param $path
     * @param UploadedFile $file
     * @return bool
     */
    public static function upload($path, UploadedFile $file)
    {
        return $file->saveAs($path);
    }

    /**
     * This method deletes file from passed path.
     * @param String $file
     * @return bool
     */
    public static function deleteFile($file)
    {
        if (is_file($file)) {
            return @unlink($file);
        }
        return false;
    }


}