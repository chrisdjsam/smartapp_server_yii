<?php

/**
 * Common helper functions used across Application
 */
class AppHelper {

	/**
	 * print_r a variable $a with pretty print and exit
	 * @param type $a
	 */
	public static function dump($a, $isJson = false) {
		if ($isJson) {
			print json_encode(print_r($a, true));
		} else {
			echo '<pre>';
			print_r($a);
			echo '</pre>';
		}
		exit;
	}

	/**
	 * Generate a random string
	 * @param int $length  size of string (default value 10)
	 * @return string generated random string
	 */
	public static function generateRandomString($length = 10) {
		$chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
		shuffle($chars);
		return implode(array_slice($chars, 0, $length));
	}

	/**
	 * Encrypt the provided string
	 * @param string $str
	 * @return string
	 */
	public static function one_way_encrypt($str){
		for($i = 0; $i < 5; $i++){
			$str = strrev(base64_encode($str)); // apply base64 first and then reverse the string
		}
		return $str;
	}

	/**
	 * Used to send email
	 * @param string $mail_to
	 * @param string $subject
	 * @param string $body
	 */
	public static function send_mail($mail_to, $subject, $body){
		$smtp_model = new SMTPViaMQ();
		$smtp_model->from = Yii::app()->params['adminEmail'];
		$smtp_model->to = $mail_to;
		$smtp_model->subject = $subject;
		$smtp_model->body = $body;
		$smtp_model->created_on = new CDbExpression('NOW()');
		if (!$smtp_model->save()) {
			error_log("+++++++++++++++++++++++++++", 0);
			error_log("Failed to save SMTP data", 0);
			error_log("+++++++++++++++++++++++++++", 0);
			Yii::app()->end();
		}
		$cmdParam = $smtp_model->id;
// 		$main_config = include_once '/var/www/Neato_Server/Server_Yii/Neato/amqp/smtp_notification_via_mq.php';
// 		send_smtp_notification_via_mq($cmdParam);
		$cmdStr = "php " . Yii::app()->params['amqp_email_publisher_path'];
		shell_exec($cmdStr . " '" . $cmdParam . "'");
	}

	/**
	 * This function is used to check for valid password
	 * @param string $password
	 * @return bool
	 */

	public static function is_valid_password($pass){
		if(strlen($pass)< 6){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * This function is used to check for valid email
	 * @param string $email
	 * @return bool
	 */
	public static function is_valid_email($email){
		if(Yii::app()->params['authenticate_via_email'] == false){
			return true;
		}else
		{
			if(preg_match("/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/",$email)){
				return true;
			}
			return false;
		}
	}

	/**
	 * @param string $email
	 * @return boolean
	 */
	public static function is_valid_email_for_all($email){

		if(preg_match("/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/",$email)){
			return true;
		}
		return false;

	}

	/**
	 * This function is used to encrypt the string of data
	 * @param string $value
	 * @return string
	 */
	public static  function two_way_string_encrypt($value){
		$skey= Yii::app()->params['two-way-encrypt-key'];
		if(!$value){
			return false;
		}
		$text = $value;
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
		return trim(self::safe_b64encode($crypttext));
	}

	/**
	 * This function is used to decrypt the string of data
	 * @param string $value
	 * @return string
	 */
	public static function two_way_string_decrypt($value){
		$skey= Yii::app()->params['two-way-encrypt-key'];
		if(!$value){
			return false;
		}
		$crypttext = self::safe_b64decode($value);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}

	/**
	 * This function is used to encrypt the string of data into base64 data
	 * @param string $value
	 * @return string
	 */
	public static function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	/**
	 * This function is used to decrypt the string of data into orginal data from base64 data
	 * @param string $value
	 * @return string
	 */
	public static function safe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	/**
	 * This function is used to delete a directory recursively
	 * @param strin $dir
	 * @return boolean
	 */
	public static function deleteDirectoryRecursively ($dir) {
		if (!file_exists($dir)) return true;
		if (!is_dir($dir)) return unlink($dir);
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!AppHelper::deleteDirectoryRecursively($dir.DIRECTORY_SEPARATOR.$item)) return false;
		}
		return rmdir($dir);
	}

	/**
	 * This function is used to delete a file
	 * @param strin $path_to_file
	 * @return boolean
	 */
	public static function deleteFile ($path_to_file) {
		if (file_exists($path_to_file))
			return unlink($path_to_file);
		return false;

	}


	/**
	 * This function is used to do curl call
	 * @param string $dir
	 * @param string $header
	 * @param string $data_string
	 * @return curl response - $response
	 */
	public static function curl_call($url, $headers, $data_string) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);

		curl_close($ch);

		return $response;

	}

	public static function strip_string($text, $length) {
		$length = abs((int) $length);
		if (strlen($text) > $length) {
			$text = $text . '...';
		}
		return($text);
	}

	public static function download_file($filename, $ctype) {

		// required for IE, otherwise Content-disposition is ignored
		if (ini_get('zlib.output_compression'))
			ini_set('zlib.output_compression', 'Off');

		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false); // required for certain browsers
		header("Content-Type: $ctype");

		// change, added quotes to allow spaces in filenames

		header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($filename));
		readfile("$filename");

	}

	public static function parseXMLToJSON ($url) {

		$fileContents = file_get_contents($url);

		$fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

		$fileContents = trim(str_replace('"', "'", $fileContents));

		$simpleXml = simplexml_load_string($fileContents);

		$json = json_encode($simpleXml);

		return $json;

	}

	public static function remote_file_exists($url){
		return(bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
	}

	public static function getTimeSummary ($time, $timeBase = false) {
		if (!$timeBase) {
			$timeBase = time();
		}

		if ($time <= time()) {
			$dif = $timeBase - $time;

			if ($dif < 60) {
				if ($dif < 2) {
					return "1 second ago";
				}

				return $dif." seconds ago";
			}

			if ($dif < 3600) {
				if (floor($dif / 60) < 2) {
					return "A minute ago";
				}

				return floor($dif / 60)." minutes ago";
			}

			if (date("d n Y", $timeBase) == date("d n Y", $time)) {
				return "Today, ".date("g:i A", $time);
			}

			if (date("n Y", $timeBase) == date("n Y", $time) && date("d", $timeBase) - date("d", $time) == 1) {
				return "Yesterday, ".date("g:i A", $time);
			}

			if (date("Y", $time) == date("Y", time())) {
				return date("F, jS g:i A", $time);
			}
		} else {
			$dif = $time - $timeBase;

			if ($dif < 60) {
				if ($dif < 2) {
					return "1 second";
				}

				return $dif." seconds";
			}

			if ($dif < 3600) {
				if (floor($dif / 60) < 2) {
					return "Less than a minute";
				}

				return floor($dif / 60)." minutes";
			}

			if (date("d n Y", ($timeBase + 86400)) == date("d n Y", ($time))) {
				return "Tomorrow, at ".date("g:i A", $time);
			}
		}

		return date("F, jS g:i A Y", $time);
	}

}
?>