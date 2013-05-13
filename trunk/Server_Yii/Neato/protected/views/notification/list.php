<?php

$this->pageTitle = 'Notifications - ' . Yii::app()->name;
$baseURL = Yii::app()->baseUrl;
$this->breadcrumbs = array(
    'Notification' => array('index'),
    'List',
);

?>
<fieldset class='data-container static-data-container'>
    <legend>Notifications</legend>

    <p class="list_details">
        
        Please select a filtering criteria and type in a message and click on send.<br/>
        If you want to send targeted notifications, select "Selected Devices" option and then select registration ids.<br/>
        You can also delete unused registration IDs by first selecting "Selected Devices" and then select the registration Ids to delete.<br/>
        If you click on the email links, you can navigate to the profile page for the user.<br/>
        If you want to see specifics of the past notifications, click on "Logs" button.<br/>        

    </p>
    
    <div>
        <a href ="notificationHistory" id="Show_notification_history" class='neato-button_alt right' title="Logs">Logs</a>
    </div>

    <form action='' method='POST' id='notification_form_data' class='device_details'>
        <div class="send_notification_container">

            <div class="space_for_notification_response"><span id="list-space_for_notification_response"></span></div>
            <div class="left span-3">
                <label class="notification_message_label">Message</label>
            </div>
            <div>
                <textarea id="message_to_send" class="" name="message_to_send" rows="4"></textarea>
            </div>

            <div class="notification_by_device_type">
                <div class="span-3">
                    <label class="notification_message_label">Send to</label>
                </div>
                <input name="notification_send_by_device_type" type="radio" value="registration_ids" checked="checked"/>
                <label class="notification_filter_label">Selected devices</label>
                <input name="notification_send_by_device_type" type="radio" value="1"/>
                <label class="notification_filter_label">All Android devices</label>
                <input name="notification_send_by_device_type" type="radio" value="2"/>
                <label class="notification_filter_label">All iPhone devices</label>
                <input name="notification_send_by_device_type" type="radio" value="all"/>
                <label class="notification_filter_label">All devices</label>
            </div>
        </div>


        <table class="pretty-table notifications-table">
            <thead>
                <tr class="notification_datagrid">
                    <th style="width: 10%; padding: 0px;"  class='pretty-table-center-th'>Select</th>
                    <th style="width: 25%;"  class='pretty-table-center-th'>Email</th>
                    <th style="width: 50%;"  class='pretty-table-center-th'>Registration Id</th>
                    <th style="width: 15%; padding: 0px;"  class='pretty-table-center-th'>Device</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <div class="send_notification_btn right">
            <div id="list_send_message_event" class='neato-button_alt right' title="Send">Send</div>
            <div id="list_delete_selected_reg_id" class='neato-button_alt right hide-me' title="Delete">Delete</div>
        </div>

    </form>

</fieldset>

<script>
    
    $(document).ready(function(){
        
        var baseURL = '<?php echo $baseURL; ?>';
        
        var handle = 'notifications-table';
        var length = 25;
        var url = '<?php echo $baseURL . '/api/message/NotificationListDataTable' ?>';
        var colomns_to_disable_sort = [0];
        var default_sorting = [ 1, 'asc' ];
        
        notification_list_table(handle, length, url, colomns_to_disable_sort, default_sorting);
        $('form#notification_form_data div#DataTables_Table_0_wrapper').show();
        $('#list_delete_selected_reg_id').show();
        
        $('input:radio[name="notification_send_by_device_type"]').unbind('click').click(function () {
            var filter_val = $('input:radio[name="notification_send_by_device_type"]:checked').val();
            if(filter_val == 'registration_ids') {
                notification_list_table(handle, length, url, colomns_to_disable_sort, default_sorting);
                $('.notifications-table').show();
                $('form#notification_form_data div#DataTables_Table_0_wrapper').show();
                $('#list_delete_selected_reg_id').show();
            } else {
                $('.notifications-table').hide();
                $('form#notification_form_data div#DataTables_Table_0_wrapper').hide();
                $('#list_delete_selected_reg_id').hide();
            }
        });
        
        
        $('#list_delete_selected_reg_id').click(function(){

            if($('input:checkbox[name="chooseoption"]:checked').size()){
                
                if(confirm('Are you sure?')){
                    var chosen_data = $('#notification_form_data').serializeArray();
                    $.ajax({
                        type: 'POST',
                        url: baseURL + "/api/message/DeleteChosenRegistrationIds",
                        dataType: 'json',
                        data: {
                            chosen_data: chosen_data
                        },
                        success: function(r) {

                            notification_list_table(handle, length, url, colomns_to_disable_sort, default_sorting);
                            generate_noty("success", "Chosen Registration Ids are deleted Successfully.");
                            
                        },
                        error: function(r){
                            console.log(r);
                        }
                    });
                }
            } else {
                alert('Please select atleast one Registration Id.');
            }
        });

        var notification_response_handle = $('#list-space_for_notification_response');
        $('#list_send_message_event').click(function(){
            notification_response_handle.text("Notification sending initiated.");
            var notification_data = [];
            notification_data = $('#notification_form_data').serializeArray();
            $.ajax({
                type: 'POST',
                url: baseURL + "/api/message/SendNotification",
                dataType: 'json',
                data: {
                    notification_data: notification_data
                },
                success: function(r) {
                    notification_response_handle.text(JSON.stringify(r));
                    notification_list_table(handle, length, url, colomns_to_disable_sort, default_sorting);
                },
                error: function(r){
                    console.log(r);
                }
            });
        });
        
    });
    
</script>