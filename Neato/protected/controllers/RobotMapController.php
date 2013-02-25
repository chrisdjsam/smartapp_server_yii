<?php

class RobotMapController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/popup', meaning
	 * using two-column layout. See 'protected/views/layouts/popup.php'.
	 */
	public $layout='//layouts/popup_form';

	/**
	 * Displays a particular robot map's XML component in the popup.
	 * @param integer $id the ID of the robot map to be displayed
	 */
	public function actionPopupBlobview(){
//		$this->layout='//layouts/popup';
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
//		$this->layout='//layouts/popup';
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

	/**
	 * Creates a new robot map data.
	 */
	public function actionAdd()
	{
//		$this->layout='//layouts/popup_form';

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotMap/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$sr_no = Yii::app()->request->getParam('sr_no', '');
		$sr_no = AppHelper::two_way_string_decrypt($sr_no);
		self::check_function_argument($sr_no);
		$id = Yii::app()->request->getParam('id_robot', '');
		$id = AppHelper::two_way_string_decrypt($id);
		self::check_function_argument($id);

		$model=new RobotMap();

		$this->render('add',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model));
	}

	/**
	 * Updates a robot map data.
	 */
	public function actionUpdate(){
//		$this->layout='//layouts/popup_form';
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotMap/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$map_id = Yii::app()->request->getParam('map_id', '');
		$map_id = AppHelper::two_way_string_decrypt($map_id);
		self::check_function_argument($map_id);
		$model = RobotMap::model()->find("id = :id",array(":id" =>$map_id));
		$xml_version = $model->getXMLDataLatestVersion();
		$blob_version = $model->getBlobDataLatestVersion();

		$sr_no = Yii::app()->request->getParam('sr_no', '');
		$sr_no = AppHelper::two_way_string_decrypt($sr_no);
		self::check_function_argument($sr_no);
		$id = Yii::app()->request->getParam('id_robot', '');
		$id = AppHelper::two_way_string_decrypt($id);
		self::check_function_argument($id);

		$this->render('update',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model, 'xml_version'=>$xml_version, 'blob_version'=> $blob_version));
	}
}