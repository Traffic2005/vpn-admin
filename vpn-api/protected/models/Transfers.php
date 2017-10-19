<?php

/**
 * This is the model class for table "transfers".
 *
 * The followings are the available columns in table 'transfers':
 * @property string $user_id
 * @property string $created_at
 * @property string $resource
 * @property string $amountBytes
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Transfers extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'transfers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, created_at, resource, amountBytes', 'required'),
            array('user_id', 'length', 'max' => 11),
            array('resource', 'length', 'max' => 256),
            array('amountBytes', 'length', 'max' => 20),
            // The following rule is used by search().
            array('user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id'     => 'User',
            'created_at'  => 'Created At',
            'resource'    => 'Resource',
            'amountBytes' => 'Amount Bytes',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('user_id', $this->user_id, true);

        return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return Transfers the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param $startDate
     * @param $finishDate
     *
     * @return mixed
     */
    public function getAbuseCompany($startDate, $finishDate)
    {
        return Yii::app()->db->createCommand()
                             ->select('c.id, c.name, c.transferQuota as quota, SUM(t.amountBytes)AS used')
                             ->from('companies c')
                             ->join('users u', 'u.company_id = c.id')
                             ->join("{$this->tableName()} t",
                                 "t.user_id = u.id AND t.created_at BETWEEN '$startDate' AND '$finishDate'"
                             )
                             ->group('c.id')
                             ->having(' SUM(t.amountBytes) > c.transferQuota')
                             ->queryAll();
    }

    /**
     * @param $companyId
     * @param $startDate
     * @param $finishDate
     *
     * @return mixed
     */
    public function getAbuseUser($companyId, $startDate, $finishDate)
    {
        return Yii::app()->db->createCommand()
                             ->select(' u.name, c.name AS company, SUM(t.amountBytes) AS used')
                             ->from("{$this->tableName()} t")
                             ->join('users u', 'u.id = t.user_id')
                             ->join('companies c', 'u.company_id = c.id')
                             ->where("t.created_at BETWEEN '$startDate' AND '$finishDate' AND u.company_id = $companyId"
                             )
                             ->group('t.user_id')
                             ->order('sum(t.amountBytes) DESC')
                             ->limit(1)
                             ->queryAll()[0];
    }

    /**
     * @return mixed
     */
    public function getTransferCount()
    {
        return Yii::app()->db->createCommand()
                             ->select('COUNT(*) as count')
                             ->from($this->tableName())
                             ->queryAll()[0];
    }
}
