<fieldset class='data-container'>
	<div class="qtip-xml-data-container">
		<pre class="qtip-xml-data">
    		<?php 
    		$xml_content = file_get_contents($xml_data_url);
    		$xml_content = trim($xml_content);
				if ($xml_content){?>
				<code class="language-xml">
				<?php echo htmlspecialchars($xml_content, ENT_QUOTES);?>
			</code>
			<?php }else{
				echo "Xml file has no content";
			} ?>
			
		</pre>
	</div>
	<?php if ($xml_content){?>
	<div class="qtip-xml-data-download-button">
		<a title="Download Xml Data for ID <?php echo $schedule_id;?>" class="neato-button"
			href="<?php echo $this->createUrl('robot/DownloadLatestFile',array('for'=>AppHelper::two_way_string_encrypt('schedule'), 'type'=>AppHelper::two_way_string_encrypt(Yii::app()->params['robot-schedule_xml-data-directory-name']), 'data_id'=>AppHelper::two_way_string_encrypt($schedule_id)))?>"
			target="_blank">Download</a>
	</div>
	<?php }?>
</fieldset>
