<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />

<!-- blueprint CSS framework -->
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
	media="screen, projection" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
	media="print" />
<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui/jquery-ui-1.9.0.custom.min.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jq_datatable/jquery.dataTables.css?<?php echo Yii::app()->params['app-version-no']?>" />

<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jqtip.min.css?<?php echo Yii::app()->params['app-version-no']?>" />

<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?<?php echo Yii::app()->params['app-version-no']?>" />

<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="SHORTCUT ICON"
	href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" />
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScript('app_base_url', 'var app_base_url = "' . Yii::app()->request->baseUrl . '";', CClientScript::POS_HEAD);
?>

<!-- <script type="text/javascript" src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-1.6.4.min.js"></script>-->
	<!-- page -->
<script defer src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/script.js?<?php echo Yii::app()->params['app-version-no']?>"></script>
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
                            <ul class="adminMenuUser" >				
				<li>
                                    Logged in as 
                                    <a href="<?php echo $this->createUrl('/user/userprofile')?>" title="<?php echo Yii::app()->user->name;?>"><?php echo Yii::app()->user->name;?></a> 
                                    <?php echo "(Administrator)";?>
				</li> 
				<li><a href="<?php echo $this->createUrl('/user/logout')?>" title="Log Out">
                                        Log out
                                    </a>
				</li>
                            </ul>
                            <ul class="menu-user ">
                            <?php }?>
                                
					<?php if(!$isAdmin){?>
					<ul class="menu-user noAdminMenuUser">
						<li>
                                                    Logged in as 
                                                    <a href="<?php echo $this->createUrl('/user/userprofile')?>" title="<?php echo Yii::app()->user->name;?>"><?php echo Yii::app()->user->name;?></a>
						</li>
						<?php }?>

						<li><a href="<?php echo $this->createUrl('/user/userprofile')?>"
							title="My Profile" class="neato_tab_my_profile">My Profile</a></li>
						<?php if($isAdmin){ ?>
						<li><a href="<?php echo $this->createUrl('user/list')?>"
							title="List of all Users">Users</a>
						</li>
						<li><a href="<?php echo $this->createUrl('/robot/list')?>"
							title="List of all Robots">Robots</a>
						</li>
						<li><a href="<?php echo $this->createUrl('/usersRobot/list')?>"
							title="List of all User-Robot Associations">User-Robot
								Associations</a>
						</li>
						<li><a href="<?php echo $this->createUrl('/online/list')?>"
							title="List of all online User-Robot">Who's online</a>
						</li>
                                                <li><a href="<?php echo $this->createUrl('/notification/list')?>"
							title="Send Notifications">Notifications</a>
						</li>
                                                <li><a href="<?php echo $this->createUrl('/app/list')?>"
							title="List of all available app versions ">Version Control</a>
						</li>
                                            
						<?php }?>
						
						<li><a
							href="<?php echo $this->createUrl('/user/changepassword')?>"
							title="Change your password">Change Password</a></li>
                                                <?php if(!$isAdmin){?>
						<li><a href="<?php echo $this->createUrl('/user/logout')?>"
							title="Log Out">Log out</a>
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
						<?php if(!$isLoggedIn){?>
						<div class="button-div button-register neato-button:hover">
							<a class="neato-button neato-button-register"
								href="<?php echo $this->createUrl('/user/register')?>"
								title="Register">Register</a>
						</div>
						<div class="button-div button-login neato-button:hover">
							<a class="neato-button neato-button-login"
								href="<?php echo $this->createUrl('/user/login')?>"
								title="Login">Login</a>
						</div>
						<?php }?>
					</div>
			
			</div>
		</div>
		<!-- header -->
		<div class="page-body">
			<!-- mainmenu -->

			<?php //if(isset($this->breadcrumbs)):?>
			<?php
			//		$this->widget('zii.widgets.CBreadcrumbs', array(
			//			'links'=>$this->breadcrumbs,
			//		));
			?>
			<!-- breadcrumbs -->
			<?php //endif?>

			<?php echo $content; ?>

			<div class="clear"></div>
		</div>
		<div class="page-footer">
			<div class="">
				<ul class="menu menu-footer menu-hz menu-footer-default">
					<li class=" menu menu-item-privacy"><a
						href="<?php echo $this->createUrl('/site/privacy')?>"
						title="Privacy Policy">Privacy Policy</a>
					</li>
					<li class=" menu menu-item-terms"><a
						href="<?php echo $this->createUrl('/site/terms')?>" title="Terms">Terms</a>
					</li>
					<li class="menu menu-item-about"><a
						href="<?php echo $this->createUrl('/site/about_us')?>"
						title="About Us">About Us</a>
					</li>
				</ul>
				<div class="clearfloat float-alt copyright-notice">Copyright
					&#169;2012 Neato Robotics</div>
			</div>
		</div>
		<!-- footer -->

	</div>
	<!-- noty -->
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/jquery.noty.min.js"></script>
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.form-2.94.js"></script>
	<!-- layouts -->
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/layouts/bottomRight.js"></script>
	<!-- themes -->
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/noty/themes/default.js"></script>
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.qtip.min.js"></script>
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery.dataTables.min.js"></script>
	<!-- Jquery UI -->
	<script type="text/javascript"
		src="<?PHP echo Yii::app()->request->baseUrl; ?>/js/libs/jquery-ui-1.8.16.min.js"></script>
    <!-- button gradients with rounded corners -->
    <script  type="text/javascript">
    $(document).ready(function() {
	    $(this).find('.neato-button').wrap('<div class="rounded-corners"/>');
    }); 
    </script>
</body>
</html>
