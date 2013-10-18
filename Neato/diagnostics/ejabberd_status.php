<?php
$ejabberdStatus = shell_exec("sudo ejabberdctl status");
$pos = strrpos($ejabberdStatus, "Error");
$statusMsg = "";
if ($pos === false) { // note: three equal signs
     $statusMsg = "<span class='success'>Ejabberd is running fine.</span>";
}else{
     $statusMsg = "<span  class='error'>Ejabberd is NOT running fine.</span>";
//    header("HTTP/1.0 404 Not Found");
//    exit;
}
?>
<html>
<head>
<title>Neato Diagnostics Services</title>
<style>
.success{
	color:blue; font-weight:bold;font-size:20px; 
}
.error{
	color:red; font-weight:bold;font-size:20px; 
}
div.last-ran-at{
	color:black; font-size:12px; font-style:italic;
}
</style>
</head>
<body>
<h1>
<a href="index.php">Neato Diagnostics Services</a>

</h1>
<div>
<?php echo ($statusMsg); ?>
</div>
<div class="last-ran-at">Last ran at: <?php echo date("F j, Y, g:i a");?></div>
</body>
</html>