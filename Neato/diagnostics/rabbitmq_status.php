<?php
$rabbitmqStatus = shell_exec("sudo /etc/init.d/rabbitmq-server status");
$pos = strrpos($rabbitmqStatus, "Error");
if ($pos === false) { // note: three equal signs
	$statusMsg = "<span class='success'>RabbitMQ is running fine.</span>";
}else{
	$statusMsg = "<span  class='error'>RabbitMQ is NOT running fine.</span>";
	//    header("HTTP/1.0 404 Not Found");
	//    exit;
}
?>
<html>
<head>
<title>Neato Diagnostics Services</title>
<style>
.success {
	color: blue;
	font-weight: bold;
	font-size: 20px;
}

.error {
	color: red;
	font-weight: bold;
	font-size: 20px;
}

div.last-ran-at {
	color: black;
	font-size: 12px;
	font-style: italic;
}
</style>
<script type="text/javascript" src="/Neato_Server/Server_Yii/Neato/js/libs/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/Neato_Server/Server_Yii/Neato/js/libs/jquery-ui-1.8.16.min.js"></script>
</head>
<body>
	<h1>
		<a href="index.php">Neato Diagnostics Services</a>
	</h1>
	<div class="mq_status">
		<?php echo ($statusMsg); ?>
	</div>
	<div class="last-ran-at">
		Last checked at:
		<?php echo date("F j, Y, g:i a");?>
	</div>
	<input type="button" name="startbutton" value="Restart" onclick="restartRabbitMQ()" />
	<input type="button" name="stopbutton" value="Stop" onclick="stopRabbitMQ()" />
</body>
</html>
<script>
function restartRabbitMQ(){
    var startMQ = 'StartRabbitMQ';
    sendMQAjax(startMQ);
    
//    $('.mq_status').html(start_message);
}

function stopRabbitMQ(){
   var stopMQ = 'StopRabbitMQ';    
   sendMQAjax(stopMQ);
}

function sendMQAjax(action){
    var stop_message = "<span class='error'>RabbitMQ is NOT running fine.</span>";
    var start_message = "<span class='success'>RabbitMQ is running fine.</span>";
     $.ajax({
            type: 'POST',
            url: '/Neato_Server/Server_Yii/Neato/api/user/'+action,
            dataType: 'jsonp',
            success: function(r) {
                if(action == 'StopRabbitMQ'){
                $('.mq_status').html(stop_message);    
}
                if(action == 'StartRabbitMQ'){
                $('.mq_status').html(start_message);    
                }
            },
            error: function(r) {
            }
        });
   
}
</script>
