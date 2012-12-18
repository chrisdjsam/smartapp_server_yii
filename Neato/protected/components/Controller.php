<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	*/
	public $breadcrumbs=array();

	/**
	 * Required by Facebook SDK Extension
	 * @param type $view
	 * @param type $output
	 * @return boolean
	*/
	protected function afterRender($view, &$output) {
		parent::afterRender($view, $output);

		//Yii::app()->facebook->addJsCallback($js); // use this if you are registering any $js code
		Yii::app()->facebook->initJs($output); // this initializes the Facebook JS SDK on all pages
		Yii::app()->facebook->renderOGMetaTags(); // this renders the OG tags

		return true;
	}

	/**
	 * Check for admin role for current logged in user,
	 * if not then it redirect to permission error page.
	 */
	public function check_for_admin_privileges(){
		if(!Yii::app()->user->isAdmin){
			$this->redirect(Yii::app()->request->baseUrl.'/site/PermissionError');
			Yii::app()->end();
		}
	}

	/**
	 * Check for the arguments provided by any action call,
	 * if arguments are not valid then throws CHttpException.
	 * It basically used to check the encoded string send by action call.
	 * @param string $string
	 * @throws CHttpException
	 * @return string
	 */
	public function check_function_argument($string){
		$regex = "/^[a-zA-Z\d_\-\.]+$/";
		if (! preg_match($regex, $string)) {
			throw new CHttpException(404,Yii::t('yii','Unable to resolve the request .'));
			Yii::app()->end();
		}
		return $string;
	}

	/**
	 * @see CController::beforeAction()
	 */
	public function beforeAction($action){
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		return true;
	}
}