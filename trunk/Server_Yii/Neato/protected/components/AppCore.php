<?php
/**
 * Common functions used across Neato Application
 */
class AppCore {

	/**
	 * Authenticate Api Key
	 * @return boolean
	 */

	public static function authenticate_api_key(){
		$response = false;
		$api_key = Yii::app()->request->getParam('api_key', false);
		if($api_key){
			$apiUserModel = ApiUser::model()->findByAttributes(array('api_key' => $api_key));
			if($apiUserModel != null){
				$response = true;
			}
		}
		return $response;
	}

	/**
	 * Authenticate user tokens
	 * @return boolean
	 */
	public static function authenticate_user_token(){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		if ($user_auth_token){
			$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token));
			if ($user_api_session != null) {
				$user_expire_time = $user_api_session->expires;
				$ts = time();
				if ($user_expire_time >= $ts){
					return true;
				}
				return false;
			}
		}
		return false;
	}

	/**
	 *
	 * Authenticate user tokens for provided user id
	 * @param int $user_id
	 * @return boolean
	 */
	public static function authenticate_user_token_with_user($user_id){
		$user_auth_token = Yii::app()->request->getParam('auth_token', '');
		if ($user_auth_token){
			$user_api_session = UsersApiSession::model()->findByAttributes(array('token' =>$user_auth_token, 'id_user' => $user_id));
			if ($user_api_session != null) {
				$user_expire_time = $user_api_session->expires;
				$ts = time();
				if ($user_expire_time >= $ts){
					$user_auth_tken_valid_till = Yii::app()->params['user-auth-token-valid-till'];
					$duration = $ts + 3600*24*$user_auth_tken_valid_till;
					$user_api_session->expires = $duration;
					$user_api_session->save();
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	/**
	 * Given a message key, returns an appropriately translated full-text string
	 *
	 * @param string $message_key The short message code
	 * @param array  $args        An array of arguments to pass through vsprintf().
	 * @param string $language    Optionally, the standard language code
	 *                            (defaults to site/user default, then English)
	 *
	 * @return string Either the translated string, the English string,
	 * or the original language string.
	 */
	public static function yii_echo($message_key, $args = array(), $language = "ln"){

		$english = array(
				/**
				 * Sites
		         */
				'item:site' => 'Sites',

				/**
				 * Sessions
		         */
				'login' => "Log In",
				'signuptext' => "<p>Create an account and register your Neato now.</p><p> We'll send you a FREE filter.</p>",
				'newusersignup' => "Register",
				'orconnectwith' => "Or connect with ",
				'loginok' => "You have been logged in.",
				'loginerror' => "We couldn't log you in. Please check your credentials and try again.",
				'login:empty' => "Email and password are required.",
				'login:baduser' => "Unable to load your user account.",
				'auth:nopams' => "Internal error. No user authentication method installed.",

				'logout' => "Log Out",
				'logoutok' => "You have been logged out.",
				'logouterror' => "We couldn't log you out. Please try again.",

				'loggedinrequired' => "You must be logged in to view that page.",
				'adminrequired' => "You must be an administrator to view that page.",
				'membershiprequired' => "You must be a member of this group to view that page.",

				/**
				 * Errors
		         */
				'exception:title' => "Fatal Error.",
				'exception:contact_admin' => 'An unrecoverable error has occurred and has been logged. Contact the site administrator with the following information:',

				'actionundefined' => "The requested action (%s) was not defined in the system.",
				'actionnotfound' => "The action file for %s was not found.",
				'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
				'actionunauthorized' => 'You are unauthorized to perform this action',

				'InstallationException:SiteNotInstalled' => 'Unable to handle this request. This site '
				. ' is not configured or the database is down.',
				'InstallationException:MissingLibrary' => 'Could not load %s',
				'InstallationException:CannotLoadSettings' => 'Yii could not load the settings file. It does not exist or there is a file permissions issue.',

				'SecurityException:Codeblock' => "Denied access to execute privileged code block",
				'DatabaseException:WrongCredentials' => "Yii couldn't connect to the database using the given credentials. Check the settings file.",
				'DatabaseException:NoConnect' => "Yii couldn't select the database '%s', please check that the database is created and you have access to it.",
				'SecurityException:FunctionDenied' => "Access to privileged function '%s' is denied.",
				'DatabaseException:DBSetupIssues' => "There were a number of issues: ",
				'DatabaseException:ScriptNotFound' => "Yii couldn't find the requested database script at %s.",
				'DatabaseException:InvalidQuery' => "Invalid query",
				'DatabaseException:InvalidDBLink' => "Connection to database was lost.",

				'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
				'InvalidParameterException:NonYiiObject' => "Passing a non-YiiObject to an YiiObject constructor!",
				'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",

				'InvalidClassException:NotValidYiiStar' => "GUID:%d is not a valid %s",

				'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is a misconfigured plugin. It has been disabled. Please search the Yii wiki for possible causes (http://docs.Yii.org/wiki/).",
				'PluginException:CannotStart' => '%s (guid: %s) cannot start and has been deactivated.  Reason: %s',
				'PluginException:InvalidID' => "%s is an invalid plugin ID.",
				'PluginException:InvalidPath' => "%s is an invalid plugin path.",
				'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
				'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
				'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
				'PluginException:NullInstantiated' => 'YiiPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',

				'YiiPlugin:MissingID' => 'Missing plugin ID (guid %s)',
				'YiiPlugin:NoPluginPackagePackage' => 'Missing YiiPluginPackage for plugin ID %s (guid %s)',

				'YiiPluginPackage:InvalidPlugin:MissingFile' => 'The required file "%s" is missing.',
				'YiiPluginPackage:InvalidPlugin:InvalidDependency' => 'Its manifest contains an invalid dependency type "%s".',
				'YiiPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
				'YiiPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',

				'YiiPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:CannotRegisterLanguages' => 'Cannot register languages for plugin %s (guid: %s) at %s.',
				'YiiPlugin:Exception:NoID' => 'No ID for plugin guid %s!',

				'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
				'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
				'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",

				'YiiPlugin:Dependencies:Requires' => 'Requires',
				'YiiPlugin:Dependencies:Suggests' => 'Suggests',
				'YiiPlugin:Dependencies:Conflicts' => 'Conflicts',
				'YiiPlugin:Dependencies:Conflicted' => 'Conflicted',
				'YiiPlugin:Dependencies:Provides' => 'Provides',
				'YiiPlugin:Dependencies:Priority' => 'Priority',

				'YiiPlugin:Dependencies:Yii' => 'Yii version',
				'YiiPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
				'YiiPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
				'YiiPlugin:Dependencies:Plugin' => 'Plugin: %s',
				'YiiPlugin:Dependencies:Priority:After' => 'After %s',
				'YiiPlugin:Dependencies:Priority:Before' => 'Before %s',
				'YiiPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
				'YiiPlugin:Dependencies:Suggests:Unsatisfied' => 'Missing',

				'YiiPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',

				'InvalidParameterException:NonYiiUser' => "Passing a non-YiiUser to an YiiUser constructor!",

				'InvalidParameterException:NonYiiSite' => "Passing a non-YiiSite to an YiiSite constructor!",

				'InvalidParameterException:NonYiiGroup' => "Passing a non-YiiGroup to an YiiGroup constructor!",

				'IOException:UnableToSaveNew' => "Unable to save new %s",

				'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
				'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",

				'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
				'IOException:NotDirectory' => "%s is not a directory.",

				'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
				'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
				'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",

				'ClassException:ClassnameNotClass' => "%s is not a %s.",
				'ClassNotFoundException:MissingClass' => "Class '%s' was not found, missing plugin?",
				'InstallationException:TypeNotSupported' => "Type %s is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.",

				'ImportException:ImportFailed' => "Could not import element %d",
				'ImportException:ProblemSaving' => "There was a problem saving %s",
				'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",

				'ImportException:GUIDNotFound' => "Entity '%d' could not be found.",
				'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",

				'ExportException:NoSuchEntity' => "No such entity GUID:%d",

				'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.",
				'ImportException:NotAllImported' => "Not all elements were imported.",

				'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
				'InvalidParameterException:MissingOwner' => "File %s (file guid:%d) (owner guid:%d) is missing an owner!",
				'IOException:CouldNotMake' => "Could not make %s",
				'IOException:MissingFileName' => "You must specify a name before opening a file.",
				'ClassNotFoundException:NotFoundNotSavedWithFile' => "Unable to load filestore class %s for file %u",
				'NotificationException:NoNotificationMethod' => "No notification method specified.",
				'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
				'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
				'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
				'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",

				'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
				'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
				'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
				'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
				'DatabaseException:NoACL' => "No access control was provided on query",

				'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",

				'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
				'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
				'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
				'InvalidParameterException:NoDataFound' => "Could not find any data.",
				'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
				'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
				'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
				'InvalidParameterException:LibraryNotRegistered' => '%s is not a registered library',
				'InvalidParameterException:LibraryNotFound' => 'Could not load the %s library from %s',

				'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.",
				'ConfigurationException:NoSiteID' => "No site ID has been specified.",
				'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
				'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
				'SecurityException:ForwardFailedToRedirect' => 'Redirect could not be issued due to headers already being sent. Halting execution for security. Search http://docs.Yii.org/ for more information.',
				'InvalidParameterException:APIMethodOrFunctionNotSet' => "Method or function not set in call in expose_method()",
				'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structure is incorrect for call to expose method '%s'",
				'InvalidParameterException:UnrecognisedHttpMethod' => "Unrecognised http method %s for api method '%s'",
				'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
				'APIException:ParameterNotArray' => "%s does not appear to be an array.",
				'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
				'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
				'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
				'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
				'APIException:APIAuthenticationFailed' => "Method call failed the API Authentication",
				'APIException:UserAuthenticationFailed' => "Method call failed the User Authentication",
				'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
				'CallException:InvalidCallMethod' => "%s must be called using '%s'",
				'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
				'APIException:FunctionDoesNotExist' => "Function for method '%s' is not callable",
				'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
				'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
				'APIException:NotGetOrPost' => "Request method must be GET or POST",
				'APIException:MissingAPIKey' => "Missing API key",
				'APIException:BadAPIKey' => "Bad API key",
				'APIException:MissingHmac' => "Missing X-Yii-hmac header",
				'APIException:MissingHmacAlgo' => "Missing X-Yii-hmac-algo header",
				'APIException:MissingTime' => "Missing X-Yii-time header",
				'APIException:MissingNonce' => "Missing X-Yii-nonce header",
				'APIException:TemporalDrift' => "X-Yii-time is too far in the past or future. Epoch fail.",
				'APIException:NoQueryString' => "No data on the query string",
				'APIException:MissingPOSTHash' => "Missing X-Yii-posthash header",
				'APIException:MissingPOSTAlgo' => "Missing X-Yii-posthash_algo header",
				'APIException:MissingContentType' => "Missing content type for post data",
				'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
				'SecurityException:DupePacket' => "Packet signature already seen.",
				'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
				'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",

				'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
				'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
				'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",

				'PluginException:NoPluginName' => "The plugin name could not be found",

				'SecurityException:authenticationfailed' => "User could not be authenticated",

				'CronException:unknownperiod' => '%s is not a recognised period.',

				'SecurityException:deletedisablecurrentsite' => 'You can not delete or disable the site you are currently viewing!',

				'RegistrationException:EmptyPassword' => 'The password fields cannot be empty',
				'RegistrationException:PasswordMismatch' => 'Passwords must match',
				'Robot is dead' => 'Robot is dead',
				'Robot is alive' => 'Robot is alive',
				'LoginException:BannedUser' => 'You have been banned from this site and cannot log in',
				'LoginException:UsernameFailure' => 'We could not log you in. Please check your email and password.',
				'LoginException:PasswordFailure' => 'We could not log you in. Please check your email and password.',
				'LoginException:AccountLocked' => 'Your account has been locked for too many log in failures.',
				'LoginException:ChangePasswordFailure' => 'Failed current password check.',

				'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Yii',

				'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
				'viewfailure' => 'There was an internal failure in the view %s',
				'changebookmark' => 'Please change your bookmark for this page',
				'noaccess' => 'You need to login to view this content or the content has been removed or you do not have permission to view it.',
				'error:missing_data' => 'There was some data missing in your request',

				'error:default' => 'Oops...something went wrong.',
				'error:404' => 'Sorry. We could not find the page that you requested.',

				/**
				 * API
		         */
				'system.api.list' => "List all available API calls on the system.",
				'auth.gettoken' => "This API call lets a user obtain a user authentication token which can be used for authenticating future API calls. Pass it as the parameter auth_token",

				/**
				 * User details
		         */
				'name' => "Name",
				'email' => "Email",
				'username' => "Username",
				'loginusername' => "Email",
				'password' => "Password",
				'passwordagain' => "Confirm Password",
				'admin_option' => "Make this user an admin?",

				/**
				 * Access
		         */
				'PRIVATE' => "Private",
				'LOGGED_IN' => "Logged in users",
				'PUBLIC' => "Public",
				'access:friends:label' => "Friends",
				'access' => "Access",
				'access:limited:label' => "Limited",
				'access:help' => "The access level",

				/**
				 * robot
		         */
				'addrobot:ok' => "You have successfully added a robot having serial number: <b> %s </b>.",
				'addrobotexist:ok' => "The Serial number <b> %s </b> is already registered.",
				'editrobot:ok' => "You have successfully updated a robot having serial number: <b> %s </b>.",
				'editrobotexist:ok' => "The Serial number <b> %s </b> is already registered.",
				'deleterobot:ok' => "You have successfully deleted a robot.",

				/**
				 * robot type
		         */
				'addrobot:type:ok' => "You have successfully added the robot type : <b> %s </b>.",
				'editrobot:type:ok' => "You have successfully updated the robot type : <b> %s </b>.",

				/**
				 * user
		         */
				'registeruser:ok' => "<b> %s </b> you have successfully registered.",
				'adduser:ok' => "You have successfully added a user named <b> %s </b>.",
				'edituser:ok' => "You have successfully updated a user named <b> %s </b>.",
				'deleteuser:ok' => "You have successfully deleted a user <b> %s </b>.",

				/**
				 *  Forgot password email
		         */
				'forgotpassword_subject' => Yii::app()->name . " forgot password",
				'forgotpassword_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to " . Yii::app()->name . "</b></div>

				<br>Hi %s,<br><br>

				Your email id is : %s<br><br>
				Your password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Regards,

				<br><br>

				<b>Team " . Yii::app()->name . "</b>

				</div>

				</body>

				</html>",

				/**
				 *  Reset/Change password email
		         */
				'change_password_subject' => Yii::app()->name . " change password",
				'resetpassword_subject' => Yii::app()->name . " reset password",
				'changepassword_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to " . Yii::app()->name . "</b></div>

				<br>Hi %s,<br><br>

				Your email id is : %s<br><br>
				Your new password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Regards,

				<br><br>

				<b>Team " . Yii::app()->name . "</b>

				</div>

				</body>

				</html>",

				/**
				 *  email validation template
		         */
				'validate_email_subject' => Yii::app()->name . " validate email",
				'validate_email_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to " . Yii::app()->name . "</b></div>

				<br>Hi %s,<br><br>

				Thank you for registration. Please validate your primary email %s by clicking on following link.<br><br>
				%s<br><br>

				<br><br>

				Regards,

				<br><br>

				<b>Team " . Yii::app()->name . "</b>

				</div>

				</body>

				</html>",

				/**
				 *  New user email
		         */
				'welocome_subject' => "Welcome to " . Yii::app()->name,
				'welcome_message' => "<html><body>

				<div style='border: 1px solid #E3E3E3; padding:10px;'>

				<div style='width: auto; background-color: #3BB9FF; height: 20px; color: #ffffff; padding-left:10px; padding-top: 5px;'>
				<b>Welcome to " . Yii::app()->name . "</b></div>

				<br>Hi %s,<br><br>
				Thanks for registering with " . Yii::app()->name . ".<br><br>

				Your email id is : %s<br><br>
				Your password is : %s<br><br>
				You can login  by clicking <a href='%s'>here</a>

				<br><br>

				Regards,

				<br><br>

				<b>Team " . Yii::app()->name . "</b>

				</div>

				</body>

				</html>",

		);

		if (isset($english[$message_key])) {
			$string = $english[$message_key];
		} else {
			$string = $message_key;
		}

		// only pass through if we have arguments to allow backward compatibility
		// with manual sprintf() calls.
		if ($args) {
			$string = vsprintf($string, $args);
		}

		return $string;
	}

	/**
	 *
	 * Store API call deatils
	 * @param  int $status
	 * @param array() $result
	 * @param array() $request_type
	 */
	public static function ws_log_details($status = 1, $result = array(), $request_type = 'post'){
		try{
			//check for enable web service logging flag
			if(Yii::app()->params['enablewebservicelogging']){
				$remote_address = isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : 'not found';
				//$_REQUEST;
				if(!isset($_REQUEST['method'])){
					return false;
				}
				$method_name = $_REQUEST['method'];
				$api_key = $_REQUEST['api_key'];
				$response_type = $_REQUEST['response_type'];
				$handler_name = $_REQUEST['handler'];

				//unset extra data
				$request_data = $_REQUEST;
				unset($request_data['method']);
				unset($request_data['api_key']);
				unset($request_data['request']);
				unset($request_data['handler']);

				$serialized_request_data = serialize($request_data);
				$serialized_response_data = serialize($result);
				//start of code to store all $_server info
				$can_log_server = 1;
				if($can_log_server){
					$serialized_request_and_server_data['request'] = $_REQUEST;
					$serialized_request_and_server_data['server'] = $_SERVER;
					$serialized_request_data = serialize($serialized_request_and_server_data);
				}
				//end of code to store all $_server info

				$site_id = UserCore::get_site_id();
				$api_user_model = ApiUser::model()->findByAttributes(array('api_key'=>$api_key));
				if(!is_null($api_user_model)){
					$site_id = $api_user_model->id_site;
				}

				$start_time = Yii::app()->params['start_time'];
				$end_time = round(microtime(true) * 1000);

				$response_time = $end_time - $start_time;

				$ws_logging_model = new WsLogging();
				$ws_logging_model->id_site = $site_id;
				$ws_logging_model->remote_address = $remote_address;
				$ws_logging_model->method_name = $method_name;
				$ws_logging_model->api_key = $api_key;
				$ws_logging_model->response_type = $response_type;
				$ws_logging_model->handler_name = $handler_name;
				$ws_logging_model->request_type = $request_type;
				$ws_logging_model->request_data = $serialized_request_data;
				$ws_logging_model->response_data = $serialized_response_data;
				$ws_logging_model->status = $status;
				$ws_logging_model->response_time = $response_time;

				$ws_logging_model->save();

			}
		} catch (Exception $e) {
			//do nothing
		}
	}

	/**
	 * Check for robot schedule xml data.
	 * @param string $xml_data
	 * @return boolean
	 */
	public static function validate_schedule_xml_data($xml_data){
		return true;
	}

	/**
	 * Check for robot schedule blob data.
	 * @param string $blob_data
	 * @return boolean
	 */
	public static function validate_schedule_blob_data($blob_data){
		return true;
	}

	public static function dataTableOperation($aColumns, $sIndexColumn, $sTable, $sGet, $modelName, $sWhere = "", $join_flag = false) {

		/**
		 * Define $sWhereOriginal to fetch Total data count
		 */
		$sWhereOriginal = $sWhere;

		/**
		 * Paging
		 */
		$sLimit = "";
		if (isset($sGet['iDisplayStart']) && $sGet['iDisplayLength'] != '-1') {
			$sLimit = "LIMIT " . intval($sGet['iDisplayStart']) . ", " .
					intval($sGet['iDisplayLength']);
		}

		/**
		 * Ordering
		 */
		$sOrder = "";
		if (isset($sGet['iSortCol_0'])) {
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($sGet['iSortingCols']); $i++) {
				if ($sGet['bSortable_' . intval($sGet['iSortCol_' . $i])] == "true") {
					$sOrder .= $aColumns[intval($sGet['iSortCol_' . $i])] . "
					" . ($sGet['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY") {
				$sOrder = "";
			}
		}

		/**
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		//        $sWhere = "";
		if (isset($sGet['sSearch']) && $sGet['sSearch'] != "") {

			if(!empty($sWhere)){
				$sWhere .= ' AND (';
			} else {
				$sWhere = "WHERE (";
			}

			for ($i = 0; $i < count($aColumns); $i++) {

				if (isset($sGet['bSearchable_' . $i]) && $sGet['bSearchable_' . $i] == "true") {

					if(Yii::app()->user->UserRoleId == '2'){
						$sWhere .= $aColumns[$i] . " = '" . mysql_real_escape_string($sGet['sSearch']) . "' OR ";
					}else {
						$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($sGet['sSearch']) . "%' OR ";
					}
				}
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}

		/**
		 * Individual column filtering
		 */
		for ($i = 0; $i < count($aColumns); $i++) {
			if (isset($sGet['bSearchable_' . $i]) && $sGet['bSearchable_' . $i] == "true" && $sGet['sSearch_' . $i] != '') {
				if ($sWhere == "") {
					$sWhere = "WHERE ";
				} else {
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($sGet['sSearch_' . $i]) . "%' ";
			}
		}

		/**
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";

		if($join_flag){
			$rResult = Yii::app()->db->createCommand($sQuery)->queryAll();
		} else {
			$rResult = $modelName::model()->findAllBySql($sQuery);
		}

		/* Data set length after filtering */
		$sQuery = "
		SELECT FOUND_ROWS()
		";

		$rResultFilterTotal = Yii::app()->db->createCommand($sQuery)->queryAll();
		$iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];

		/* Total data set length */
		$sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
		$sWhereOriginal
		";

		$rResultTotal = Yii::app()->db->createCommand($sQuery)->queryAll();
		$iTotal = $rResultTotal[0]['COUNT(' . $sIndexColumn . ')'];

		/**
		 * Output
		 */
		$output = array(
				'sEcho' => intval($sGet['sEcho']),
				'iTotalRecords' => $iTotal,
				'iTotalDisplayRecords' => $iFilteredTotal,
				'rResult' => $rResult
		);

		return($output);
	}


	public static function send_notification_to_given_registration_ids($registration_ids, $message_to_send) {

		$response = self::scanGivenRegistrationIds($registration_ids);

		if($response['code'] == 1){
			return $response;
		}

		$registration_ids_all = Array();
		$registration_ids_all['gcm'] = $response['registration_ids_by_type']['gcm'];
		$registration_ids_all['ios'] = $response['registration_ids_by_type']['ios'];

		$result = self::send_notification($registration_ids_all, $message_to_send, null);

		return $result;

	}

	public static function send_notification_to_all_users_of_robot($user_ids_to_send_notification, $message_to_send, $send_from) {

		$AppCoreObj = new AppCore();
		$response = $AppCoreObj->fetchRegistrationIdsForGivenUserIds($user_ids_to_send_notification);

		if($response['code'] == 1){
			return $response;
		}

		$registration_ids_all = Array();
		$registration_ids_all['gcm'] = isset($response['output']['gcm']) ? $response['output']['gcm'] : array();
		$registration_ids_all['ios'] = isset($response['output']['ios']) ? $response['output']['ios'] : array();

		$result = self::send_notification($registration_ids_all, $message_to_send, $send_from);

		if(isset($response['extra'])){
			return array('code' => 0, 'output' => "Notification Response :: " . $result['output'] . " and Unable to send notification to users " . $response['extra'] . " Because they are not registered");
		}

		return $result;

	}

	public function fetchRegistrationIdsForGivenUserIds($user_ids_to_send_notification) {

		$unregistered_user_ids = Array();
		$registration_ids = Array();

		foreach ($user_ids_to_send_notification as $user_id) {

			$notification_registrations = NotificationRegistrations::model()->findAll('user_id = :user_id', array(':user_id' => $user_id));

			if (!empty($notification_registrations)) {
				foreach ($notification_registrations as $notificationRegistration) {
					if ($notificationRegistration->is_active == 'Y') {

						if($notificationRegistration->device_type == '1'){
							$registration_ids['gcm'][] = $notificationRegistration->registration_id;
						}
						else if($notificationRegistration->device_type == '2') {
							$registration_ids['ios'][] = $notificationRegistration->registration_id;
						}

					} else {
						$unregistered_user_ids[] = $user_id;
					}
				}
			} else {
				$unregistered_user_ids[] = $user_id;
			}

		}

		if(empty($registration_ids)) {
			return array('code' => 1, 'output' => 'Sorry, there is not single user who is registered for notification');
		}

		if(!empty($unregistered_user_ids)){
			$unregistered_users_name = '';
			foreach ($unregistered_user_ids as $user_id) {
				$data = User::model()->findByPk($user_id);
				if(empty($unregistered_users_name)){
					$unregistered_users_name = $data->name;
				} else {
					$unregistered_users_name .= ', ' . $data->name;
				}
			}

			return array('code' => 0, 'output' => $registration_ids, 'extra' => $unregistered_users_name);
		}

		return array('code' => 0, 'output' => $registration_ids);

	}

	public static function send_notification_to_all_users_of_robot2($user_ids_to_send_notification, $message_description, $send_from) {

		$user_ids_not_found_error = array();
		$user_registration_ids_not_found = array();
		$result = array();

		foreach ($message_description as $id => $description) {

			$user_ids_to_send_notification_by_preference = array();

			foreach ($user_ids_to_send_notification as $user_id) {
				$user_push_notification_preferences = UserPushNotificationPreferences::model()->find('user_id = :user_id and push_notification_types_id = :push_notification_types_id', array(':user_id' => $user_id, ':push_notification_types_id' => $id));
				if(!empty($user_push_notification_preferences)){
					if($user_push_notification_preferences->preference == 1){
						$user_ids_to_send_notification_by_preference[] = $user_id;
					}
				}
			}

			if(empty($user_ids_to_send_notification_by_preference)){
				$user_ids_not_found_error[$id] = array('code' => 1, 'output' => "Sorry, Server didn't find a single user who has set push notification preference as true.");
				continue;
			}

			$AppCoreObj = new AppCore();
			$response = $AppCoreObj->fetchRegistrationIdsForGivenUserIds($user_ids_to_send_notification_by_preference);

			if($response['code'] == 1){
				$user_registration_ids_not_found[$id] = $response;
				continue;
			}

			$registration_ids_all = Array();
			$registration_ids_all['gcm'] = isset($response['output']['gcm']) ? $response['output']['gcm'] : array();
			$registration_ids_all['ios'] = isset($response['output']['ios']) ? $response['output']['ios'] : array();

			$result[$id] = self::send_notification($registration_ids_all, $description, $send_from);

		}

		return array('code' => 0);

	}

	public static function send_notification_to_given_emails($emails, $message_to_send) {
		$response = self::scanGivenEmails($emails);

		if($response['code'] == 1){
			return $response;
		}

		$registration_ids_all = Array();
		$registration_ids_all['gcm'] = isset($response['output']['gcm']) ? $response['output']['gcm'] : array();
		$registration_ids_all['ios'] = isset($response['output']['ios']) ? $response['output']['ios'] : array();

		$result = self::send_notification($registration_ids_all, $message_to_send, null);

		return array('code' => 0, 'output' => 'Notification sent to registration ids : ' . json_encode($response['output']) );

	}

	// check whether given emails are present in database or not and return respective array of registration ids
	public static function scanGivenEmails($emails) {

		$causing_emails = Array();
		$registration_ids = Array();
		$unregistered_emails = Array();
		$unregistered_emails_is_active = Array();

		foreach ($emails as $email) {
			$unregistered_reg_flag = false;
			$user_id = User::model()->findByAttributes(array('email' => $email));
			if(!empty($user_id)){

				if(!empty($user_id->notificationRegistrations)){
					foreach ($user_id->notificationRegistrations as $notificationRegistration) {
						if($notificationRegistration->is_active == 'Y') {
							if($notificationRegistration->device_type == '1'){
								$registration_ids['gcm'][] = $notificationRegistration->registration_id;
							}
							else if($notificationRegistration->device_type == '2') {
								$registration_ids['ios'][] = $notificationRegistration->registration_id;
							}

						} else {
							$unregistered_reg_flag = true;
						}
					}
				} else {
					$unregistered_emails[] = $email;
				}

			} else{
				$causing_emails[] = $email;
			}

			if($unregistered_reg_flag) {
				$unregistered_emails_is_active[] = $email;
			}
		}

		if(!empty($causing_emails)) {

			return array('code' => 1, 'output' => 'Provided emails addresses are not exist in our system ( Causing Emails : ' . json_encode($causing_emails) . ' )');

		}

		if(!empty($unregistered_emails)) {

			return array('code' => 1, 'output' => 'Please register notification for given emails ( Causing Emails : ' . json_encode($unregistered_emails) . ' )');

		}

		if(empty($registration_ids)){
			return array('code' => 1, 'output' => 'Please register notification for given emails ( Causing Emails : ' . json_encode($unregistered_emails_is_active) . ' )');
		} else {
			if(!empty($unregistered_emails_is_active)){
				return array('code' => 0, 'output' => $registration_ids, 'extra' => json_encode($unregistered_emails_is_active) );
			}
		}

		return array('code' => 0, 'output' => $registration_ids);

	}

	// check whether given registration ids are present in database or not
	public static function scanGivenRegistrationIds($registration_ids) {

		$causing_registration_ids = Array();
		$registration_ids_by_type = Array();
		$registration_ids_by_type['gcm'] = Array();
		$registration_ids_by_type['ios'] = Array();

		foreach ($registration_ids as $value) {

			$data = NotificationRegistrations::model()->findByAttributes(array('registration_id' => $value));

			if (empty($data)) {
				$causing_registration_ids[] = $value;
			} else if ($data->is_active == 'N') {
				$causing_registration_ids[] = $value;
			}else {
				if($data->device_type == '1'){
					$registration_ids_by_type['gcm'][] = $value;
				} else if($data->device_type == '2') {
					$registration_ids_by_type['ios'][] = $value;
				}
			}

		}

		if(!empty($causing_registration_ids)) {

			return array('code' => 1, 'output' => 'Provided Registration Ids are not registered, please register it first then try again... (Causing Registration Ids : ' . json_encode($causing_registration_ids) . ')');

		}

		return array('code' => 0, 'output' => 'Provided Registration Ids are scaned Successfully', 'registration_ids_by_type' => $registration_ids_by_type);

	}

	public static function send_notification($registration_ids_all, $message_to_send, $send_from = Array(), $filter_criteria = 'Selected Devices') {

		$gcm_result = '';
		$ios_result = '';
		$result = '';
		$notification_log_id = '';

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$message_body = array(
				'message' => $message_to_send,
				'time' => $utc
		);

		if(!empty($send_from)){
			if($send_from['type'] == 'robot'){
				$message_body['robotId'] =$send_from['data'];
			}
		}

		$notification_details = PushNotificationTypes::model()->find('description = :description', array(':description' => $message_to_send));
		if(!empty($notification_details)){
			$message_body['notificationId'] =$notification_details->id;
		}

		$log_result = self::log_notification_request($registration_ids_all, $message_body, $filter_criteria, $send_from);

		if($log_result['code'] == 1){
			return $log_result;
		} else {
			$notification_log_id = $log_result['output'];
		}

		$cmdParam = Yii::app()->params['env'] . "|" . $notification_log_id . "|NPN";
		$cmdStr = "php " . Yii::app()->params['neato_amqp_publisher_path'];
		shell_exec($cmdStr . " '" . $cmdParam . "'");

		return array('code' => 0, 'output' => '');
	}

	public static function log_notification_request($registration_ids_all, $message_to_send, $filter_criteria, $send_from) {

		$notification_to = Array();
		$combined_request = Array();
		$combined_request_gcm = '';
		$combined_request_ios = '';

		$registration_ids_all_gcm = $registration_ids_all['gcm'];
		$registration_ids_all_ios = $registration_ids_all['ios'];

		$registration_all_gcm_type['gcm_type'] = array();
		$registration_all_ios_type['ios_type'] = array();

		foreach($registration_ids_all_gcm as $value){

			$registration_gcm_data = NotificationRegistrations::model()->find('registration_id = :registration_id', array(':registration_id' => $value));
			$registration_all_gcm_type['gcm_type'][] = $registration_gcm_data->notification_server_type;

		}

		foreach($registration_ids_all_ios as $value){

			$registration_ios_data = NotificationRegistrations::model()->find('registration_id = :registration_id', array(':registration_id' => $value));
			$registration_all_ios_type['ios_type'][] = $registration_ios_data->notification_server_type;
		}

		if ( !empty($registration_ids_all_gcm) ) {

			// Replace with real BROWSER API key from Google APIs
			$apiKey = $apiKey = Yii::app()->params['gcm_api_key'];

			$url_gcm = 'https://android.googleapis.com/gcm/send';
			$headers_gcm = array(
					'Authorization: key=' . $apiKey,
					'Content-Type: application/json'
			);
			$data_string_gcm = json_encode(array(
					'registration_ids' => $registration_ids_all_gcm,
					'data' => $message_to_send
			));

			$combined_request_gcm = '{URL: ' . $url_gcm . ', HTTP Header: ' . json_encode($headers_gcm) . ', POST Data: ' . $data_string_gcm . '}';
		}

		if(!empty($registration_ids_all_ios)){

			$fp = 'stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, stream_context_create())';

			$loc_key = array();
			$loc_key[101] = 'MSG_STUCK_NOTIFICATION_ID';
			$loc_key[102] = 'MSG_DIRT_BAG_FULL_NOTIFICATION_ID';
			$loc_key[103] = 'MSG_CLEANING_DONE_NOTIFICATION_ID';
			$loc_key[212] = 'MSG_UI_ALERT_PLUG_CABLE_NOTIFICATION_ID';
			$loc_key[22000] = 'MSG_UI_ERR_CANCEL_NOTIFICATION_ID';
			$loc_key[20219] = 'MSG_UI_ERR_DUST_BIN_MISSING_NOTIFICATION_ID';

			$loc_key_value = isset($message_to_send['notificationId'])? $loc_key[$message_to_send['notificationId']] : 'MSG_GENERIC_NOTIFICATION_ID';

			// Create the payload body
			$body['aps'] = array(
					'alert'=> array(
							'loc-key'=>$loc_key_value,
							'action-loc-key'=>"VIEW"));

			$message_body_ios = array();
			if(isset($message_to_send['robotId'])){
				$message_body_ios['robotId'] = $message_to_send['robotId'];
			}
			if(isset($message_to_send['notificationId'])){
				$message_body_ios['id'] = $message_to_send['notificationId'];
			}
			$message_body_ios['message'] = $message_to_send['message'];
			$message_body_ios['time'] = $message_to_send['time'];

			$body['raw_data'] = $message_body_ios;

			// Encode the payload as JSON
			$payload = json_encode($body);

			foreach ($registration_ids_all_ios as $reg_id) {

				// Build the binary notification
				$msg = "chr(0) . pack('n', 32) . pack('H*', $reg_id) . pack('n', " . strlen($payload) . ") . $payload";

				// Send it to the server
				$combined_request_ios .= 'fwrite(' . $fp . ', ' . PHP_EOL . $msg . ', ' . PHP_EOL . strlen($msg) . ')' . PHP_EOL;

			}

		}

		$combined_request['gcm'] = $combined_request_gcm;
		$combined_request['ios'] = $combined_request_ios;
		$combined_request_str = serialize($combined_request);

		$notification_to['gcm'] = $registration_ids_all_gcm;
		$notification_to['ios'] = $registration_ids_all_ios;
		$notification_to['gcm_type'] = $registration_all_gcm_type['gcm_type'];
		$notification_to['ios_type'] = $registration_all_ios_type['ios_type'];

		$notification_to_str = serialize($notification_to);

		$data = new NotificationLogs();

		$data->message = serialize($message_to_send);
		$data->action = 'I';
		$data->filter_criteria = $filter_criteria;
		$data->notification_to = $notification_to_str;
		$data->request = $combined_request_str;
		$data->send_from = serialize($send_from);

		if (!$data->save()) {
			return array('code' => 1, 'output' => $data->errors);
		}

		return array('code' => 0, 'output' => $data->id);
	}

	public static function log_notification_response($combined_response = '', $notification_log_id = '') {

		$combined_response_str = serialize($combined_response);
		$current_time = date('Y-m-d H:i:s');

		$data = NotificationLogs::model()->findByPk($notification_log_id);

		$data->response = $combined_response_str;
		$data->action = 'C';
		$data->updated_on = $current_time;

		if (!$data->save()) {
			return array('code' => 1, 'output' => $data->errors);
		}

	}

	public static function store_registration_id($user_id, $registration_id, $device_type, $application_id, $notification_server_type) {

		$data = NotificationRegistrations::model()->findByAttributes(array('registration_id' => $registration_id));

		if (empty($data)) {

			$model = new NotificationRegistrations();

			$model->user_id = $user_id;
			$model->registration_id = $registration_id;
			$model->device_type = $device_type;
			$model->application_id = $application_id;
			$model->notification_server_type = $notification_server_type;

			if (!$model->save()) {
				return $model->errors;
			}

			return 'Registered successfully';
		} else {

			$model = $data;

			$model->user_id = $user_id;
			$model->registration_id = $registration_id;
			$model->device_type = $device_type;
			$model->application_id = $application_id;
			$model->notification_server_type = $notification_server_type;
			$model->is_active = 'Y';
			if (!$model->save()) {
				return $model->errors;
			}

			return 'Notification registration details updated successfully';
		}
	}

	public static function remove_registration_id($registration_id) {

		$data = NotificationRegistrations::model()->findByAttributes(array('registration_id' => $registration_id));

		if (empty($data)) {
			return array('code' => 1, 'output' => 'not_found');
		} else if ($data->is_active == 'Y') {
			$data_is_active = 'N';
		} else if ($data->is_active == 'N') {
			$data_is_active = 'Y';
		}

		$model = $data;

		$model->is_active = $data_is_active;

		if (!$model->save()) {
			return array('code' => 1, 'output' => $model->errors);
		}

		return array('code' => 0, 'output' => 'Unregistered successfully');

	}

	public static function fetch_notification_log_by_id($notification_log_id) {

		$displayed_str_length = 400;

		$notification_log = NotificationLogs::model()->findByPk($notification_log_id);

		// all gcm notification ids
		$notification_to_arr = unserialize($notification_log->notification_to);
		$notification_to_gcm = isset($notification_to_arr['gcm']) ? $notification_to_arr['gcm'] : array();
		$notification_to_ios = isset($notification_to_arr['ios']) ? $notification_to_arr['ios'] : array();

		$notification_to_gcm_ids = '';
		foreach ($notification_to_gcm as $value) {

			if ($notification_to_gcm_ids == '') {
				$notification_to_gcm_ids = $value;
			} else {
				$notification_to_gcm_ids .= ', ' . $value;
			}
		}
		$notification_to_gcm_ids = AppHelper::strip_string($notification_to_gcm_ids, $displayed_str_length);

		$notification_to_ios_ids = '';
		foreach ($notification_to_ios as $value) {

			if ($notification_to_ios_ids == '') {
				$notification_to_ios_ids = $value;
			} else {
				$notification_to_ios_ids .= ', ' . $value;
			}
		}
		$notification_to_ios_ids = AppHelper::strip_string($notification_to_ios_ids, $displayed_str_length);

		//get request
		$combined_request_arr = unserialize($notification_log->request);
		$gcm_request = isset($combined_request_arr['gcm']) ? $combined_request_arr['gcm'] : array();
		$ios_request = isset($combined_request_arr['ios']) ? $combined_request_arr['ios'] : array();

		//get response
		$combined_response_arr = unserialize($notification_log->response);
		$gcm_response = isset($combined_response_arr['gcm']) ? $combined_response_arr['gcm'] : array() ;
		$ios_response = isset($combined_response_arr['ios']) ? $combined_response_arr['ios'] : array() ;

		$message_to_display = '';
		$message_to_display = @unserialize($notification_log->message);
		if ($message_to_display !== false) {
			$message_to_display = $message_to_display['message'];
		} else {
			$message_to_display = $notification_log->message;
		}

		$display_notification_details = '';

		$display_notification_details .= "<div class='device-entry'>";
		$display_notification_details .= "<div class='label-value'>" . $message_to_display . "</div>";
		$display_notification_details .= "<div class='label-value'>(#" .$notification_log->id . ")</div>";
		$display_notification_details .= "</div>";

		$display_notification_details .= "<div class='device-entry'>";
		$display_notification_details .= '<label>Sent at: </label>';
		$display_notification_details .= "<span class='label-value'>" . $notification_log->created_on . "</span>";
		$display_notification_details .= "</div>";

		$notification_type = $notification_log->notification_type;
		switch ($notification_type) {
			case '1':
				$notification_type = 'System' ;
				break;

			case '2':
				$notification_type = 'Activities' ;
				break;

			case '3':
				$notification_type = 'SOS' ;
				break;

			default:
				$notification_type = 'System' ;
				break;
		}
		$display_notification_details .= "<div class='device-entry'>";
		$display_notification_details .= '<label>Notification Type: </label>';
		$display_notification_details .= "<span class='label-value'>" . $notification_type . "</span>";
		$display_notification_details .= "</div>";

		$display_notification_details .= "<div class='device-entry'>";
		$display_notification_details .= '<label>Sent To (' . $notification_log->filter_criteria . '):</label>';
		$display_notification_details .= "<div class='label-value_notification_history'>";
		if ($notification_to_gcm) {
			$display_notification_details .= "<div class='log_request_response gcm_text_style'><i>" . str_replace(",", ",<br>", $notification_to_gcm_ids) . "</i></div>";
		}
		if ($notification_to_ios) {
			$display_notification_details .= "<div class='log_request_response gcm_text_style'><i>" . str_replace(",", ",<br>", $notification_to_ios_ids) . "</i></div>";
		}
		$display_notification_details .= "</div>";
		$display_notification_details .= "</div>";

		if ($gcm_request || $ios_request) {
			$display_notification_details .= "<div class='device-entry'>";
			$display_notification_details .= '<label>Request Sent:</label>';
			$display_notification_details .= "<div class='label-value_notification_history'>";
			if ($gcm_request) {
				$gcm_request_str = AppHelper::strip_string($gcm_request, 250);
				$display_notification_details .= "<span class='gcm_text_style'><i>" . $gcm_request_str . "</i></span><br/>";
			}
			if($ios_request){
				$ios_request_str = AppHelper::strip_string($ios_request, 250);
				$display_notification_details .= "<span class='gcm_text_style'><i>" . $ios_request_str . "</i></span><br/>";
			}
			$display_notification_details .= "</div>";
			$display_notification_details .= "</div>";
		}

		if ($gcm_response || $ios_response) {
			$display_notification_details .= "<div class='device-entry'>";
			$display_notification_details .= '<label>Response Received:</label>';
			$display_notification_details .= "<div class='label-value_notification_history'>";
			if ($gcm_response) {
				$gcm_response_str = AppHelper::strip_string($gcm_response, $displayed_str_length);
				$display_notification_details .= "<span class='gcm_text_style'><i>" . $gcm_response_str . "</i></span><br/>";
			}
			if ($ios_response) {
				$ios_response_str = AppHelper::strip_string($ios_response, $displayed_str_length);
				$display_notification_details .= "<span class='gcm_text_style'><i>" . $ios_response_str . "</i></span><br/>";
			}
			$display_notification_details .= "</div>";
			$display_notification_details .= "</div>";
		}

		$display_notification_details .= "<form id='form_notification_download' action='" . Yii::app()->baseUrl . "/notification/downloadRequestResponse' method='POST'>";
		$display_notification_details .= "<input type='hidden' name= 'notification_log_id' value='" . $notification_log->id . "'>";
		$display_notification_details .= "<div class='device-entry href_download_file'>";
		$display_notification_details .= "<div id='notification_download' class='neato-button_alt' style='width: 135px;'>Download Log</div>";
		$display_notification_details .= "</div>";
		$display_notification_details .= "</form>";
		return array('code' => 0, 'output' => $display_notification_details);

	}

	public static function sendIOSPushNotification($deviceToken, $message_body){

		$iOSCertificatesPath = Yii::app()->basePath. DIRECTORY_SEPARATOR. 'certificates'.DIRECTORY_SEPARATOR.'neato.pem';

		// Put your private key's passphrase here:
		$passphrase = 'neato123';

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $iOSCertificatesPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
		$fp = stream_socket_client(
				'ssl://gateway.sandbox.push.apple.com:2195', $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp){
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		}

		$loc_key = array();
		$loc_key[101] = 'MSG_STUCK_NOTIFICATION_ID';
		$loc_key[102] = 'MSG_DIRT_BAG_FULL_NOTIFICATION_ID';
		$loc_key[103] = 'MSG_CLEANING_DONE_NOTIFICATION_ID';
		$loc_key[212] = 'MSG_UI_ALERT_PLUG_CABLE_NOTIFICATION_ID';
		$loc_key[22000] = 'MSG_UI_ERR_CANCEL_NOTIFICATION_ID';
		$loc_key[20219] = 'MSG_UI_ERR_DUST_BIN_MISSING_NOTIFICATION_ID';

		$loc_key_value = isset($message_body['notificationId'])? $loc_key[$message_body['notificationId']] : 'MSG_GENERIC_NOTIFICATION_ID';

		// Create the payload body
		$body['aps'] = array(
				'alert'=> array(
						'loc-key'=>$loc_key_value,
						'action-loc-key'=>"VIEW"));

		$message_body_ios = array();
		if(isset($message_body['robotId'])){
			$message_body_ios['robotId'] = $message_body['robotId'];
		}
		if(isset($message_body['notificationId'])){
			$message_body_ios['id'] = $message_body['notificationId'];
		}
		$message_body_ios['message'] = $message_body['message'];
		$message_body_ios['time'] = $message_body['time'];

		$body['raw_data'] = $message_body_ios;

		// Encode the payload as JSON
		$payload = json_encode($body);

		$result = '';

		foreach ($deviceToken as $reg_id) {

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $reg_id) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result .= fwrite($fp, $msg, strlen($msg)) . ' ';

		}

		// Close the connection to the server
		fclose($fp);

		return $result;
	}

	public static function getVirtuallyOnlinRobots($serial_number, $robot_ping_interval) {

		$data = RobotCore::getLatestPingTimestampFromRobot($serial_number);

		if (!empty($data)) {
			$latest_ping_timestamp = strtotime($data[0]->ping_timestamp);

			$current_system_timestamp = time();
			$time_diff = ($current_system_timestamp - $latest_ping_timestamp);

			if ($time_diff < $robot_ping_interval) {
				return true;
			}
		}
		return false;
	}

	public static function setRobotKeyValue($key_value){
		$command_key = ($key_value['key']);
		$command_value = ($key_value['value']);
		$robot = $key_value['robot'];

		$utc_str = gmdate("M d Y H:i:s", time());
		$utc = strtotime($utc_str);

		$data = RobotKeyValues::model()->find('_key = :_key AND robot_id = :robot_id', array(':_key' => $command_key,':robot_id' => $robot->id));

		if(!empty($data)){
			$data->value = $command_value;
			$data->timestamp = $utc;
			$data->update();
		} else {
			$robot_key_value = new RobotKeyValues();
			$robot_key_value->robot_id = $robot->id;
			$robot_key_value->_key = $command_key;
			$robot_key_value->value = $command_value;
			$robot_key_value->timestamp = $utc;
			$robot_key_value->save();
		}

		$user_id = Yii::app()->user->id;
		$user_data = User::model()->findByPk($user_id);

		$cause_agent_id = Yii::app()->session['cause_agent_id'];

		$xmpp_message_model = new XmppMessageLogs();
		$xmpp_message_model->save();
		$message = '<?xml version="1.0" encoding="UTF-8"?><packet><header><version>1</version><signature>0xcafebabe</signature></header><payload><request><command>5001</command><requestId>' . $xmpp_message_model->id . '</requestId><timeStamp>' . $utc . '</timeStamp><retryCount>0</retryCount><responseNeeded>false</responseNeeded><distributionMode>2</distributionMode><params><robotId>' . $robot->serial_number . '</robotId><causeAgentId>' . $cause_agent_id . '</causeAgentId></params></request></payload></packet>';

		$xmpp_message_model->send_from = $robot->id;
		$xmpp_message_model->send_at = $utc;

		$xmpp_message_model->xmpp_message = $message;
		$xmpp_message_model->save();

		$online_users_chat_ids = RobotCore::getOnlineUsers();
		RobotCore::send_chat_message($user_data->chat_id, $robot->chat_id , $message);
		foreach ($robot->usersRobots as $userRobot){
			if(in_array($userRobot->idUser->chat_id, $online_users_chat_ids)){
				RobotCore::send_chat_message($user_data->chat_id, $userRobot->idUser->chat_id, $message);
			}
		}

	}

	public static function remainingTimeStamp($robot){

		$robot_user_association_tokens = RobotUserAssociationTokens::model()->findAll('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if(!empty($robot_user_association_tokens)){
			foreach ($robot_user_association_tokens as $token){
				$user_token = $token->token;
				$token_timestamp = $token->created_on;
			}

			$token_timestamp = strtotime($token_timestamp);

			$expiry_time = (strtotime('+5 minutes', $token_timestamp));

			$current_system_timestamp = time();
			$reamaining_timestamp = $current_system_timestamp - $expiry_time;

			return $reamaining_timestamp;
		}
	}

	public static function createdTimeStamp($robot){

		$robot_user_association_tokens = RobotUserAssociationTokens::model()->findAll('robot_id = :robot_id', array(':robot_id' => $robot->id));

		if(!empty($robot_user_association_tokens)){

			foreach ($robot_user_association_tokens as $token){
				$user_token = $token->token;
				$token_timestamp = $token->created_on;
			}

			$token_timestamp = strtotime($token_timestamp);
			$expiry_time = (strtotime('+5 minutes', $token_timestamp));

			return $expiry_time;
		}
	}

	public static function removeExpiredLinkingCodeUsingCronJob(){

		$robot_linking_data = RobotLinkingCode::model()->findAll();

		foreach ($robot_linking_data as $value) {

			if(!RobotCore::isLinkingCodeValid($value->linking_code_created_on)){
				RobotUserAssociationTokens::model()->deleteAll('token = :token', array(':token' => $value->linking_code));
				$value->delete();
			}

		}

	}

	public static function removeLinkingCodeByLinkingData($robot_linking_data){
		foreach ($robot_linking_data as $value) {
			RobotUserAssociationTokens::model()->deleteAll('token = :token', array(':token' => $value->linking_code));
			$value->delete();
		}
	}

}

?>