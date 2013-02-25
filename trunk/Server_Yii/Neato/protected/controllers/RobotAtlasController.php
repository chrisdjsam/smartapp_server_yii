<?php

class RobotAtlasController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/popup', meaning
	 * using two-column layout. See 'protected/views/layouts/popup.php'.
	 */
	public $layout='//layouts/popup_form';

	/**
	 * Displays a particular robot map's blob component in the popup.
	 * @param integer $id the ID of the robot map to be displayed
	 */
	public function actionPopupXmlview(){
//		$this->layout='//layouts/popup';
		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$robot_atlas_model = RobotAtlas::model()->findByPk($id);
		$xml_data_url = $robot_atlas_model->getXMLDataURL();

		$this->render('robot_popup_xml_data_view',array(
				'xml_data_url'=>$xml_data_url,
				'atlas_id'=>$id,
				'id_robot'=>$robot_atlas_model->id_robot,
		));
	}

	/**
	 * Displays a specific view to add robot atlas.  
	 */
	public function actionAdd()
	{
//		$this->layout='//layouts/popup_form';

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotAtlas/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		
		$id = Yii::app()->request->getParam('id_robot', '');
		$id = AppHelper::two_way_string_decrypt($id);
		self::check_function_argument($id);
		
		$sr_no =  Robot::model()->findByPk($id)->serial_number;

		$model=new RobotAtlas();

		$this->render('add',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model));
	}

	/**
	 * Displays a specific view to update robot atlas.
	 */
	public function actionUpdate(){
		
//		$this->layout='//layouts/popup_form';
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotAtlas/update');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$id_robot = Yii::app()->request->getParam('id_robot', '');
		$id_robot = AppHelper::two_way_string_decrypt($id_robot);
		self::check_function_argument($id_robot);
		
		$sr_no = Robot::model()->findByPk($id_robot)->serial_number;
		
		$model = RobotAtlas::model()->find("id_robot = :id_robot",array(":id_robot" =>$id_robot));
		
		$xml_version = $model->getXMLDataLatestVersion();
		
		$this->render('update',array('sr_no'=>$sr_no, 'id'=> $id_robot, 'model'=>$model, 'xml_version'=>$xml_version));
	}
}