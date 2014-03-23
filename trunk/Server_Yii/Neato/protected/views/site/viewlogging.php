<?php
/* @var $this SiteController */
/* @var $model WSLoggingDetails */

?>
<fieldset class='data-container'>
	<legend>WSLogging Details</legend>
	<br />
	<div class="logging-conents">
		<?php 
		echo "Request Data";
		echo '<pre>';
		print_r(unserialize($wslogging_model['request_data']));
		echo '</pre>';

		echo "Response Data";
		echo '<pre>';
		print_r(unserialize($wslogging_model['response_data']));
		echo '</pre>';
		?>
	</div>
</fieldset>
