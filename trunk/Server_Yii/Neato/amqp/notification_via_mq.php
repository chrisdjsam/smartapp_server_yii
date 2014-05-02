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
	$url = APIPROTOCOL . APIHOSTNAME . APICONTROLLER . "sendXMPPNotification";
	$data_string = array();
	$data_string['id'] = $notification_id;
	echo "++++++++++++++++++++++++++++++++++++++++++++";
	var_dump(curl_call($url, array(), $data_string));
	echo "++++++++++++++++++++++++++++++++++++++++++++";
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
