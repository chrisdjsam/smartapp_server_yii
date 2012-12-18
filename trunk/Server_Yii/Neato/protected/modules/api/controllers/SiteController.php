<?php

/**
 * The API SiteController is meant for app related API actions.
 */
class SiteController extends APIController {

	/*
	 * API to get api version
	*
	*Parameters:
	*<ul>
	*	<li><b>api_key</b> :Your API Key</li>
	*</ul>
	*Success Response:
	*<ul>
	*	<li>{"status":0,"result":"1"}</li>
	*</ul>
	*Failure Responses:
	*<ul>
	*	<li>If API Key is missing:
	*		<ul>
	*			<li>{"status":-1,"message":"Method call failed the API
	*				Authentication"}</li>
	*		</ul>
	*	</li>
	*</ul>
	*/
	public function actionApiVersion() {
		self::success(1);
	}
}
