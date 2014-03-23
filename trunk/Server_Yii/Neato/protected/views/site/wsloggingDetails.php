<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='WSLogging Details - ' . Yii::app()->name;
?>
<fieldset class='data-container'>
	<legend>WSLogging Details</legend>
	<br />
	<table class="pretty-table wslogging-table">
		<thead>
			<tr>
				<th style="width: 5%;" title="ID">ID</th>
				<th style="width: 5%;" title="Site ID">Site ID</th>
				<th style="width: 14%;" title="Remote Address">Remote Address</th>
				<th style="width: 16%;" title="Method Name">Method Name</th>
				<th style="width: 22%;" title="Api Key">Api Key</th>
				<th style="width: 8%;" title="Response Type">Response</th>
				<th style="width: 6%;" title="Handler Name">Handle</th>
				<th style="width: 8%;" title="Request Type">Request</th>
				<th style="width: 6%;" title="Status">Status</th>
				<th style="width: 10%;" title="Date and Time">Date and Time</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($wslogging_data as $wslogging){?>
			<tr>
				<td>
					<a href="<?php echo $this->createUrl('site/viewlogging',array('h'=>AppHelper::two_way_string_encrypt($wslogging->id)))?>"
						title="View WsLogging Deatils of <?php echo($wslogging->method_name);?>">
						<?php echo($wslogging->id);?>
					</a>
				</td>
				<td>
					<?php echo($wslogging->id_site);?>
				</td>
				<td>
					<?php echo($wslogging->remote_address);?>
				</td>
				<td>
					<?php echo($wslogging->method_name);?>
				</td>
				<td>
					<?php echo($wslogging->api_key);?>
				</td>
				<td>
					<?php echo($wslogging->response_type);?>
				</td>
				<td>
					<?php echo($wslogging->handler_name);?>
				</td>
				<td>
					<?php echo($wslogging->request_type);?>
				</td>
				<td>
					<?php echo($wslogging->status);?>
				</td>
				<td>
					<?php echo($wslogging->date_and_time);?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</fieldset>
