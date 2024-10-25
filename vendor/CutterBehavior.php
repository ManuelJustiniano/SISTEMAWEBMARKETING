<?php

namespace sadovojav\cutter\behaviors;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class CutterBehavior
 * @package sadovojav\cutter\behavior
 */
class CutterBehavior extends \yii\behaviors\AttributeBehavior
{
    /**
     * Attributes
     * @var
     */
    public $attributes;

    /**
     * Base directory
     * @var
     */
    public $baseDir;

    /**
     * Base path
     * @var
     */
    public $basePath;


    public $baseWeb;

    /**
     * Image cut quality
     * @var int
     */
    public $quality = 92;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeUpload',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpload',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeUpload()
    {
        if (is_array($this->attributes) && count($this->attributes)) {
            foreach ($this->attributes as $attribute) {
                $this->upload($attribute);
            }
        } else {
            $this->upload($this->attributes);
        }
    }

    public function upload($attribute)
    {
        $class = \yii\helpers\StringHelper::basename(get_class($this->owner)) . 'Cutter';

        if ($uploadImage = UploadedFile::getInstance($this->owner, $attribute)) {
            if (!$this->owner->isNewRecord) {
                $this->delete($attribute);
            }

            $cropping = $_POST[$class][$attribute . '-cropping'];

            $croppingFileName = md5($uploadImage->name . $this->quality . Json::encode($cropping));
            $croppingFileExt = strrchr($uploadImage->name, '.');
            //$croppingFileDir = substr($croppingFileName, 0, 2);

            $croppingFileBasePath = Yii::getAlias($this->basePath) . $this->baseDir;

            if (!is_dir($croppingFileBasePath)) {
                mkdir($croppingFileBasePath, 0755, true);
            }

            /*$croppingFilePath = Yii::getAlias($this->basePath) . $this->baseDir . DIRECTORY_SEPARATOR . $croppingFileDir;

            if (!is_dir($croppingFilePath)) {
                mkdir($croppingFilePath, 0755, true);
            }*/

            //$fileSavePath = $croppingFilePath . DIRECTORY_SEPARATOR . $croppingFileName . $croppingFileExt;
            $fileSavePath = $croppingFileBasePath . DIRECTORY_SEPARATOR . $croppingFileName . $croppingFileExt;

            $point = new Point($cropping['dataX'], $cropping['dataY']);
            $box = new Box($cropping['dataWidth'], $cropping['dataHeight']);

            $palette = new \Imagine\Image\Palette\RGB();
            $color = $palette->color('fff', 0);

            Image::frame($uploadImage->tempName, 0, 'fff', 0)
                ->rotate($cropping['dataRotate'], $color)
                ->crop($point, $box)
                ->save($fileSavePath, ['quality' => $this->quality]);

            //$this->owner->{$attribute} = $this->baseDir . DIRECTORY_SEPARATOR . $croppingFileDir
            $this->owner->{$attribute} = '@web' . DIRECTORY_SEPARATOR . $this->baseDir
                . DIRECTORY_SEPARATOR . $croppingFileName . $croppingFileExt;
        } elseif (isset($_POST[$class][$attribute . '-remove']) && $_POST[$class][$attribute . '-remove']) {
            $this->delete($attribute);
        } elseif (!empty($_POST[$class][$attribute])) {
            $this->owner->{$attribute} = $_POST[$class][$attribute];
        } elseif (isset($this->owner->oldAttributes[$attribute])) {
            $this->owner->{$attribute} = $this->owner->oldAttributes[$attribute];
        }
    }

    public function beforeDelete()
    {
        if (is_array($this->attributes) && count($this->attributes)) {
            foreach ($this->attributes as $attribute) {
                $this->delete($attribute);
            }
        } else {
            $this->delete($this->attributes);
        }
    }

    public function delete($attribute)
    {
        $file = Yii::getAlias($this->basePath) . $this->owner->oldAttributes[$attribute];

        if (is_file($file) && file_exists($file)) {
            unlink(Yii::getAlias($this->basePath) . $this->owner->oldAttributes[$attribute]);
        }
    }
}