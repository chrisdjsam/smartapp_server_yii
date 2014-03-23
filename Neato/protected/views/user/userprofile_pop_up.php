<?php
/* @var $this UserController */
/* @var $model User */

if (Yii::app()->user->id !==  $model->id){
	$this->pageTitle='Profile Details - ' . Yii::app()->name;
}else{
	$this->pageTitle='My Profile - ' . Yii::app()->name;
}

$this->breadcrumbs=array(
		'Users'=>array('index'),
		$model->name,
);
?>
<fieldset class='data-container static-data-container'>
	<?php
	$legend_message = "My Profile";
	if (Yii::app()->user->id !==  $model->id){
		$legend_message = 	"Profile details for $model->name";
	}
	?>
	<?php 
	$html_string = '';
	if ($model->doesRobotAssociationExist()){
		$is_first_robot = true;
		$html_string = '';
		foreach($model->usersRobots as $value){
			if(!$is_first_robot){
				$html_string .= ",&nbsp";
			}
			$is_first_robot = false;
			$html_string .= "<a title='View robot ".$value->idRobot->serial_number."' href='".$this->createUrl('robot/view',array('h'=>AppHelper::two_way_string_encrypt($value->idRobot->id)))."'>".$value->idRobot->serial_number."</a>";
		}
	}

	?>
	<?php  $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
					'email',
					'name',
					array(
							'label' =>'Asssociated Robots',
							'type'=>'raw',
							'value' => $html_string,
					),
					'chat_id',
					'chat_pwd',
			),
	));

	?>
</fieldset>
