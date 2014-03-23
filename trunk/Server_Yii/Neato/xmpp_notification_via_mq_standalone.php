<?php

function send_xmpp_notification_via_mq($environmentWithId) {

	if (empty($environmentWithId)) {
		return;
	}

	$environmentAndId = explode("|", $environmentWithId);
	$env = isset($environmentAndId[0]) ? $environmentAndId[0] : '';
	$xmpp_uid = isset($environmentAndId[1]) ? $environmentAndId[1] : '';

	if (empty($env) || empty($xmpp_uid)) {
		return;
	}
	if ($env === "prod") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "staging_neato";
	} else if ($env === "staging") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "staging_neato";
	} else if ($env === "dev") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "dev_neato";
	}else if ($env === "prod_vorwerk") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "staging_vorwerk";
	} else if ($env === "staging_vorwerk") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "staging_vorwerk";
	} else if ($env === "dev_vorwerk") {
		$username = "root";
		$password = "gtrsl123";
		$hostname = "localhost";
		$dbname = "dev_vorwerk";
	} else if ($env === "local") {
		$username = "root";
		$password = "root";
		$hostname = "localhost";
		$dbname = "neato";
	} else {
		echo ("Invalid environment.");
		return;
	}

	try {
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

		$xmpp_notification_data = mysql_fetch_array(mysql_query("SELECT * FROM `xmpp_notification_via_mq` WHERE  xmpp_uid = '".$xmpp_uid."'"));

		$from = $xmpp_notification_data['from'];
		$to = $xmpp_notification_data['to'];
		$message = $xmpp_notification_data['message'];

		$cmd = "sudo ejabberdctl send-message-chat " . $from . " " . $to . " " . $message;
		$output = shell_exec($cmd);
		$output = strval($output);

	} catch (Exception $e) {
		var_dump($e);
	}

	mysql_close($dbhandle);

	//    return $result;
}

?>
