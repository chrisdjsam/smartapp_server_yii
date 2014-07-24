<?php

/**
 * The API ConsumerController is meant for AMQP related operation.
 */
class ConsumerController extends APIController {

	public function actionSendSMTPNotification(){
		$smtp_id = Yii::app()->request->getParam('id', '');
		$smpt_data = SMTPViaMQ::model()->findByPk($smtp_id);
		try {
			$message = new YiiMailMessage;
			$message->setBody($smpt_data->body, 'text/html');
			$message->subject = $smpt_data->subject;
			$message->addTo($smpt_data->to);
			$message->from = $smpt_data->from;
			$response = Yii::app()->mail->send($message);
			$smpt_data->status = 1;
			$smpt_data->response = $response;
			$smpt_data->end_time = round(microtime(true) * 1000);
			if (!$smpt_data->save()) {
				error_log("+++++++++++++++++++++++++++", 0);
				error_log("Failed to update SMTP status and response", 0);
				error_log("+++++++++++++++++++++++++++", 0);
				Yii::app()->end();
			}
		} catch (Exception $e) {
			error_log("+++++++++++++++++++++++++++", 0);
			$smpt_data->response = $e->getMessage();
			if (!$smpt_data->save()) {
				error_log("Failed to update SMTP status", 0);
			}
			error_log($e->getMessage(), 0);
			error_log("+++++++++++++++++++++++++++", 0);
		}
	}

	public function actionSendPushNotification(){
		$notification_id = Yii::app()->request->getParam('id', '');
		$gcm_result = '';
		$ios_result = '';
		$result = '';

		try {
			$notification_data_to_consume = NotificationLogs::model()->findByPk($notification_id);
			$notification_to = unserialize($notification_data_to_consume['notification_to']);
			$message_body = unserialize($notification_data_to_consume['message']);
			$registration_ids_gcm = $notification_to['gcm'];
			$registration_ids_ios = $notification_to['ios'];
			if (!empty($registration_ids_gcm)) {
				$gcm_result = ConsumerHelper::sendGCMPushNotification($registration_ids_gcm, $message_body);
			}
			if (!empty($registration_ids_ios)) {
				$registration_type_ios = $notification_to['ios_type'];
				$application_id_ios = $notification_to['ios_application_id'];
				$ios_result .= ConsumerHelper::sendIOSPushNotification($registration_ids_ios, $message_body, $registration_type_ios, $application_id_ios);
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
				$notification_data_to_consume->action = 'C';
				$notification_data_to_consume->response = $combined_response_str;
				$notification_data_to_consume->updated_on = $current_time;
				if(!$notification_data_to_consume->save()){
					error_log("+++++++++++++++++++++++++++", 0);
					error_log("Failed to update Push Notification log, for more info.....", 0);
					var_dump($notification_data_to_consume->errors);
					error_log("+++++++++++++++++++++++++++", 0);
					Yii::app()->end();
				}
			}
		} catch (Exception $e) {
			error_log("+++++++++++++++++++++++++++", 0);
			var_dump($e);
			error_log("+++++++++++++++++++++++++++", 0);
		}

	}

	public function actionSendXMPPNotification(){
		$notification_id = Yii::app()->request->getParam('id', '');
		$xmpp_notification_data = XmppNotificationViaMQ::model()->find('xmpp_uid=:xmpp_uid', array(':xmpp_uid'=>$notification_id));
		try {
			$from = $xmpp_notification_data['from'];
			$to = $xmpp_notification_data['to'];
			$message = $xmpp_notification_data['message'];
			$cmd = "sudo ejabberdctl send-message-chat " . $from . " " . $to . " " . $message;
			$output = shell_exec($cmd);
			$output = strval($output);
			$xmpp_notification_data->response = $output;
			if (!$xmpp_notification_data->save()) {
				error_log("+++++++++++++++++++++++++++", 0);
				error_log("Failed to update XMPP response", 0);
				error_log("+++++++++++++++++++++++++++", 0);
				Yii::app()->end();
			}
		} catch (Exception $e) {
			error_log("+++++++++++++++++++++++++++", 0);
			$xmpp_notification_data->response = $e->getMessage();
			if (!$xmpp_notification_data->save()) {
				error_log("Failed to update XMPP response", 0);
			}
			error_log($e->getMessage(), 0);
			error_log("+++++++++++++++++++++++++++", 0);
		}
	}

}

