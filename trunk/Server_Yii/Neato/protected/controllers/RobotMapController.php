<?php

class RobotMapController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/popup', meaning
	 * using two-column layout. See 'protected/views/layouts/popup.php'.
	 */
	public $layout='//layouts/popup';

	/**
	 * Displays a particular robot map's XML component in the popup.
	 * @param integer $id the ID of the robot map to be displayed
	 */
	public function actionPopupBlobview(){

		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_map_model = RobotMap::model()->findByAttributes(array('id' => $id));
		$blob_data_url = $robot_map_model->getBlobDataURL();

		$this->render('robot_popup_blob_data_view',array(
				'blob_data_url'=>$blob_data_url,
				'map_id'=>$id,
		));

	}

	/**
	 * Displays a particular robot map's blob component in the popup.
	 * @param integer $id the ID of the robot map to be displayed
	 */
	public function actionPopupXmlview(){

		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_map_model = RobotMap::model()->findByAttributes(array('id' => $id));
		$xml_data_url = $robot_map_model->getXMLDataURL();

		$this->render('robot_popup_xml_data_view',array(
				'xml_data_url'=>$xml_data_url,
				'map_id'=>$id,
		));

	}
}