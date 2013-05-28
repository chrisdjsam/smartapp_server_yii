<?php

$this->pageTitle = 'Notifications - ' . Yii::app()->name;
$baseURL = Yii::app()->baseUrl;
$this->breadcrumbs = array(
    'Notification' => array('index'),
    'Log',
);

?>
<fieldset class='data-container static-data-container'>
    <legend>Notification Logs</legend>

    <p class="list_details">
        
        Click on 'More' link to see detailed log about a specific Notification.<br/>
        Click on 'Refresh' button to refresh the data.<br/>
        Click on 'New' button to send new notification.<br/>
        
    </p>

    <div>
        <a href="#" id="refresh_notification_list_page" class='neato-button_alt right' title="Refresh">Refresh</a>
        <a href="list" id="Show_notification_list_page" class='neato-button_alt right' title="New">New</a>
    </div>

    <table class="pretty-table notifications-history-table">
        <thead>
            <tr class="notification_datagrid">
                <th style="width: 7%;"  class='pretty-table-center-th'>Id</th>
                <th style="width: 50%;"  class='pretty-table-center-th'>Message</th>
                <th style="width: 20%;"  class='pretty-table-center-th text_transform_none'>Sent from</th>
                <th style="width: 16%;"  class='pretty-table-center-th text_transform_none'>Sent at</th>
                <th style="width: 7%;"  class='pretty-table-center-th'></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</fieldset>

<div id="displays_notification_details" title="Notification Details" class="">
    <div>
        <fieldset>
            <div id="notification_details"></div>
        </fieldset>
    </div>
</div>

<script>
    
    $(document).ready(function(){
        
        var baseURL = '<?php echo $baseURL; ?>';
        
        var handle = 'notifications-history-table';
        var length = 25;
        var notification_history_url = '<?php echo $baseURL . '/api/message/NotificationHistoryDataTable' ?>';
        var colomns_to_disable_sort = [2,4];
        var default_sorting = [ 0, 'desc' ];
        
        dataTableForAll(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting, 'show_me_details');
        $('#refresh_notification_list_page').click(function(){
            notification_history_table(handle, length, notification_history_url, colomns_to_disable_sort, default_sorting, 'show_me_details');
        });
        
    });
    
    
    function show_me_details(){
        
        $('.notification_history_details').unbind('click').click(function() {
            var notification_log_id = $(this).data('notification_log_id');
            var notification_details = $('#notification_details');
            
            $.ajax({
                type: 'POST',
                url: '<?php echo $baseURL . '/api/message/NotificationHistoryDetails' ?>',
                dataType: 'json',
                data: {
                    notification_log_id: notification_log_id
                },
                success: function(r) {
                    
                    notification_details.html(r);
                    
                    $( "#displays_notification_details" ).attr('title', 'Log for Notification Id '+notification_log_id);
                    $('#ui-dialog-title-displays_notification_details').html('Log for Notification Id '+notification_log_id);
                    
                    hideWaitDialog();                    
                    $( "#displays_notification_details" ).dialog({
                        width: 700,
                        position:['center',10],
                        modal: true
                    });
                    
                    $('div#displays_notification_details').siblings('div.ui-dialog-titlebar').css('background', '#DC4405');
                    $('div#displays_notification_details').siblings('div.ui-dialog-titlebar').css('color', '#FFFFFF');
                    

                    $('#notification_download').unbind('click').click(function(){
                        $('#form_notification_download').submit();
                    });

                },
                error: function(r){
                    console.log(r);
                },
                beforeSend: function(){
                    showWaitDialog();
                }
                
            });
            
            
        });        
    }
    
</script>