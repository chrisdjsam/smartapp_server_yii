<?php

/**
 * This class deals with all the site related operations.
 *
 */
class NotificationController extends Controller
{

    public function actionList(){
        if (Yii::app()->user->getIsGuest()) {
            $baseUrl = Yii::app()->baseUrl;
            $this->redirect($baseUrl.'/user/login');
        }
        if(Yii::app()->user->isAdmin){
            $this->render('list');
        }
        
    }
    
    public function actionNotificationHistory(){
        
        if (Yii::app()->user->getIsGuest()) {
            $baseUrl = Yii::app()->baseUrl;
            $this->redirect($baseUrl.'/user/login');
        }
        if(Yii::app()->user->isAdmin){
            $this->render('log');
        }
        
    }
    
    public function actionDownloadRequestResponse (){

        $ts = time();
        $stringData = '';
        
        $notification_log_id = Yii::app()->request->getParam('notification_log_id', '');
        
        $notification_log = NotificationLogs::model()->findByPk($notification_log_id);

        $notification_to_arr = unserialize($notification_log->notification_to);
        
        $notification_to_gcm = isset($notification_to_arr['gcm']) ? $notification_to_arr['gcm'] : array();
        $notification_to_ios = isset($notification_to_arr['ios']) ? $notification_to_arr['ios'] : array();
        
        //get all gcm registration ids
        $notification_to_gcm_ids = '';
        foreach ($notification_to_gcm as $value) {
            if ($notification_to_gcm_ids == '') {
                $notification_to_gcm_ids = $value;
            }else {
                $notification_to_gcm_ids .= ', ' . $value;
            }
        }
        
        //get all ios registration ids
        $notification_to_ios_ids = '';
        foreach ($notification_to_ios as $value) {
            if ($notification_to_ios_ids == '') {
                $notification_to_ios_ids = $value;
            }else {
                $notification_to_ios_ids .= ', ' . $value;
            }
        }

        //get gcm and ios request
        $combined_request_arr = unserialize($notification_log->request);
        $gcm_request = isset($combined_request_arr['gcm']) ? $combined_request_arr['gcm'] : array();
        $ios_request = isset($combined_request_arr['ios']) ? $combined_request_arr['ios'] : array();
        
        //get response
        $combined_response_arr = unserialize($notification_log->response);
        $gcm_response = isset($combined_response_arr['gcm']) ? $combined_response_arr['gcm'] : array() ;
        $ios_response = isset($combined_response_arr['ios']) ? $combined_response_arr['ios'] : array() ;

        $file_name = 'NotificationLog_' . $notification_log->id;

        $uploads_dir = Yii::app()->getBasePath(). DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . Yii::app()->params['notification-log-directory-name'] . DIRECTORY_SEPARATOR . $ts . DIRECTORY_SEPARATOR;

        $fullFilePath = "$uploads_dir" . $file_name . ".txt";
        
        if (!is_dir($uploads_dir)) {
            
            $old_umask = umask(0);
            mkdir($uploads_dir, 0777);
            umask($old_umask);
            
        }
        
        $fh = fopen($fullFilePath, 'w') or die("can't open file");
        
        
        $message_to_display = '';
        $message_to_display = @unserialize($notification_log->message);
        if ($message_to_display !== false) {
            $message_to_display = $message_to_display['message'];
        } else {
            $message_to_display = $notification_log->message;
        }

        $stringData .= "$message_to_display\n(#$notification_log->id)\n\n";
        $stringData .= "Sent at: $notification_log->created_on \n\n";
        
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
        $stringData .= "Notification Type: $notification_type \n\n";
        
        $stringData .= "Sent To ($notification_log->filter_criteria): \n\n";

        if ($notification_to_gcm) {
            $stringData .= "{" . str_replace(",", ",\n", $notification_to_gcm_ids) . "}\n";
        }

        if ($notification_to_ios) {
            $stringData .= "{" . str_replace(",", ",\n", $notification_to_ios_ids) . "}\n";
        }
        
        $stringData .= "\n---------------------------------------------\n\n";

        if ($gcm_request || $ios_request) {

            $stringData .= "Request Sent: \n\n";

            if ($gcm_request) {
                $stringData .= $gcm_request . "\n";
            }
            if ($ios_request) {
                $stringData .= $ios_request;
            }
            $stringData .= "\n---------------------------------------------\n\n";
        }

        if ($gcm_response || $ios_response) {

            $stringData .= "Response Received: \n\n";

            if ($gcm_response) {
                $stringData .= $gcm_response . "\n";
            }
            
            if ($ios_response) {
                $stringData .= $ios_response . "\n";
            }
            
            $stringData .= "\n---------------------------------------------\n\n";
        }

        fwrite($fh, $stringData);
        fclose($fh);

        AppHelper::download_file($fullFilePath, 'application/txt');
        exit();

        
    }

}