<?php

include_once __DIR__ . '/amqp_helper.php';
include_once __DIR__ . '/amqp_config.php';

function send_smtp_notification_via_mq($smtp_id) {
	if (empty($smtp_id)) {
		return;
	}
	$url = APIPROTOCOL . APIHOSTNAME . APICONTROLLER . "sendSMTPMessage";
	$data_string = array();
	$data_string['id'] = $smtp_id;
	echo "++++++++++++++++++++++++++++++++++++++++++++";
	echo curl_call($url, array(), $data_string);
	echo "++++++++++++++++++++++++++++++++++++++++++++";
}

?>
