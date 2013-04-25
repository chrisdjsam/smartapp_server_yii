<?php

/**
 * WebUser represents the persistent state for a Web application user by extending CWebUser.
 * It is meant to get current logged in users values (ex: name, email)
 *
 */
class WebUser extends CWebUser {

	/**
	 * @var object the active record object of current logged in user
	 */
	private $_model;

	/**
	 * It check for $_model, if not loaded then load the model.
	 * @param string $id
	 * @return object
	 */
	protected function loadUser($id = null) {
		if (is_null($id)) {
			$id = Yii::app()->user->id;
		}
		if ($this->_model === null) {
			if ($id !== null)
				$this->_model = User::model()->findByPk($id);
		}
		return $this->_model;
	}

	/**
	 * Returns the user name.
	 * access it by Yii::app()->user->name
	 * @return string the user name.
	 */
	function getName() {
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->name;
	}

	/**
	 * Returns the user email.
	 * access it by Yii::app()->user->email
	 * @return string the user email.
	 */
	function getEmail() {
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->email;
	}

	/**
	 * Returns the value that the user is admin or not (1/0).
	 * access it by Yii::app()->user->isAdmin
	 * @return bollean the user admin value.
	 */
	function getIsAdmin() {
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->is_admin;
	}
        
        /**
	 * Returns the value that the user is validated or not (1/0).
	 * access it by Yii::app()->user->isValidated
	 * @return boolean value.
	 */
	function getIsValidated() {
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->is_validated;
	}
        
        /**
	 * Returns the value that the user is validated or not (1/0).
	 * access it by Yii::app()->user->isValidated
	 * @return boolean value.
	 */
	function getCreatedOn() {
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->created_on;
	}
        
}

?>