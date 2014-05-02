<?php

/**
 * The API ConsumerController is meant for AMQP related operation.
 */
class ConsumerController extends APIController {

	public function actionSendSMTPMessage(){
		$smtp_id = Yii::app()->request->getParam('id', '');
		$smpt_data = SMTPViaMQ::model()->findByPk($smtp_id);
		$message = new YiiMailMessage;
		$message->setBody($smpt_data->body, 'text/html');
		$message->subject = $smpt_data->subject;
		$message->addTo($smpt_data->to);
		$message->from = $smpt_data->from;
		Yii::app()->mail->send($message);
		$smpt_data->status = 1;
		if (!$smpt_data->save()) {
			error_log("+++++++++++++++++++++++++++", 0);
			error_log("Failed to update SMTP status", 0);
			error_log("+++++++++++++++++++++++++++", 0);
			Yii::app()->end();
		}
	}

}

