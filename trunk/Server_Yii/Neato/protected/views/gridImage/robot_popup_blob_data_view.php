<fieldset class='data-container'>
	<div class="qtip-blob-image-container">
		<img src="<?php echo($blob_data_url);?>" class="qtip-blob-image" />
	</div>
	<div class="qtip-blob-image-download-button">
		<a title="Download Grid-Image Data " class="neato-button"
			href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('atlasGridImage'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-atlas-blob-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($grid_image_id), 'id_robot'=>AppHelper::two_way_string_encrypt($id_robot)))?>"
			target="_blank">Download</a>
	</div>
</fieldset>
