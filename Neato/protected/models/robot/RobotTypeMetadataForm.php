<?php

/**
 * RobotTypeMetadataForm class.
 */

class RobotTypeMetadataForm extends CFormModel {

    public $id;
    public $type;
    public $name;
    public $sleep_time;
    public $lag_time;
    public $isNewRecord = true;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, sleep_time, lag_time, name', 'required'),
			array('id, sleep_time, lag_time', 'numerical', 'integerOnly'=>true),
			array('name, type, sleep_time, lag_time', 'length', 'max'=>500),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
                        'type' => 'Type',
			'name' => 'Name',
                        'sleep_time' => 'Sleep Time',
                        'lag_time' => 'Lag Time',
		);
	}

}
