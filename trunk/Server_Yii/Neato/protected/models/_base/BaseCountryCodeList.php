<?php

/**
 * This is the model class for table "country_code_list".
 *
 * The followings are the available columns in table 'country_code_list':
 * @property integer $id
 * @property string $iso2
 * @property string $short_name
 * @property string $long_name
 * @property string $iso3
 * @property string $numcode
 * @property string $un_member
 * @property string $calling_code
 * @property string $cctld
 * @property string $language
 */
class BaseCountryCodeList extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseCountryCodeList the static model class
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
		return 'country_code_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iso2', 'length', 'max'=>2),
			array('short_name, long_name', 'length', 'max'=>80),
			array('iso3', 'length', 'max'=>3),
			array('numcode', 'length', 'max'=>6),
			array('un_member', 'length', 'max'=>12),
			array('calling_code', 'length', 'max'=>8),
			array('cctld', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, iso2, short_name, long_name, iso3, numcode, un_member, calling_code, cctld, language', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'iso2' => 'Iso2',
			'short_name' => 'Short Name',
			'long_name' => 'Long Name',
			'iso3' => 'Iso3',
			'numcode' => 'Numcode',
			'un_member' => 'Un Member',
			'calling_code' => 'Calling Code',
			'cctld' => 'Cctld',
			'language' => 'Language',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('iso2',$this->iso2,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('long_name',$this->long_name,true);
		$criteria->compare('iso3',$this->iso3,true);
		$criteria->compare('numcode',$this->numcode,true);
		$criteria->compare('un_member',$this->un_member,true);
		$criteria->compare('calling_code',$this->calling_code,true);
		$criteria->compare('cctld',$this->cctld,true);
		$criteria->compare('language',$this->language,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}