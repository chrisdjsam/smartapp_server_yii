<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?<?php echo Yii::app()->params['app-version-no']?>" />
</head>

<body>
	<div class="container page-default popup-container">


		<div class="page-body popup-body">
			<!-- mainmenu -->

			<?php echo $content; ?>

			<div class="clear"></div>
		</div>


	</div>
</body>
</html>
