<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

function send_push_notification($environmentWithId) {

	if (empty($environmentWithId)) {
		return;
	}

	$environmentAndId = explode("|", $environmentWithId);
	$env = isset($environmentAndId[0]) ? $environmentAndId[0] : '';
	$notification_id = isset($environmentAndId[1]) ? $environmentAndId[1] : '';

	if (empty($env) || empty($notification_id)) {
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

	$gcm_result = '';
	$ios_result = '';
	$result = '';

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

		$notification_data_to_consume = mysql_fetch_array(mysql_query("SELECT * FROM `notification_logs` WHERE id = $notification_id"));

		$notification_to = unserialize($notification_data_to_consume['notification_to']);

		$message_body = unserialize($notification_data_to_consume['message']);

		$registration_ids_gcm = $notification_to['gcm'];
		$registration_ids_ios = $notification_to['ios'];

		if (!empty($registration_ids_gcm)) {

			// API key from Google APIs
			$apiKey = 'AIzaSyAnczo1eXzLo6EdkWCa_EYqi-PqLA2kdBA';

			// Set POST variables
			$url_gcm = 'https://android.googleapis.com/gcm/send';
			$headers_gcm = array(
					'Authorization: key=' . $apiKey,
					'Content-Type: application/json'
			);
			$data_string_gcm = json_encode(array(
					'registration_ids' => $registration_ids_gcm,
					'data' => $message_body
			));

			$gcm_result = curl_call($url_gcm, $headers_gcm, $data_string_gcm);

			$result_object = json_decode($gcm_result);

			$removableStatuses = array();
			$removableStatuses[] = 'NotRegistered';
			$removableStatuses[] = 'InvalidRegistration';
			$removableStatuses[] = 'MismatchSenderId';

			if ($result_object->failure > 0 || $result_object->canonical_ids > 0) {
				foreach ($registration_ids_gcm as $key => $value) {

					$returnedErrorCode = isset($result_object->results[$key]->error) ? $result_object->results[$key]->error : '';

					if (in_array($returnedErrorCode, $removableStatuses)) {

						mysql_query("UPDATE `notification_registrations` SET `is_active` = 'N' WHERE registration_id = '$value'");

					} else {

						$new_registration_id = isset($result_object->results[$key]->registration_id) ? $result_object->results[$key]->registration_id : false;

						if ($new_registration_id) {

							$row = mysql_fetch_array(mysql_query("SELECT * FROM notification_registrations where registration_id = '$new_registration_id'"));

							if (empty($row)) {
								mysql_query("UPDATE `notification_registrations` SET `registration_id` = '$new_registration_id', `is_active` = 'Y' WHERE registration_id = '$value'");
							} else {
								mysql_query("UPDATE `notification_registrations` SET `is_active` = 'N' WHERE registration_id = '$value'");
							}
							mysql_query("INSERT INTO `notification_registration_id_logs`(`old_registration_id`, `new_registration_id`) VALUES ('$value', '$new_registration_id')");

						}
					}
				}
			}
		}

		if (!empty($registration_ids_ios)) {
			$registration_type_ios = $notification_to['ios_type'];
			$application_id_ios = $notification_to['ios_application_id'];
			$ios_result .= sendIOSPushNotification($registration_ids_ios, $message_body, $registration_type_ios, $application_id_ios);
		}

		$combined_response = array();

		if (!empty($gcm_result)) {
			$combined_response['gcm'] = $gcm_result;
			$result .= " gcm_response::" . $gcm_result;
		}

		if (!empty($ios_result)) {
			$combined_response['ios'] = $ios_result;
			$result .= PHP_EOL . " ios_response::" . $ios_result;
		}

		if (!empty($result)) {
			$combined_response_str = serialize($combined_response);
			$current_time = date('Y-m-d H:i:s');
			mysql_query("UPDATE `notification_logs` SET `action`='C', `response`='" . addslashes($combined_response_str) . "', `updated_on`='$current_time' WHERE id = $notification_id");
		}
	} catch (Exception $e) {
		var_dump($e);
	}

	mysql_close($dbhandle);

	//    return $result;
}

