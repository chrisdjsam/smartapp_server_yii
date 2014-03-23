<?php

/**
 * Default API Endpoint
 */
class DefaultController extends APIController {

	/**
	 * Redirect to the base of the application
	 */
	public function actionIndex() {
		$this->redirect('/');
	}

}