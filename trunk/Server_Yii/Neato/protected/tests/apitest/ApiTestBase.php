<?php
class ApiTestBase extends CDbTestCase
{
	/*
	 * Sends an api call to the server via curl
	*/
	public function sendApi($api, $fields){

		$baseURL = Yii::app()->params['apiProtocol']. Yii::app()->params['apiHostname']. "api/rest/json?method=";

		$fields['api_key'] = Yii::app()->params['apiKey'];
		$fields_string = http_build_query($fields);
		/**
		 * Initialize the cURL session
		*/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $baseURL.$api);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		/**
		 * Execute the cURL session
		*/
		$contents = curl_exec ($ch);

		/**
		 * Close cURL session
		*/
		curl_close ($ch);

		$contents = json_decode($contents);
		return $contents;
	}
}
