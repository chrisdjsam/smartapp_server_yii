<?php

class GridImageController extends Controller
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
	public function actionPopupBlobView(){
//		$this->layout='//layouts/popup';
		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		
		$atlas_grid_image_model = AtlasGridImage::model()->findByPk($id);
		$blob_data_url = $atlas_grid_image_model->getBlobDataURL();
		$this->render('robot_popup_blob_data_view',array(
				'blob_data_url'=>$blob_data_url,
				'grid_image_id'=>$id,
				'id_robot'=>$atlas_grid_image_model->idAtlas->id_robot,
		));
	}

	
	/**
	 * Displays a specific view to add grid image.
	 */
	public function actionAdd()
	{
//		$this->layout='//layouts/popup_form';

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/gridImage/add');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		
		$id = Yii::app()->request->getParam('id_robot', '');
		$id = AppHelper::two_way_string_decrypt($id);
		self::check_function_argument($id);
		
		$robot = Robot::model()->findByPk($id);
		$sr_no =  $robot->serial_number;
		
		$model=new AtlasGridImage();
		$model->id_atlas = $robot->robotAtlas->id;  
		
		$this->render('add',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model));
	}

	/**
	 * Displays a specific view to update grid image.
	 */
	public function actionUpdate(){
		
//		$this->layout='//layouts/popup_form';
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/gridImage/update');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$id_grid_image = Yii::app()->request->getParam('id_grid_image', '');
		$id_grid_image = AppHelper::two_way_string_decrypt($id_grid_image);
		self::check_function_argument($id_grid_image);
		
		$model = AtlasGridImage::model()->findByPk($id_grid_image);

		$robot = Robot::model()->findByPk($model->idAtlas->id_robot);
		$sr_no = $robot ->serial_number;
		$id = 	 $robot ->id; 
		$this->render('update',array('sr_no'=>$sr_no, 'id'=> $id, 'model'=>$model));
	}
}