<?php

/**
 * This class deals with all the site related operations.
 *
 */
class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
						'class'=>'CCaptchaAction',
						'backColor'=>0xFFFFFF,
				),
				// page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page'=>array(
						'class'=>'CViewAction',
				),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		//echo $this->redirect(Yii::app()->user->returnUrl);
		if (!Yii::app()->user->getIsGuest()) {
			if(Yii::app()->user->isAdmin){
				$this->redirect(Yii::app()->request->baseUrl.'/user/list');
			}else{
				$this->redirect(array('user/userprofile'));
			}
		}

		$this->redirect(Yii::app()->request->baseUrl.'/user/login');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest){
				echo $error['message'];
			}else{
				$this->render('error', $error);
			}
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionPermissionError()
	{
		$this->render('permissionerror');
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
						"Reply-To: {$model->email}\r\n".
						"MIME-Version: 1.0\r\n".
						"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the privacy policy page.
	 */
	public function actionPrivacy()
	{
		$this->render('privacy', array());
	}

	/**
	 * Displays the privacy policy page.
	 */
	public function actionSupportPrivacy()
	{
		$this->layout = 'support';
		$this->render('privacy', array());
	}
	
	
	/**
	 * Displays the terms page.
	 */
	public function actionTerms()
	{
		$this->render('terms', array());
	}

	/**
	 * Displays the terms page.
	 */
	public function actionSupportTerms()
	{
		$this->layout = 'support';
		$this->render('terms', array());
	}
	
	/**
	 * Displays the about_us page.
	 */
	public function actionAbout_us()
	{
		$this->render('about_us', array());
	}
	
	/**
	 * Displays the about_us page.
	 */
	public function actionSupportAbout_us()
	{
		$this->layout = 'support';
		$this->render('about_us', array());
	}
	

	/**
	 * Lists all API logging.
	 */
	public function actionWsloggingDetails()
	{
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/site/wsloggingDetails');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		self::check_for_admin_privileges();

		$wslogging_data = WsLogging::model()->findAll();
		$this->render('wsloggingDetails',array(
				'wslogging_data'=>$wslogging_data,
		));
	}

	/**
	 * Displays a particular API logging details.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewlogging()
	{
		$h_id = Yii::app()->request->getParam('h', AppHelper::two_way_string_encrypt(Yii::app()->user->id));
		$id = AppHelper::two_way_string_decrypt($h_id);
		self::check_function_argument($id);
		if (Yii::app()->user->getIsGuest()) {
			Yii::app()->user->setReturnUrl(Yii::app()->request->baseUrl.'/site/viewloggingdetails');
			$this->redirect(Yii::app()->request->baseUrl.'/user/login');
		}
		$wslogging_model=WsLogging::model()->findByPk($id);
		$this->render('viewlogging',array(
				'wslogging_model'=>$wslogging_model,
		));
	}

}