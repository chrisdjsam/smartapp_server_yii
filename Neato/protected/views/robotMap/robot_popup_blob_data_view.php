<fieldset class='data-container'>
	<div class="qtip-blob-image-container">
		<img src="<?php echo($blob_data_url);?>" class="qtip-blob-image" />
	</div>
	<div class="qtip-blob-image-download-button">
		<a title="Download Map Data for ID <?php echo $map_id;?>" class="neato-button"
			href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('map'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-blob-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($map_id)))?>"
			target="_blank">Download</a>
	</div>
</fieldset>
