<?php

include_once __DIR__ . '/amqp_helper.php';
include_once __DIR__ . '/amqp_config.php';

function send_push_notification($notification_id) {
	if (empty($notification_id)) {
		return;
	}
	$url = APIPROTOCOL . APIHOSTNAME . APICONTROLLER . "sendPushNotification";
	$data_string = array();
	$data_string['id'] = $notification_id;
	echo "++++++++++++++++++++++++++++++++++++++++++++";
	var_dump(curl_call($url, array(), $data_string));
	echo "++++++++++++++++++++++++++++++++++++++++++++";
}

function send_xmpp_notification($notification_id) {
	if (empty($notification_id)) {
		return;
	}

	$username = DB_USERNAME;
	$password = DB_PASSWORD;
	$hostname = DB_HOSTNAME;
	$dbname = DB_NAME;

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

	$notification_data = mysql_fetch_array(mysql_query("SELECT * FROM `xmpp_notification_via_mq` WHERE xmpp_uid = '$notification_id'"));
	$from = $notification_data['from'];
	$to = $notification_data['to'];
	$message = $notification_data['message'];
	$cmd = "sudo ejabberdctl send-message-chat " . $from . " " . $to . " " . $message;
	$output = shell_exec($cmd);
	mysql_query("UPDATE `xmpp_notification_via_mq` SET  `response`='".strval($output)."',`status`=1, `end_time`='".round(microtime(true) * 1000)."' WHERE xmpp_uid = '$notification_id'");
}

function send_smtp_notification($notification_id) {
	if (empty($notification_id)) {
		return;
	}
	$url = APIPROTOCOL . APIHOSTNAME . APICONTROLLER . "sendSMTPNotification";
	$data_string = array();
	$data_string['id'] = $notification_id;
	echo "++++++++++++++++++++++++++++++++++++++++++++";
	var_dump(curl_call($url, array(), $data_string));
	echo "++++++++++++++++++++++++++++++++++++++++++++";
}

?>
