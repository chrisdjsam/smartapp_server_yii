<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
	xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/theme_main.css?<?php echo Yii::app()->params['app-version-no']?>" />
</head>
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.form-2.94.js"></script>
<body>
	<div class="WaitingDialogClass" style="display: none;">
		<div id="WaitingDialog" title="Please Wait">
			<div id="displayWait">
				<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/ajax-loader.gif","Please Wait", array('class'=> 'wait-dialog-image')); ?>
			</div>
		</div>
	</div>
	<div class="container page-default popup-container">
		<div class="page-body popup-body">
			<!-- mainmenu -->
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>
	</div>
</body>
<script defer src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/script.js?<?php echo Yii::app()->params['app-version-no']?>"></script>
<!-- noty -->
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/jquery.noty.min.js"></script>
<!-- layouts -->
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/layouts/bottomRight.js"></script>
<!-- themes -->
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/themes/default.js"></script>
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.dataTables.min.js"></script>
<!-- Jquery UI -->
<script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-ui-1.8.16.min.js"></script>
<!-- button gradients with rounded corners -->
<script defer>
 $(document).ready(function() {
	 $('.qtip').find('.neato-button').wrap('<div class="rounded-corners"/>');
	 });
 </script>
</html>
