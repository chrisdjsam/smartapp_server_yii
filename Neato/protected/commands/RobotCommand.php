<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

class RobotCommand extends CConsoleCommand {

	public function actionClearExpiredLinkingCode() {

		// here we are doing what we need to do

			AppCore::removeExpiredLinkingCodeUsingCronJob();

	}
}


?>