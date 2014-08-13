<?php
$cs = Yii::app()->getClientScript();
$cs->registerScript('app_base_url', 'var app_base_url = "' . Yii::app()->request->baseUrl . '";', CClientScript::POS_HEAD);

$this->pageTitle='Diagnostics Services - '.Yii::app()->name;
?>
<fieldset class='data-container static-data-container'>
	<legend>Diagnostics Services</legend>
	<p class="list_details">
		Ejabberd and RabbitMQ are critical pieces of the Neato SmartApp's Server end along with Apache and MySQL.
		<br />
		If you are seeing this page, it means Apache and MySQL are working fine.
		<br />
		Using this page, you can easily find out the status of Ejabberd and RabbitMQ services.
		<br />
		This page also displays count of online Ejabberd accounts and running RabbitMQ consumers.
		<br />
		If service is running, system would display status in green color otherwise it would display the status in red color.
		<br />
		This page also displays date and time at which status of these services was last checked.
	</p>
	<br />
	<br />
	<div class="diagnostics">

		<div class="diagnostics-box diagnostics-box-left hide-me">
			<?php
			exec(Yii::app()->params['ejabberdctl'] . " status", $output, $ejabberd_status);
			$ejabberdStatusMsg = "";
			$ejabberd_next_action = "";
			$ejabberd_connected_count_html = "";
			$ejabberd_connected_count_html = '<div class="status-block"> <strong>Online Accounts:</strong> 0</div>';
			if ($ejabberd_status == 0) {
				$ejabberdStatusMsg = "<span class='success'>Ejabberd is running fine.</span>";
				$ejabberd_next_action = '<div class="ejabberd-diagnostics-block diagnostics-block diagnostics-block-success-backgroud"><a href="#" ><b>Ejabberd</b></a></div>';
				exec(Yii::app()->params['ejabberdctl'] . " connected_users_number", $output, $status);
				$ejabberd_connected_count = isset($output[2]) ? $output[2] : '0';
				$ejabberd_connected_count_html = '<div class="status-block"> <strong>Online Accounts:</strong> ' . $ejabberd_connected_count . '</div>';
			}else{
				$ejabberdStatusMsg = "<span  class='error'>Ejabberd is not running.</span>";
				$ejabberd_next_action = '<div class="ejabberd-diagnostics-block diagnostics-block diagnostics-block-error-backgroud"><a href="#" ><b>Ejabberd</b></a></div>';
			}
			echo $ejabberd_next_action;
			?>
			<div class="status-box">
				<div class="ejabber_status status-block">
					<?php echo ($ejabberdStatusMsg); ?>
				</div>
				<div class="ejabberd-last-ran-at last-ran-at status-block">
					Last checked on:
					<?php echo date("F j, Y, g:i a");?>
				</div>
				<?php echo $ejabberd_connected_count_html; ?>
			</div>
		</div>

		<div class="diagnostics-box diagnostics-box-right hide-me">
			<?php
				exec(Yii::app()->params['rabbitmqctl'] . " status", $output, $rabbitmq_status);
				$rabbitMQStatusMsg = "";
				$rabbitmq_next_action = "";
				$running_consumers = "<span class='error'> No consumers are running </span>";
				if ($rabbitmq_status == 0) {
					$rabbitMQStatusMsg = "<span class='success'>RabbitMQ is running fine.</span>";
					$rabbitmq_next_action = '<div class="rabbitmq-diagnostics-block diagnostics-block diagnostics-block-success-backgroud"><a href="#" ><b>RabbitMQ</b></a></div>';

					exec(Yii::app()->params['rabbitmqctl'] . " list_consumers", $output, $status);

					$rabbitmqctl_list_queues = implode("|", $output);

					$notification_msgs = array();

					if(strpos($rabbitmqctl_list_queues, "smtp_notification_msgs") !== false){
						$notification_msgs['smtp_notification_msgs'] = "SMTP Consumer";
					}
					if(strpos($rabbitmqctl_list_queues, "push_notification_msgs") !== false){
						$notification_msgs['push_notification_msgs'] = "Push Consumer";
					}
					if(strpos($rabbitmqctl_list_queues, "xmpp_notification_msgs") !== false){
						$notification_msgs['xmpp_notification_msgs'] = "XMPP Consumer";
					}

					if(!empty($notification_msgs)){
						$running_consumers = '<div class="status-block">' .
											 	'<div class="running-consumers-box-a pull-left">' .
													'<strong>Running Consumers:</strong>' .
												'</div>' .
												'<div class="running-consumers-box-b pull-left">';
						foreach ($notification_msgs as $value) {
							$running_consumers .= $value . '<br />';
						}
						$running_consumers .= '</div></div>';
					}

				}else{
					$rabbitMQStatusMsg = "<span  class='error'>RabbitMQ is not running.</span>";
					$rabbitmq_next_action = '<div class="rabbitmq-diagnostics-block diagnostics-block diagnostics-block-error-backgroud"><a href="#" ><b>RabbitMQ</b></a></div>';
				}
				echo $rabbitmq_next_action;
			?>
			<div class="status-box">
				<div class="mq_status status-block">
					<?php echo ($rabbitMQStatusMsg); ?>
				</div>
				<div class="rabbitmq-last-ran-at last-ran-at status-block">
					Last checked on:
					<?php echo date("F j, Y, g:i a");?>
				</div>
				<?php echo $running_consumers; ?>
			</div>
		</div>

	</div>
</fieldset>

<script>

function init() {
	var diagnostics_box_left = $('.diagnostics-box-left').height();
	var diagnostics_box_right = $('.diagnostics-box-right').height();

	if (diagnostics_box_left != diagnostics_box_right && diagnostics_box_right > diagnostics_box_left) {
		$('.diagnostics-box-left').height(diagnostics_box_right);
	} else if (diagnostics_box_left != diagnostics_box_right && diagnostics_box_right < diagnostics_box_left){
		$('.diagnostics-box-right').height(diagnostics_box_left);
	}
	$('.diagnostics-box-left').show();
	$('.diagnostics-box-right').show();
}

$(document).ready(function(){
	init();
});

</script>
