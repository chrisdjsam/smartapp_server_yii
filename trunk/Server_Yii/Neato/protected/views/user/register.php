<?php
/* @var $this UserController */
/* @var $model User */

$cs = Yii::app()->getClientScript();
$cs->registerScript('fb_permissions', 'var fb_permissions = "' . Yii::app()->params['fb-permissions'] . '";', CClientScript::POS_HEAD);
$cs->registerScript('app-redirect', 'var redirect_url = "' . Yii::app()->user->getReturnUrl() . '";', CClientScript::POS_HEAD);

$this->breadcrumbs=array(
		'Users'=>array('index'),
		'Register',
);

?>
<?php echo $this->renderPartial('_register', array('model'=>$model)); ?>