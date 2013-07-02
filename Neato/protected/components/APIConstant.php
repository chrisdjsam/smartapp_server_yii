<?php

class APIConstant {

    const AUTHENTICATION_FAILED = '-101';
    const PARAMETER_MISSING = '-102';
    const APP_DETAILS_NOT_FOUND = '-103';
    const UNSUPPORTED_ACCOUNT_TYPE = '-104';
    const EMAIL_NOT_VALID = '-105';
    const EMAIL_EXISTS = '-106';
    const SOCIAL_INFO_EXISTS = '-107';
    const UNAVAILABLE_JABBER_SERVICE = '-108';
    const PROFILE_KEY_VALUE_NOT_PROVIDED = '-109';
    const OLD_PASS_NOT_MATCH_EXISTING_PASS = '-110';
    const PASS_CONTAIN_ATLEAST_ONE_CHAR = '-111';
    const EMAIL_DOES_NOT_EXIST = '-112';
    const AUTH_TOKEN_AGAINST_EMAIL_DOES_NOT_EXIST = '-113';
    const SERIAL_NUMBER_DOES_NOT_EXIST = '-114';
    const ALTERNATE_EMAIL_DOES_NOT_EXIST = '-115';
    const CROSSED_RESEND_EMAIL_LIMIT = '-116';
    const EMAIL_ALREADY_ACTIVATED = '-117';
    const ROBOT_SERIAL_NUMBER_EXISTS = '-118';
    const USER_ID_NOT_FOUND = '-119';
    const USER_AND_ROBOT_ASSOCIATION_DOES_NOT_EXIST = '-120';
    const MESSAGE_SENDING_FAILED = '-121';
    const MESSAGE_TYPE_DOES_NOT_MATCH = '-122';
    const NO_USER_FOUND_FOR_ROBOT = '-123';
    const USER_PREFERENCE_NOT_VALID = '-124';
    const REGISTRATION_IDS_NOT_VALID = '-125';
    const JSON_OBJECT_NOT_VALID = '-126';
    const ROBOT_MAP_ID_DOES_NOT_EXIST = '-127';
    const MISSING_BOTH_DATA_VERSIONS = '-128';
    const DOES_NOT_MATCH_LATEST_XML_DATA_VERSION = '-129';
    const DOES_NOT_MATCH_LATEST_BLOB_DATA_VERSION = '-130';
    const ATLAS_ALREADY_ADDED = '-131';
    const ROBOT_ATLAS_ID_DOES_NOT_EXIST = '-132';
    const SCHEDULE_TYPE_NOT_VALID_OR_MISSING = '-133';
    const ROBOT_SCHEDULE_ID_DOES_NOT_EXIST = '-134';
    const SCHEDULE_DATA_NOT_FOUND = '-135';
    const GRID_IMAGE_EXISTS_FOR_ATLAS_ID_AND_GRID_ID = '-136';
    const GRID_IMAGE_DOES_NOT_EXIST_FOR_ATLAS_ID_AND_GRID_ID = '-137';
    const ROBOT_CUSTOM_ID_NOT_EXIST = '-138';
    const LATEST_BLOB_DATA_VERSION_DOES_NOT_MATCH_WITH_CUSTOM_ID_KEYS_AND_BLOB_DATA_VERSION = '-139';
    const CUSTOM_ID_AND_KEYS_AND_BLOB_DATA_VERSION_PROVIDED_BUT_KEYS_DOES_NOT_EXIST = '-140';
    const METHOD_CALL_NOT_IMPLEMENTED = '-141';
    const ROBOT_TYPE_NOT_VALID = '-142';
    const ALTERNATE_EMAIL_NOT_VALID = '-143';
    const PROBLEM_IN_SETTING_NEW_PASSWORD = '-144';
    const SOCIAL_ID_NOT_EXIST = '-145';
    const MISSING_USER_SOCIAL_ID_IN_GET_USER_AUTH_TOKEN_METHOD = '-146';
    const MISSING_PASSWORD = '-147';
    const MISSING_SOURCE_SERIAL_NUMBER_OR_SOURCE_SMARTAPP_ID = '-148';
    const SOURCE_SMARTAPP_ID_NOT_VALID = '-149';
    const SOURCE_SMARTAPP_ID_NOT_EXIST = '-150';
    const SOURCE_SMARTAPP_ID_IS_NOT_ASSOCIATED_WITH_ROBOT = '-151';
    const KEY_NOT_VALID = '-152';
    const CONFIGURATION_FAILED = '-153';
    const TOKEN_NOT_INVALID = '-154';
    const TOKEN_EXPIRED = '-155';
    const SLEEP_OR_WAKEUP_TIME_NOT_VALID = '-156';
    const UNSUPPORTED_FILE_TYPE = '-157';
    const USER_ATTRIBUTE_NOT_FOUND = '-158';
    const NO_SCHEDULE_DATA_FOUND = '-159';
    const INVALID_XML = '-160';
    const COULD_NOT_DELETE_ROBOT = '-161';
    const COULD_NOT_SET_USER_ATTRIBUTES = '-162';
    const GRID_ID_CONTAIN_ATLEAST_ONE_CHAR_OR_NUMBER = '-163';
    const BLOB_DATA_MISSING = '-164';
    const GRID_ID_MISSING = '-165';
    const ERROR_DELETING_GRID_IMAGE = '-166';
    const MISSING_XML_DATA = '-167';
    const APP_ID_SHOULD_BE_INTEGER = '-168';
    const APP_ID_ALREADY_EXIST = '-169';
    const UPGRADE_STATUS_MISSING = '-170';
    const ERROR_IN_ADDING_NEW_APP = '-171';
    const ERROR_UPDATING_APP_VERSION = '-172';
    const ERROR_DELETING_APP_VERSION = '-173';
    const API_KEY_MISSING_OR_INCORRECT = '-174';
    const ERROR_CODE_NOT_EXIST = '-175';

