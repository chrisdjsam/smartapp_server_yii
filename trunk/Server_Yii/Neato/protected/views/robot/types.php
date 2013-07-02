<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle = 'Robots - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Robots' => array('index'),
    'types',
);
?>
<fieldset class='data-container static-data-container'>
    <legend>Robot Types</legend>

    <p class="list_details">
        All the available robot types are listed below.<br />Click
        on edit to update a specific robot type information. <br /> You can also
        select robot types and click on delete button to delete robot types.
    </p>
    <div class="action-button-container">
            <div id="add_robot_type" title="Add Robot Type" class="neato-button">Add</div>
            <div id="delete_robot_type" title="Delete Robot Type" class="neato-button">Delete</div>
    </div>

    <table class="pretty-table robot_types-table">
        <thead>
            <tr class="notification_datagrid">
                <th style="width: 8%;" title="Select" class='pretty-table-center-th'>Select</th>
                <th style="width: 15%;" title="Robot Type" class='pretty-table-center-th'>Robot Type</th>
                <th style="width: 32%;" title="Robot Type Name" class='pretty-table-center-th'>Name</th>
                <th style="width: 15%;" title="Associated Users" class='pretty-table-center-th'>Sleep Time</th>
                <th style="width: 15%;" title="Schedule" class='pretty-table-center-th'>Lag Time</th>
                <th style="width: 15%;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($robot_types as $type) { 
                
                    $sleep_time = '';
                    $lag_time = '';
                    foreach ($type->robotTypeMetadatas as $metadata) {
                        
                        if($metadata->_key == 'sleep_time'){
                            $sleep_time = $metadata->value/60;
                        } elseif($metadata->_key == 'lag_time') {
                            $lag_time = $metadata->value;
                        }
                        
                    }
                ?>
                
                <tr>
                    <td>
                        <?php 
                            if(Yii::app()->params['default_robot_type'] == $type->type){
                                echo 'Default';
                            } else {
                                ?><input type="checkbox" name="chooseoption[]" value="<?php print $type->id ?>" class="choose-option"><?php
                            }
                        ?>
                        
                    </td>
                    <td>
                        <?php print $type->type; ?>
                    </td>
                    <td>
                        <?php print $type->name; ?>
                    </td>
                    <td>
                        <?php print $sleep_time; ?> min
                    </td>
                    <td>
                        <?php print $lag_time; ?> sec
                    </td>
                    <td><?php echo '<a href="'.$this->createUrl('/robot/updateType',array('h'=>AppHelper::two_way_string_encrypt($type->id))).'" title="Edit robot '.$type->type.'">edit</a>';?></td>

                </tr>

            <?php } ?>
        </tbody>
    </table>

</fieldset>

<script>
    $('#add_robot_type').click(function(){
        window.location.href = '<?php echo $this->createUrl('robot/addType')?>';
    });    
    
    $('#delete_robot_type').click(function(){
        var chosen_type = new Array();
        var index = 0;
        $('input[name=chooseoption[]]:checked').each(function()
        {
            if($(this).val() != 'on'){
                chosen_type[index] = $(this).val();
                index++;
            }
        });

        if (typeof chosen_type !== 'undefined' && chosen_type.length > 0) {
  
            $.ajax({
                type: 'POST',
                url: app_base_url +'/api/robot/deleteType',
                dataType: 'jsonp',
                data: {
                    chosen_type: chosen_type
                },
                success: function(r) {
                    hideWaitDialog();
                    if (r.status === 0) {
                        generate_noty("success", r.message);
                        window.location.href = window.location.pathname;
                    } else { // Handle errors
                        generate_noty("error", "Error while deletion");
                    }
                },
                error: function(r) {
                    hideWaitDialog();
                    generate_noty("error", "Error while deletion");
                },
                beforeSend: function(){
                    showWaitDialog();
                },
                complete: function(){
                    hideWaitDialog();
                }
            });
            
        } else {
            alert("Please select at least one robot type");
        }

    });
</script>
