 <?php

/**
 * This is the model class for table "smtp_via_mq".
 *
 * The followings are the available columns in table 'smtp_via_mq':
 * @property string $id
 * @property string $from
 * @property string $to
 * @property string $subject
 * @property string $body
 * @property integer $status
 * @property integer $response
 * @property string $created_on
 * @property string $updated_on
 */
class BaseSMTPViaMQ extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SmtpViaMq the static model class
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
        return 'smtp_via_mq';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('created_on', 'required'),
            array('status', 'numerical', 'integerOnly'=>true),
            array('from, to', 'length', 'max'=>255),
            array('subject, body', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, from, to, subject, body, status, response, created_on, updated_on', 'safe', 'on'=>'search'),
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
            'from' => 'From',
            'to' => 'To',
            'subject' => 'Subject',
            'body' => 'Body',
            'status' => 'Status',
        	'response' => 'Response',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
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
        $criteria->compare('from',$this->from,true);
        $criteria->compare('to',$this->to,true);
        $criteria->compare('subject',$this->subject,true);
        $criteria->compare('body',$this->body,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('response',$this->response);
        $criteria->compare('created_on',$this->created_on,true);
        $criteria->compare('updated_on',$this->updated_on,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}