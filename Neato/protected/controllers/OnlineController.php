<?php
/**
 * This class deals with all Online related operations.
 *
 */
class OnlineController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/* Lists all users and robots.
	 */
	public function actionList()
	{
		if(Yii::app()->user->UserRoleId == '2'){
			$this->layout = 'support';
		}
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		try{
			RobotCore::refreshGetOnlineUsersData();
			$this->render('list');
		} catch (Exception $e) {
			$this->render('retry');
		}
	}

	public function actionOnlineUsersDataTable() {
		$dataColumns = array('name', 'email', 'u.chat_id', 'id');
		$dataIndexColumn = "id";
		$dataTable = "users u inner join online_chat_ids oci on u.chat_id = oci.chat_id";
		$dataDataModelName = null;
		$dataWhere = '';

		$result = AppCore::dataTableOperation($dataColumns, $dataIndexColumn, $dataTable, $_GET, $dataDataModelName, $dataWhere, true);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach ($result['rResult'] as $user) {

			$row = array();

			$user = User::model()->findByPk($user['id']);
			$user_email = '<a rel="'.$this->createUrl('/user/userprofilepopup', array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" href="'.$this->createUrl('/user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($user->id))).'" class="qtiplink" title="View details of ('.$user->email.')">'.$user->email.'</a>';

			$associated_robots = '';
			if ($user->doesRobotAssociationExist()) {
				$is_first_robot = true;
				foreach ($user->usersRobots as $value) {
					if (!$is_first_robot) {
						$associated_robots .= ",";
					}
					$is_first_robot = false;
					$associated_robots .= "<a class='single-item qtiplink robot-qtip' title='View details of (" . $value->idRobot->serial_number . ")' rel='" . $this->createUrl('/robot/popupview', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "' href='" . $this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($value->idRobot->id))) . "'>" . $value->idRobot->serial_number . "</a>";
				}
			}

			$row [] = $user->name;
			$row [] = $user_email;
			$row [] = $user->chat_id;
			$row [] = $associated_robots;
			$output ['aaData'] [] = $row;

		}

		$this->renderPartial('/default/defaultView', array('content' => $output));
	}

	public function actionOnlineRobotsDataTable() {
		$dataColumns = array('serial_number', 'name', 'r.chat_id', 'id');
		$dataIndexColumn = "id";
		$dataTable = "robots r inner join online_chat_ids oci on r.chat_id = oci.chat_id";
		$dataDataModelName = null;
		$dataWhere = '';

		$result = AppCore::dataTableOperation($dataColumns, $dataIndexColumn, $dataTable, $_GET, $dataDataModelName, $dataWhere, true);

		/*
		 * Output
		*/
		$output = array(
				'sEcho' => $result['sEcho'],
				'iTotalRecords' => $result['iTotalRecords'],
				'iTotalDisplayRecords' => $result['iTotalDisplayRecords'],
				'aaData' => array()
		);

		foreach (array_unique($result['rResult']) as $robot) {

			$robot = Robot::model()->findByPk($robot['id']);

			$row = array();

			$serial_number = '<a rel="' . $this->createUrl('/robot/popupview', array('h' => AppHelper::two_way_string_encrypt($robot->id))) . '" href="' . $this->createUrl('/robot/view', array('h' => AppHelper::two_way_string_encrypt($robot->id))) . '" class="qtiplink robot-qtip" title="View details of (' . $robot->serial_number . ')">' . $robot->serial_number . '</a>';

			$associated_users = '';
			if ($robot->doesUserAssociationExist()) {
				$is_first_user = true;
				foreach ($robot->usersRobots as $value) {
					if (!$is_first_user) {
						$associated_users .= ",";
					}
					$is_first_user = false;
					$associated_users .= "<a class='single-item qtiplink' title='View details of (" . $value->idUser->email . ")' rel='" . $this->createUrl('/user/userprofilepopup', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "' href='" . $this->createUrl('/user/userprofile', array('h' => AppHelper::two_way_string_encrypt($value->idUser->id))) . "'>" . $value->idUser->email . "</a>";
				}
			}

			$row[] = $serial_number;
			$row[] = $robot->name;
			$row[] = $robot->chat_id;
			$row[] = $associated_users;

			$output['aaData'][] = $row;
		}

		$this->renderPartial('/default/defaultView', array('content' => $output));
	}

	public function actionRefreshDataTable() {

		$output = array();
		$output['time'] = date('d-M-Y h:m:s:a') . ' (' . date_default_timezone_get() . ')';
		RobotCore::refreshGetOnlineUsersData();
		$this->renderPartial('/default/defaultView', array('content' => $output));

	}


}
