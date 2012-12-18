<?php

class RobotScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/popup', meaning
	 * using two-column layout. See 'protected/views/layouts/popup.php'.
	 */
	public $layout='//layouts/popup';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionPopupBlobview(){

		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_schedule_model = RobotSchedule::model()->findByAttributes(array('id' => $id));
		$blob_data_url = $robot_schedule_model->getBlobDataURL();

		$this->render('robot_popup_blob_data_view',array(
				'blob_data_url'=>$blob_data_url,
				'schedule_id'=>$id,
		));

	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionPopupXmlview(){

		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_schedule_model = RobotSchedule::model()->findByAttributes(array('id' => $id));
		$xml_data_url = $robot_schedule_model->getXMLDataURL();

		$this->render('robot_popup_xml_data_view',array(
				'xml_data_url'=>$xml_data_url,
				'schedule_id'=>$id,
		));

	}
}
