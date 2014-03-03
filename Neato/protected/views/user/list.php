<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle='Users - ' . Yii::app()->name;
$this->breadcrumbs=array(
		'Users'=>array('index'),
		'List',
);
?>
<?php $is_wp_enabled = Yii::app()->params['is_wp_enabled'];?>
<?php $userRole = Yii::app()->user->UserRoleId;?>

<fieldset class='data-container static-data-container'>
	<legend>
		<?php if($userRole != '2'){?>
		Users
	<?php
	}else{ 
	?>
	Search User
	<?php
	} 
	?>
	</legend>

	<p class="list_details">
	<?php if($userRole != '2'){?>
		All the users are listed below.
		<br /><?php }?>
		<?php if($userRole == '2'){?>
		You can search a specific user by typing in his exact email address and then clicking on search button.<br />
		<?php }?>		 

		You can view user profile by
		clicking on the email of a specific user.<br /> You can view robot
		information by clicking on the associated robot's serial number.<br />
                <?php if($userRole != '2'){?>
                	<?php if(!$is_wp_enabled){ ?>
						You can also select a user and click on delete button to delete a
						user.<br /> Please note that deleting a user would also delete the
						user-robot associations for that specific user.<br />
                	<?php }?>
                <?php }?>
	</p>
	
	<?php if($userRole == '2'){?>
		<div class="search-box">
			<input class="search-text-input" type="text" aria-controls="" / ><span><button class="search-button for-user">Search</button></span>
		</div>
	<?php }?>
	
	<form action="<?php echo $this->createUrl('user/delete') ?>"
		method="POST" id="userList">
			<?php if($userRole != '2'){?>
                <?php if(!$is_wp_enabled){ ?>
                    <div class="action-button-container">
			<a href="<?php echo $this->createUrl('user/add')?>" title="Add user"
				class="neato-button">Add</a> <input type='button' value='Delete'
				id='deleteSelected' class='deleteSelected neato-button'
				title="Delete user" />
                    </div>
                <?php }?>
            <?php }?>
		<table class="pretty-table user-table">
			<thead>
				<tr>
					<?php if($userRole !== '2'){?>
						<th style="width: 38px;" title="Select" class='pretty-table-center-th'>Select</th>
					<?php }?>
					<th title="Email">Email</th>
					<?php if($userRole !== '2'){?>
						<th title="Name">Name</th>
					<?php }?>
					<th title="Associated Robots">Associated Robots</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
</fieldset>
<script>
var user_role_id = '<?php print Yii::app()->user->UserRoleId;?>'
$('#deleteSelected').click(function(){
       var is_any_check_box_checked = false;
       $('.choose-option').each(function(index) {
               if ($(this).is(':checked')) {
                       is_any_check_box_checked = true;
               }
       });

       if(is_any_check_box_checked){
               if(confirm("Are you sure you want to delete selected users?")){
                       $('#userList').submit();
               }
       }else{
               alert('Select at least one row to delete.');
       }
})
</script>
