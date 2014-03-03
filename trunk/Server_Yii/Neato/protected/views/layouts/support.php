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
        $userRole = Yii::app()->user->UserRoleId;
?>

	<div class="container page-default" >

		<div class="page-header " id ="header-color">
			<div class="inner">
				<?php
			if($isLoggedIn){?>
                            
				<?php if($isAdmin){?>
                            <ul class="adminMenuUser" >				
				<li>
                                    Logged in as 
                                    <b><?php echo Yii::app()->user->name;?></b> 
                                    <?php echo "(Support)";?>
				</li> 
				<li><a href="<?php echo $this->createUrl('/user/logout')?>" title="Log Out">
                                        Log out
                                    </a>
				</li>
                            </ul>
                            
                            <?php }?>
                            <ul class="menu-user ">    
					<?php if(!$isAdmin){?>
					<ul class="menu-user noAdminMenuUser">
						<li>
                                                    Logged in as 
                                                    <a href="<?php echo $this->createUrl('/user/userprofile')?>" title="<?php echo Yii::app()->user->name;?>"><?php echo Yii::app()->user->name;?></a>
						</li>
						<?php }?>
						
						<?php if($userRole != 2){ ?>
						<li><a href="<?php echo $this->createUrl('/user/userprofile')?>"
							title="My Profile" class="neato_tab_my_profile">My Profile</a></li>
						<?php } ?>							
						<?php if($isAdmin){ ?>
						<li><a href="<?php echo $this->createUrl('user/list')?>"
							title="<?php echo ($userRole == '2')?"User":"List of all Users"; ?>"><?php echo ($userRole == '2')?"Search User":"Search a user"; ?></a>
						</li>
						<li><a href="<?php echo $this->createUrl('/robot/list')?>"
							title="<?php echo ($userRole == '2')?"Robot":"List of all Robots"; ?>"><?php echo ($userRole == '2')?"Search Robot":"Search a robot"; ?></a>
						</li>
						<?php if($userRole != 2){ ?>
							<li><a href="<?php echo $this->createUrl('/usersRobot/list')?>"
								title="List of all User-Robot Associations">User-Robot
									Associations</a>
							</li>
						<?php }?>
						<li><a href="<?php echo $this->createUrl('/online/list')?>"
							title="List of all online User-Robot">Who is online?</a>
						</li>
						<?php if($userRole != '2'){ ?>
	                        <li><a href="<?php echo $this->createUrl('/notification/list')?>"
								title="Send Notifications">Notifications</a>
							</li>
							<li>
                            	<a href="<?php echo $this->createUrl('/robot/types')?>" title="Robot Types">Types</a>
							</li>                                            
                            <li>
                            	<a href="<?php echo $this->createUrl('/app/list')?>"
								title="List of all available app versions ">Version Control</a>
							</li>
						<?php } ?>
                                            
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
                                                    <a href="<?php echo $this->createUrl("/user/supportlogin")?>" title="Neato Robotics">
                                                        <?php echo CHtml::image(Yii::app()->request->baseUrl."/images/logo.png","Neato Robotics", array('class'=> 'app-logo')); ?>
                                                    </a>
                                                </div>
					</h1>
					<div class="top-buttons-container">
						<?php if(!$isLoggedIn){?>
							
						<?php }?>
					</div>
			</div>
		</div>
		<!-- header -->
		<div class="page-body " id="theme-color" >
                <!--<div class="page-body ">-->
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
		<div class="page-footer" id="footer-color">
			<div class="">
				<ul class="menu menu-footer menu-hz menu-footer-default">
					<li class=" menu menu-item-privacy"><a
						href="<?php echo $this->createUrl('/site/SupportPrivacy')?>"
						title="Privacy Policy">Privacy Policy</a>
					</li>
					<li class=" menu menu-item-terms"><a
						href="<?php echo $this->createUrl('/site/SupportTerms')?>" title="Terms">Terms</a>
					</li>
					<li class="menu menu-item-about"><a
						href="<?php echo $this->createUrl('/site/SupportAbout_us')?>"
						title="About Us">About Us</a>
					</li>
				</ul>
				<div class="clearfloat float-alt copyright-notice">Copyright &#169; <?php echo date('Y'); ?> <?php echo Yii::app()->name?></div>
					
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
