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
 <script type="text/javascript"
		src="/Neato_Server/Server_Yii/Neato/js/libs/jquery-1.7.2.min.js"></script>
<script type="text/javascript"
		src="/Neato_Server/Server_Yii/Neato/js/libs/jquery-ui-1.8.16.min.js"></script>
</head>
<body>
<h1>
<a href="index.php">Neato Diagnostics Services</a>

</h1>
<div class ="ejabber_status">
<?php echo ($statusMsg); ?>
</div>
<div class="last-ran-at">Last checked at: <?php echo date("F j, Y, g:i a");?></div>
<input type="button" name="startbutton" value="Restart" onclick ="restartEjabber()" />
<input type="button" name="stopbutton" value="Stop" onclick ="stopEjabber()" />
</body>
</html>

<script>
function restartEjabber(){
    
   var action = 'startEjabbered';
    sendEjabberAjax(action);
   
}

function stopEjabber(){
   var action = 'stopEjabbered';    
   sendEjabberAjax(action);
    
}
function sendEjabberAjax(action){

 $.ajax({
            type: 'POST',
            url: '/Neato_Server/Server_Yii/Neato/api/user/'+action,
            dataType: 'jsonp',
            success: function(r) {
                if(action == 'stopEjabbered'){
                    $('.ejabber_status').html("<span class='error'>Ejabberd is NOT running fine.</span>");
                }
                if(action == 'startEjabbered'){
                   $('.ejabber_status').html("<span class='success'>Ejabberd is running fine.</span>"); 
                }
                console.log(r);
            },
            error: function(r) {
            },
            complete: function(){

            }
        });
}
   
</script>

