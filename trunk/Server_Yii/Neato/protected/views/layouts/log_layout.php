<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
	media="screen, projection" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jq_datatable/jquery.dataTables.css?<?php echo Yii::app()->params['app-version-no']?>" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?<?php echo Yii::app()->params['app-version-no']?>" />
<title>
	<?php echo CHtml::encode($this->pageTitle); ?>
</title>
<link rel="SHORTCUT ICON" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" />
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScript('app_base_url', 'var app_base_url = "' . Yii::app()->request->baseUrl . '";', CClientScript::POS_HEAD);
?>
</head>
<body>
	<div class="WaitingDialogClass" style="display: none;">
		<div id="WaitingDialog" title="Please Wait">
			<div id="displayWait">
				<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/ajax-loader.gif","Please Wait", array('class'=> 'wait-dialog-image')); ?>
			</div>
		</div>
	</div>
	<div class="app-messages hide-me">
		<?php
		foreach(Yii::app()->user->getFlashes() as $key => $message) {
			echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
		}
		$is_wp_enabled = Yii::app()->params['is_wp_enabled'];
		?>
	</div>
	<?php
	$isLoggedIn = !Yii::app()->user->getIsGuest();
	$isAdmin = false;
	if($isLoggedIn){
		$isAdmin = Yii::app()->user->isAdmin;
	}
	?>
	<div class="container page-default">
		<div class="page-header ">
			<div class="inner">
				<?php
			if($isLoggedIn){?>
				<?php if($isAdmin){?>
				<ul class="adminMenuUser">
					<li>
						Logged in as
						<a href="<?php echo $this->createUrl('/user/userprofile')?>" title="<?php echo Yii::app()->user->name;?>"><?php echo Yii::app()->user->name; ?></a>
						<?php echo "(Administrator)";?>
					</li>
					<?php if(!$is_wp_enabled){ ?>
					<li>
						<a href="<?php echo $this->createUrl('/user/changepassword')?>" title="Change my password">Change my password</a>
					</li>
					<?php }?>
					<li>
						<a href="<?php echo $this->createUrl('/user/logout')?>" title="Log Out"> Log out </a>
					</li>
				</ul>
				<ul class="menu-user ">
					<?php }?>
					<?php if(!$isAdmin){?>
					<ul class="menu-user noAdminMenuUser">
						<li>
							Logged in as
							<a href="<?php echo $this->createUrl('/user/userprofile')?>" title="<?php echo Yii::app()->user->name;?>">
								<?php echo Yii::app()->user->name;?>
							</a>
						</li>
						<?php }?>
						<?php if($isAdmin){ ?>
						<li>
							<a href="<?php echo $this->createUrl('/robot/list')?>" title="List of all Robots">Robots</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('user/list')?>" title="List of all Users">Users</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('/usersRobot/list')?>" title="List of all User-Robot Associations">User-Robot
								Associations</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('/online/list')?>" title="List of all online User-Robot">Who is online?</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('/notification/list')?>" title="Send Notifications">Notifications</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('/robot/types')?>" title="Robot Types">Types</a>
						</li>
						<li>
							<a href="<?php echo $this->createUrl('/app/list')?>" title="List of all available app versions ">Version Control</a>
						</li>
						<?php }?>
						<li>
							<a href="<?php echo $this->createUrl('/user/userprofile')?>" title="My Profile" class="neato_tab_my_profile">My Profile</a>
						</li>
						<?php if(!$isAdmin){?>
						<li>
							<a href="<?php echo $this->createUrl('/user/logout')?>" title="Log Out">Log out</a>
						</li>
						<?php } ?>
					</ul>
					<?php }	?>
					<h1>
						<div id="logo">
							<a href="<?php echo $this->createUrl("/")?>" title="Neato Robotics">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/logo.png","Neato Robotics", array('class'=> 'app-logo')); ?>
							</a>
						</div>
					</h1>
					<div class="top-buttons-container">
						<?php
						$register_url =  $this->createUrl('/user/register');
						if($is_wp_enabled){
							$register_url = Yii::app()->params['wordpress_api_url'].'wp-login.php?action=register';
						}
						?>
						<?php if(!$isLoggedIn){?>
						<div class="button-div button-login neato-button:hover">
							<a class="neato-button neato-button-login" href="<?php echo $this->createUrl('/user/login')?>" title="Login">Login</a>
						</div>
						<?php }?>
					</div>

			</div>
		</div>
		<!-- header -->
		<div class="page-body">
			<!-- mainmenu -->
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>
		<div class="page-footer">
			<div class="">
				<div class="clearfloat float-alt copyright-notice">
					Copyright &#169;
					<?php echo date('Y'); ?>
					<?php echo Yii::app()->name?>
				</div>
			</div>
		</div>
		<!-- footer -->
	</div>
	<!-- noty -->
	<!-- layouts -->
	<!-- themes -->
	<!-- button gradients with rounded corners -->
</body>
</html>
