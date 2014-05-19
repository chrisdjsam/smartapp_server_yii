<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle='Robot Information - ' . Yii::app()->name;

$this->breadcrumbs=array(
		'Robots'=>array('index'),
		$model->name,
);
?>
<fieldset
	class='data-container static-data-container'>
	<!-- 	<div class="action-button-container"> -->
	<!--<a href="<?php echo $this->createUrl('robot/delete',array('rid'=>AppHelper::two_way_string_encrypt($model->id)))?>" -->
	<!-- 				title="Delete robot" class="neato-button delete-single-item">Delete</a>  -->
	<!-- 	</div> -->
	<?php
	$map = '';
	$schedule = '';
	$html_string = '';

	if($model->doesMapExist()) {
		$map = 'Yes';
	}

	if($model->doesScheduleExist()) {
		$schedule = 'Yes';
	}

	if ($model->usersRobots){
		$is_first_user = true;
		$html_string = '';
		foreach($model->usersRobots as $value){
	 	if(!$is_first_user){
	 		$html_string .= ",&nbsp;";
	 	}
	 	$is_first_user = false;
	 	$html_string .= "<a title='View profile of ".$value->idUser->email."' href='".$this->createUrl('user/userprofile',array('h'=>AppHelper::two_way_string_encrypt($value->idUser->id)))."'>".$value->idUser->email."</a>";
	 }
	}
	?>
	<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
					'serial_number',
					'name',
					array(
							'label' =>'Asssociated Users',
							'type'=>'raw',
							'value' => $html_string,
					),
					'chat_id',
					array(
							'label' =>'Map',
							'type'=>'raw',
							'value' => $map,
					),
					array(
							'label' =>'Schedule',
							'type'=>'raw',
							'value' => $schedule,
					),
			),
	)); ?>
</fieldset>
