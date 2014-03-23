<?php
/**
 * Yii Module Class Declaration for the API Module
 *
 */
class ApiModule extends CWebModule {

	/**
	 * The init method is called when the module is being created
	 * you may place code here to customize the module or the application
	 * import the module-level models and components
	 *
	 */
	public function init() {
		$this->setImport(array(
				'api.models.*',
				'api.components.*',
		));
	}

	/**
	 * Used to perform an action before the controller gets access to it.
	 * See Yii source code for more information
	 * @param $controller
	 * @param $action
	 * @return boolean
	 */
	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		}
		else
			return false;
	}

}
