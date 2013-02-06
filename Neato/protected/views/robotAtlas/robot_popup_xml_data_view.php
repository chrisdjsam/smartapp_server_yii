<fieldset class='data-container'>
	<div class="qtip-xml-data-container">

		<pre class="qtip-xml-data">
    		
				<?php 
				$xml_content = file_get_contents($xml_data_url);
				$xml_content = trim($xml_content);
				if ($xml_content){?>
				<code class="language-xml" id='xml_container'>
				<?php echo htmlspecialchars($xml_content, ENT_QUOTES);?>
			</code>
			<?php }else{
				echo "Xml file has no content";
			} ?>
			
			</pre>
	</div>
	<?php if ($xml_content){?>
	<div class="qtip-xml-data-download-button">
		<a title="Download Xml Data for ID <?php echo $atlas_id;?>"
			class="neato-button"
			href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('atlas'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-xml-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($atlas_id), 'id_robot'=>AppHelper::two_way_string_encrypt($id_robot)))?>"
			target="_blank">Download</a>
	</div>
	<?php }?>

</fieldset>
