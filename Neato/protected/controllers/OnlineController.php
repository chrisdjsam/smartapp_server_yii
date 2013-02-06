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
		
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/robot/list');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();
		
		$online_users_chat_ids = AppCore::getOnlineUsers();
		
		$robot_data = Robot::model()->findAll();
		$online_robots= array();
		
		foreach ($robot_data as $robot){
			if(in_array($robot->chat_id, $online_users_chat_ids)){
				$online_robots[] = $robot; 
			}
		}
		$users_data = User::model()->findAll();
		$online_users = array();
		foreach ($users_data as $user){
			if(in_array($user->chat_id, $online_users_chat_ids )) {
				$online_users[] = $user;  
			}
		}

		$this->render('list',array(
				'users_data'=>$online_users,
				'robot_data'=>$online_robots,
		));
		
	}
		
}
