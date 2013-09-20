<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii-1.1.12.b600af/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';

require_once($yii);
Yii::createConsoleApplication($config)->run();

