<?php

/**
 * This class deals with all the Application related operations.
 *
 */
class AppController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Lists all application versions.
	 */
	public function actionList()
	{
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/app/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$app_data = AppInfo::model()->findAll();
		$this->render('list',array(
				'app_data'=>$app_data,
				'status_array' => UpgradeStatus::model()->getUpgradeStatusValue(),
		));
	}

	public function actionAdd(){

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/app/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$model = new AppInfo();
		$this->render('add',array(
				'model'=>$model,
				'status_array' => UpgradeStatus::model()->getUpgradeStatusValue(),
		));

	}

	public function actionUpdate(){

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/app/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}

		$h_id = Yii::app()->request->getParam('h', '');
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);

		$model = AppInfo::model()->findByPk($id);

		$this->render('update',array(
				'model'=>$model,
				'status_array' => UpgradeStatus::model()->getUpgradeStatusValue(),
		));

	}

	public function actionLog(){
		
		$this->layout = 'log_layout';

		$logLevelConversion = array("0"=> "None", "1" => "Low", "2"=>"High");
		$logSettings = Yii::app()->params['enablewebservicelogging'];
		$apiLogLevels = Yii::app()->params['api_verbosity'];
		$defaultLogLevel = $logLevelConversion[Yii::app()->params['default_api_verbosity']];

		$apiLogLevelsStr = array();

		foreach($apiLogLevels as $key => $value){
			$apiLogLevel = new stdClass();
			$apiLogLevel->api = $key;
			$apiLogLevel->logLevel = $logLevelConversion[$value];
			$apiLogLevelsStr[] = $apiLogLevel;
		}

		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/app/log');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		$this->render('log', array('logSettings'=>$logSettings, 'defaultLogLevel'=> $defaultLogLevel, 'apiLogLevelStr'=>$apiLogLevelsStr));
	}

}
