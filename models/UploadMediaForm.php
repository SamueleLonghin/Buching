<?php

namespace app\models;

use http\Client;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class UploadMediaForm extends Model
{
    public $media;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['media'], 'required'],

        ];
    }

}
