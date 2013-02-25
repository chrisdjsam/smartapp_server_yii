<?php

class RobotScheduleController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/popup', meaning
	 * using two-column layout. See 'protected/views/layouts/popup.php'.
	 */
	public $layout='//layouts/popup_form';

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionPopupBlobview(){
                
//                $this->layout='//layouts/popup_form';
                
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
                
//                $this->layout='//layouts/popup_form';
                
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

	/**
	 * Creates a new robot schedule data.
	 */
	public function actionAdd()
	{
//		$this->layout='//layouts/popup_form';
	
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotSchedule/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
	
		$sr_no = Yii::app()->request->getParam('sr_no', '');
		$sr_no = AppHelper::two_way_string_decrypt($sr_no);
		self::check_function_argument($sr_no);
		
		$id = Yii::app()->request->getParam('id_robot', '');
		$id = AppHelper::two_way_string_decrypt($id);
		self::check_function_argument($id);
	
		$model=new RobotSchedule();
		$model->type="Basic";				
		$this->render('add',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model));
	}
	
	/**
	 * Updates a robot schedule data.
	 */
	public function actionUpdate(){
//		$this->layout='//layouts/popup_form';
		
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robotSchedule/update');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
	
		$schedule_id = Yii::app()->request->getParam('schedule_id', '');
		$schedule_id = AppHelper::two_way_string_decrypt($schedule_id);
		self::check_function_argument($schedule_id);
		$model = RobotSchedule::model()->find("id = :id",array(":id" =>$schedule_id));
		
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

