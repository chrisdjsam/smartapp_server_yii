<?php
/**
 * Default API view
 *
 * Renders JSON (or JSONP if passed a $callback) and outputs the json encoded
 * $content variable. Pretty straightforward.
 *
 * @package API
 */
header('Content-Type: text/javascript; charset=UTF-8');

if (!isset($callback)) {
	$callback = Yii::app()->request->getParam('callback','');
}

if ($callback != '') {
	echo $callback;
	echo '(';
	echo json_encode($content);
	echo ')';
} else {
	echo json_encode($content);
}
?>
