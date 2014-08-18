<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

$main_config = require(dirname(__FILE__).'/main.php');

unset($main_config['theme']);

return $main_config;


