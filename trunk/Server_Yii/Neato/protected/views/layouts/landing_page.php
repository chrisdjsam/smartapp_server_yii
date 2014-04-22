<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Activation Successful - ' . Yii::app ()->name;
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="width=640" name="viewport">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/landing_page.css"
	media="screen, projection" />
</head>
<body id="activatelandingpage">
	<div id="logo">
		<img src="<?php echo Yii::app()->request->baseUrl.'/images/vorwerk-logo-transparent.png'?>" alt="Vorwerk Logo" />
	</div>
	<!-- mainmenu -->
			<?php echo $content; ?>

</body>
</html>
