<?php

class CronCommand extends CConsoleCommand {

	public function actionClearExpiredLinkingCode() {
		// here we are doing what we need to do
		AppCore::removeExpiredLinkingCodeUsingCronJob();
	}

	public function actionRemoveDysfunctionalChatIds(){
		$intervalToRemoveDysFunctionalChatIds = Yii::app()->params['interval_to_remove_dysfunctional_chat_ids'];
		$sQuery = "Select r.chat_id FROM robot_ping_log pli inner join robots r on pli.serial_number = r.serial_number WHERE ping_timestamp < NOW() - INTERVAL " .$intervalToRemoveDysFunctionalChatIds. " MINUTE";
		$rResult = Yii::app()->db->createCommand($sQuery)->queryAll();
		foreach ($rResult as $robot) {
			$ejabberd_node = Yii::app()->params['ejabberdhost'];
			$chat_id = $robot['chat_id'];
			$chat_id = str_replace("@" . $ejabberd_node, "", $chat_id);
			$cmd = Yii::app()->params['ejabberdctl'] . " kick_session ".$chat_id." ".$ejabberd_node." '' 'Remove Dysfunctional Chat Ids'";
			shell_exec($cmd);
		}
	}

	public function actionCleanUpWSLog(){
		$intervalToRemoveOutdatedWSLog = Yii::app()->params['interval_to_remove_outdated_ws_log'];
		$sQuery = "DELETE FROM ws_logging WHERE date_and_time < NOW() - INTERVAL " .$intervalToRemoveOutdatedWSLog. " DAY";
		$rResult = Yii::app()->db->createCommand($sQuery)->execute();
	}

}


?>