    public static $english = array(
        APIConstant::API_KEY_MISSING_OR_INCORRECT => 'Method call failed the API Authentication',
        APIConstant::APP_DETAILS_NOT_FOUND => 'App Id does not exist.',
        APIConstant::UNSUPPORTED_ACCOUNT_TYPE => 'Account Type is NOT supported.',
        APIConstant::PARAMETER_MISSING => 'Missing parameter in method call',
        APIConstant::EMAIL_NOT_VALID => 'The email address you provided does not appear to be a valid email address.',
        APIConstant::EMAIL_EXISTS => 'This email address has already been registered.',
        APIConstant::SOCIAL_INFO_EXISTS => 'This social information already exists.',
        APIConstant::UNAVAILABLE_JABBER_SERVICE => 'User could not be created because jabber service in not responding.',
        APIConstant::PROFILE_KEY_VALUE_NOT_PROVIDED => 'Invalid value for key',
        APIConstant::OLD_PASS_NOT_MATCH_EXISTING_PASS => 'Old password does not match with user password.',
        APIConstant::PASS_CONTAIN_ATLEAST_ONE_CHAR => 'Password should contain atleast one character.',
        APIConstant::EMAIL_DOES_NOT_EXIST => 'Email does not exist.',
        APIConstant::AUTH_TOKEN_AGAINST_EMAIL_DOES_NOT_EXIST => 'Provided auth token does not exist',
        APIConstant::SERIAL_NUMBER_DOES_NOT_EXIST => 'Serial number does not exist.',
        APIConstant::ALTERNATE_EMAIL_DOES_NOT_EXIST => 'The alternate email address you provided does not appear to be a valid email address.',
        APIConstant::CROSSED_RESEND_EMAIL_LIMIT => 'Sorry, You crossed resend validation email limit.',
        APIConstant::EMAIL_ALREADY_ACTIVATED => 'The email address you have provided is already activated.',
        APIConstant::ROBOT_SERIAL_NUMBER_EXISTS => 'This robot serial number already exists.',
        APIConstant::USER_ID_NOT_FOUND => 'User ID does not exist',
        APIConstant::USER_AND_ROBOT_ASSOCIATION_DOES_NOT_EXIST => 'User robot association does not exist.',
        APIConstant::MESSAGE_SENDING_FAILED => 'Message could not be sent to robot',
        APIConstant::MESSAGE_TYPE_DOES_NOT_MATCH => 'Does not match supported message type',
        APIConstant::REGISTRATION_IDS_NOT_VALID => 'Invalid registration IDs',
        APIConstant::NO_USER_FOUND_FOR_ROBOT => 'No user was found for robot',
        APIConstant::USER_PREFERENCE_NOT_VALID => 'User preference does not permit this operation.',
        APIConstant::JSON_OBJECT_NOT_VALID => 'The JSON Object you have provided does not appear to be a valid.',
        APIConstant::ROBOT_MAP_ID_DOES_NOT_EXIST => 'Robot map id does not exist.',
        APIConstant::MISSING_BOTH_DATA_VERSIONS => 'Provide at least one data version(xml or blob) or schedule type.',
        APIConstant::DOES_NOT_MATCH_LATEST_XML_DATA_VERSION => 'Version mismatch for xml data.',
        APIConstant::DOES_NOT_MATCH_LATEST_BLOB_DATA_VERSION => 'Version mismatch for blob data.',
        APIConstant::ATLAS_ALREADY_ADDED => 'Robot can have only one atlas',
        APIConstant::ROBOT_ATLAS_ID_DOES_NOT_EXIST => 'Robot atlas id does not exist.',
        APIConstant::SCHEDULE_TYPE_NOT_VALID_OR_MISSING => 'Robot schedule type is not valid.',
        APIConstant::ROBOT_SCHEDULE_ID_DOES_NOT_EXIST => 'Robot schedule id does not exist.',
        APIConstant::SCHEDULE_DATA_NOT_FOUND => 'Sorry,we did not find any schedule data for given robot serial number and schedule type',
        APIConstant::GRID_IMAGE_EXISTS_FOR_ATLAS_ID_AND_GRID_ID => 'Combination of atlas id and grid id exist. Try updating for same.',
        APIConstant::GRID_IMAGE_DOES_NOT_EXIST_FOR_ATLAS_ID_AND_GRID_ID => 'Combination of atlas id and grid id does not exist.',
        APIConstant::ROBOT_CUSTOM_ID_NOT_EXIST => 'Robot custom id does not exist.',
        APIConstant::LATEST_BLOB_DATA_VERSION_DOES_NOT_MATCH_WITH_CUSTOM_ID_KEYS_AND_BLOB_DATA_VERSION => 'Version mismatch for (key_name).',
        APIConstant::CUSTOM_ID_AND_KEYS_AND_BLOB_DATA_VERSION_PROVIDED_BUT_KEYS_DOES_NOT_EXIST => '(key_name) not found.',
        APIConstant::METHOD_CALL_NOT_IMPLEMENTED => 'Method call has not been implemented.',
        APIConstant::ROBOT_TYPE_NOT_VALID => 'Robot Type is not valid',
        APIConstant::ALTERNATE_EMAIL_NOT_VALID => 'The alternate email address you have provided does not appear to be a valid email address.',
        APIConstant::PROBLEM_IN_SETTING_NEW_PASSWORD => 'Error in generating new password.',
        APIConstant::SOCIAL_ID_NOT_EXIST => 'Social id does not exist. ',
        APIConstant::MISSING_USER_SOCIAL_ID_IN_GET_USER_AUTH_TOKEN_METHOD => 'Missing parameter user_social_id in method auth.get_user_auth_token',
        APIConstant::MISSING_PASSWORD => 'Missing parameter password in method auth.get_user_auth_token',
        APIConstant::MISSING_SOURCE_SERIAL_NUMBER_OR_SOURCE_SMARTAPP_ID => 'Missing parameter source serial number or source smartapp id in method call',
        APIConstant::SOURCE_SMARTAPP_ID_NOT_VALID => 'Please enter valid email id in the field source smartapp id',
        APIConstant::SOURCE_SMARTAPP_ID_NOT_EXIST => 'Sorry, Provided source_smartapp_id(email) does not exist in our system',
        APIConstant::SOURCE_SMARTAPP_ID_IS_NOT_ASSOCIATED_WITH_ROBOT => 'Sorry, Provided source_smartapp_id(email) is not associated with given robot',
        APIConstant::KEY_NOT_VALID => 'Sorry, entered key does not match with serial number.',
        APIConstant::CONFIGURATION_FAILED => 'Set robot configuration failed due to database problem',
        APIConstant::TOKEN_NOT_INVALID => 'Please enter valid token',
        APIConstant::TOKEN_EXPIRED => 'Sorry, provided token is expired',
        APIConstant::SLEEP_OR_WAKEUP_TIME_NOT_VALID => 'Please enter valid sleep time or wakeup time',
        APIConstant::UNSUPPORTED_FILE_TYPE => 'Unsupported file type',
        APIConstant::USER_ATTRIBUTE_NOT_FOUND => 'No attribute found for this user',
        APIConstant::NO_SCHEDULE_DATA_FOUND => 'No schedule data found for this robot',
        APIConstant::INVALID_XML => 'Invalid XML.',
        APIConstant::COULD_NOT_DELETE_ROBOT => 'Could not delete robot.',
        APIConstant::COULD_NOT_SET_USER_ATTRIBUTES => 'Could not set user attributed.',
        APIConstant::GRID_ID_CONTAIN_ATLEAST_ONE_CHAR_OR_NUMBER => 'id_grid should contain atleast one character or number .',
        APIConstant::BLOB_DATA_MISSING => 'Please Provide Blob data.',
        APIConstant::GRID_ID_MISSING => 'Please Provide Grid ID.',
        APIConstant::ERROR_DELETING_GRID_IMAGE => 'Error while deleting grid image',
        APIConstant::MISSING_XML_DATA => 'Please Provide XML data',
        APIConstant::APP_ID_SHOULD_BE_INTEGER => 'app_id should be an integer only.',
        APIConstant::APP_ID_ALREADY_EXIST => 'app_id already exist.',
        APIConstant::UPGRADE_STATUS_MISSING => 'Please mention upgrade status.',
        APIConstant::ERROR_IN_ADDING_NEW_APP => 'Problem adding new app version.',
        APIConstant::ERROR_UPDATING_APP_VERSION => 'Problem updating app version.',
        APIConstant::ERROR_DELETING_APP_VERSION => 'Problem deleting app version.',
        APIConstant::AUTHENTICATION_FAILED => 'User authentication failed',
        APIConstant::ERROR_CODE_NOT_EXIST => 'Provided error code does not exist.',
    );

    static function getMessageForErrorCode($errorCode) {

        if (isset(self::$english[$errorCode])) {
            return self::$english[$errorCode];
        } else {
            return $errorCode;
        }
    }

}

