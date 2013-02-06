<?php

/**
 * This is the model class for table "upgrade_status".
 *
 * The followings are the available columns in table 'upgrade_status':
 * @property integer $upgrade_status_key
 * @property string $upgrade_status_value
 */
class BaseUpgradeStatus extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseUpgradeStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'upgrade_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('upgrade_status_key, upgrade_status_value', 'required'),
			array('upgrade_status_key', 'numerical', 'integerOnly'=>true),
			array('upgrade_status_value', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('upgrade_status_key, upgrade_status_value', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'upgrade_status_key' => 'Upgrade Status Key',
			'upgrade_status_value' => 'Upgrade Status Value',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('upgrade_status_key',$this->upgrade_status_key);
		$criteria->compare('upgrade_status_value',$this->upgrade_status_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}