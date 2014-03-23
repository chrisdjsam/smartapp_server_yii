<?php
$extension = "";
$encoded_data = "";

if(isset($_REQUEST['submit'])){
	if(isset($_FILES['file_data'])){
		$temp_file_path = $_FILES['file_data']['tmp_name'];
		$extension = pathinfo($_FILES['file_data']['name'], PATHINFO_EXTENSION);
		$original_content = file_get_contents($temp_file_path);

		//$str = 'This is an encoded string';
		$encoded_data = base64_encode($original_content);
	}
}

?>
<html>
<head>
<title>Base64 encoding tool</title>
<style type="text/css">
body {
	
}

.custom_table {
	width: 100%;
	border: 1px solid green;
}

.custom_table td.label_field {
	width: 25%;
}

.custom_table td.value_field {
	width: 75%;
}
</style>
</head>
<body>
	<br />
	<form action="robot_data_encode.php" method='POST' id='robotpostMap123' class='ajaxified_forms123' enctype="multipart/form-data">
		<table class='custom_table'>
			<tr>
				<td colspan="2">
					<label>Base 64 encoded data</label>
				</td>
			</tr>
			<tr>
				<td>File</td>
				<td>
					<input type="file" name='file_data'>
				</td>
			</tr>
			<tr>
				<td>Base 64 encoded data</td>
				<td>
					<textarea rows="5" cols="40" name='encoded_data'>
						<?php echo $encoded_data?>
					</textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="Submit" name='submit' dummy='robotpostMap' value='Submit' class='submit_form'>
				</td>
				<td></td>
			</tr>
		</table>
	</form>
</body>
</html>
