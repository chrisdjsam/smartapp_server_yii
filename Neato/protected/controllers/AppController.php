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

	public function actionWebServiceLog() {

		$dataColumns = array('id', 'method_name', 'serial_number', 'email', 'api_request', 'response_data', 'internal_process_values', 'remote_address', 'date_and_time', 'app_info_header', 'request_data');
		$dataIndexColumn = "id";
		$dataTable = "ws_logging";

		$dataDataModelName = 'WsLogging';

		$result = AppCore::dataTableOperation($dataColumns, $dataIndexColumn, $dataTable, $_GET, $dataDataModelName);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach ($result['rResult'] as $data) {

			$row = array();
			
			$row[] = $data->id;
			$row[] = $data->method_name;
			$row[] = $data->serial_number;
			$row[] = $data->email;
			$row[] = empty($data->api_request)?'':$data->api_request;
			$row[] = empty($data->response_data)?'':json_encode(unserialize($data->response_data));
			$internal_process_values = $data->internal_process_values;
			if($data->internal_process_values == 'null'){
				$internal_process_values = '';
			}
			$row[] = $internal_process_values;
			$row[] = $data->remote_address;
			$row[] = $data->date_and_time;
			$row[] = empty($data->app_info_header) ? '' : ($data->app_info_header);
			$row[] = empty($data->request_data)?'':json_encode(unserialize($data->request_data));


			$output['aaData'][] = $row;

		}

		$this->renderPartial('/default/defaultView', array('content' => $output));

	}
}
