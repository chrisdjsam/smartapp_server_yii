<?php

/**
 * This is the model class for table "atlas_grid_image".
 *
 * The followings are the available columns in table 'atlas_grid_image':
 * @property string $id
 * @property string $id_atlas
 * @property string $id_grid
 * @property string $blob_data_file_name
 * @property string $version
 *
 * The followings are the available model relations:
 * @property RobotAtlas $idAtlas
 */
class BaseAtlasGridImage extends GxActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BaseAtlasGridImage the static model class
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
		return 'atlas_grid_image';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_atlas, id_grid', 'required'),
			array('id, id_atlas, id_grid, version', 'length', 'max'=>20),
// 			array('blob_data_file_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_atlas, id_grid, blob_data_file_name, version', 'safe', 'on'=>'search'),
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
			'idAtlas' => array(self::BELONGS_TO, 'RobotAtlas', 'id_atlas'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_atlas' => 'Id Atlas',
			'id_grid' => 'Grid Id',
			'blob_data_file_name' => 'Blob Data File Name',
			'version' => 'Version',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('id_atlas',$this->id_atlas,true);
		$criteria->compare('id_grid',$this->id_grid,true);
		$criteria->compare('blob_data_file_name',$this->blob_data_file_name,true);
		$criteria->compare('version',$this->version,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
}