function curl_call($url, $headers, $data_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function sendIOSPushNotification($deviceToken, $message_body, $registration_type_ios, $application_id_ios) {

	$loc_key = array();
	$loc_key[101] = 'MSG_STUCK_NOTIFICATION_ID';
	$loc_key[102] = 'MSG_DIRT_BAG_FULL_NOTIFICATION_ID';
	$loc_key[103] = 'MSG_CLEANING_DONE_NOTIFICATION_ID';
	$loc_key[212] = 'MSG_UI_ALERT_PLUG_CABLE_NOTIFICATION_ID';
	$loc_key[22000] = 'MSG_UI_ERR_CANCEL_NOTIFICATION_ID';
	$loc_key[20219] = 'MSG_UI_ERR_DUST_BIN_MISSING_NOTIFICATION_ID';

	$loc_key_value = isset($message_body['notificationId']) ? $loc_key[$message_body['notificationId']] : 'MSG_GENERIC_NOTIFICATION_ID';

	// Create the payload body
	$body['aps'] = array(
			'alert' => array(
					'loc-key' => $loc_key_value,
					'action-loc-key' => "VIEW"));

	$message_body_ios = array();
	if(isset($message_body['robotId'])){
		$message_body_ios['robotId'] = $message_body['robotId'];
	}
	if (isset($message_body['notificationId'])) {
		$message_body_ios['id'] = $message_body['notificationId'];
	}
	$message_body_ios['message'] = $message_body['message'];
	$message_body_ios['time'] = $message_body['time'];

	$body['raw_data'] = $message_body_ios;

	// Encode the payload as JSON
	$payload = json_encode($body);

	$result = '';

	$app_type_index = 0;

	foreach ($deviceToken as $reg_id) {

		$fp = Null;
		if(!empty($registration_type_ios) && strtoupper($registration_type_ios[$app_type_index]) == 'DIST'){
			$fp = fp_for_dist($application_id_ios[$app_type_index]);
		}else {
			$fp = fp_for_dev($application_id_ios[$app_type_index]);
		}

		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $reg_id) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		$result .= fwrite($fp, $msg, strlen($msg)) . ' ';

		$app_type_index++;
	}

	// Close the connection to the server
	fclose($fp);

	return $result;
}

function fp_for_dev($application_id_ios){

	$iOSCertificatesPath = './neato.pem';
	$passphrase = 'neato123';

	error_log("++++++++++++++++++++++++++++++++++++++++", 0);
	error_log("DEV", 0);
	error_log($application_id_ios, 0);
	error_log("++++++++++++++++++++++++++++++++++++++++", 0);

	if(!empty($application_id_ios) && $application_id_ios == 'com.vorwerk-robot.vr200beta'){
		$iOSCertificatesPath = './vorwerk_push_notification_server_dev.pem';
		$passphrase = 'vorwerk123';
		error_log("===========================================", 0);
		error_log($iOSCertificatesPath, 0);
		error_log("===========================================", 0);
	}

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $iOSCertificatesPath);
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	// Open a connection to the APNS server
	$fp_dev = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

	if (!$fp_dev) {
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	}

	return $fp_dev;
}

function fp_for_dist($application_id_ios){

	$iOSCertificatesPath = './neato.pem';
	$passphrase = 'neato123';

	error_log("++++++++++++++++++++++++++++++++++++++++", 0);
	error_log("DIST", 0);
	error_log($application_id_ios, 0);
	error_log("++++++++++++++++++++++++++++++++++++++++", 0);

	if(!empty($application_id_ios) && $application_id_ios == 'com.vorwerk-robot.vr200beta'){
		$iOSCertificatesPath = './vorwerk_push_notification_server_dist.pem';
		$passphrase = 'vorwerk123';
		error_log("===========================================", 0);
		error_log($iOSCertificatesPath, 0);
		error_log("===========================================", 0);
	}

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $iOSCertificatesPath);
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	// Open a connection to the APNS server
	$fp_dist = stream_socket_client(
			'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

	if (!$fp_dist) {
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	}

	return $fp_dist;

}

?>
