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
        
        $notification_to_gcm = $notification_to_arr['gcm'];

        //get all gcm registration ids
        $notification_to_gcm_ids = '';
        foreach ($notification_to_gcm as $value) {
            if ($notification_to_gcm_ids == '') {
                $notification_to_gcm_ids = $value;
            }else {
                $notification_to_gcm_ids .= ', ' . $value;
            }
        }

        //get gcm request
        $combined_request_arr = unserialize($notification_log->request);
        $gcm_request = $combined_request_arr['gcm'];
        
        //get gcm response
        $combined_response_arr = unserialize($notification_log->response);
        $gcm_response = $combined_response_arr['gcm'];

        $file_name = 'NotificationLog_' . $notification_log->id;

        $uploads_dir = Yii::app()->getBasePath(). DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR . Yii::app()->params['notification-log-directory-name'] . DIRECTORY_SEPARATOR . $ts . DIRECTORY_SEPARATOR;

        $fullFilePath = "$uploads_dir" . $file_name . ".txt";
        
        if (!is_dir($uploads_dir)) {
            
            $old_umask = umask(0);
            mkdir($uploads_dir, 0777);
            umask($old_umask);
            
        }
        
        $fh = fopen($fullFilePath, 'w') or die("can't open file");

        $stringData .= "$notification_log->message\n(#$notification_log->id)\n\n";
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

        $stringData .= "\n---------------------------------------------\n\n";

        if ($gcm_request ) {

            $stringData .= "Request Sent: \n\n";

            if ($gcm_request) {
                $stringData .= $gcm_request . "\n";
            }
            $stringData .= "\n---------------------------------------------\n\n";
        }

        if ($gcm_response ) {

            $stringData .= "Response Received: \n\n";

            if ($gcm_response) {
                $stringData .= $gcm_response . "\n";
            }
            $stringData .= "\n---------------------------------------------\n\n";
        }

        fwrite($fh, $stringData);
        fclose($fh);

        AppHelper::download_file($fullFilePath, 'application/txt');
        exit();

        
    }

}