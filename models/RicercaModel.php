<?php
    
namespace app\models;
    use Yii;
    use yii\base\Model;

class RicercaModel extends Model
{
    public $q;
    public $arrivo;
    public $partenza;
    public $occupanti;
    public $min;
    public $max;
    public $date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['q'], 'safe'],
            [['arrivo', 'partenza','occupanti','min','max','date'], 'safe'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'Voglio andare a...'),

        ];
    }
}
