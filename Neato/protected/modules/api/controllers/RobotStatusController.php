<?php

/**
 * The API RobotStatusController is meant for app related API actions.
 */
class RobotStatusController extends APIController {

    public function actionOnline(){
        
        $chat_user = Yii::app()->request->getParam('user', '');
        $server = Yii::app()->request->getParam('server', '');
        $message = "Came online";

        $chat_id = $chat_user . '@' . $server;
        
        $result = false;
        
        include_once Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database_config.php';
        
        $env = array();
        $env[] = $dev;
        $env[] = $staging;
        
        foreach ($env as $db_config) {
            $result = self::ping_from_robot($db_config, $chat_id, $message);
            if($result){
                break;
            }
        }

        if($result){
            echo 'data is stored';
        }
        
    }
    
    public static function ping_from_robot($env, $chat_id, $message) {
        
        $username = $env['username'];
        $password = $env['password'];
        $hostname = $env['hostname'];
        $dbname = $env['dbname'];

        //connection to the database
        $dbhandle = mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");
        
        if ($dbhandle === false) {
            echo("Stale DB Connection");
            $dbhandle = mysql_connect($hostname, $username, $password, true) or die("Unable to connect to MySQL");
        }
        if ($dbhandle === false) {
            echo("DB Connection Failed");
            return;
        }
        
        mysql_select_db($dbname, $dbhandle);

        $robot_data = mysql_fetch_array(mysql_query("SELECT * FROM `robots` WHERE chat_id = '$chat_id'"));
        
        if(!empty($robot_data)){
            mysql_query("INSERT INTO `robot_ping_log`(`robot_id`, `ping_timestamp`, `status`) VALUES (" . $robot_data['id'] . ", " . new CDbExpression('NOW()') . ", '$message')");
            mysql_close($dbhandle);
            return true;
        }
        
        mysql_close($dbhandle);
        return false;
        
    }
    
    public function actionOffline(){
        
        $chat_user = Yii::app()->request->getParam('user', '');
        $server = Yii::app()->request->getParam('server', '');
        $message = "Went offline";

        $chat_id = $chat_user . '@' . $server;
        
        $result = false;
        
        include_once Yii::app()->basePath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database_config.php';
        
        $env = array();
        $env[] = $dev;
        $env[] = $staging;
        
        foreach ($env as $db_config) {
            $result = self::ping_from_robot($db_config, $chat_id, $message);
            if($result){
                break;
            }
        }

        if($result){
            echo 'data is stored';
        }
        
    }   
}