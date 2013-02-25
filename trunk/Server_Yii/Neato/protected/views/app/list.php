<?php
/* @var $this RobotController */
/* @var $model Robot */
$this->pageTitle = 'Robots - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Robots' => array('index'),
    'List',
);
$isAdmin = Yii::app()->user->isAdmin;
?>
<fieldset class='data-container static-data-container'>
    <legend>App Versions</legend>

    <p class="list_details">
        All the available application versions are listed below.<br />
        Click on latest version URL to download latest version. <br />
        <?php if ($isAdmin) { ?>
            Click on edit to update a specific version information. <br /> 
            click on delete to delete a version information.<br /><br />
        <?php } ?>
    </p>
    <form action=" " method="POST" id="appList">
        <?php if ($isAdmin) { ?>
            <div class="list-add_app_version">
                <span class="action-button-container">
                    <a href="<?php echo $this->createUrl('app/add') ?>" title="Add app version" class="neato-button">Add</a> 
                </span>
            </div>

            <table class="pretty-table version-table">

            <?php } else { ?>

                <table class="pretty-table version-table" style = "margin-top: 20px">

                <?php } ?>
                <thead>
                    <tr>
                        <th style="width: 7%;"  class='pretty-table-center-th'>App ID</th>
                        <th style="width: 9%;"  class='pretty-table-center-th'>Current Version</th>
                        <th style="width: 9%;"  class='pretty-table-center-th'>OS Type</th>
                        <th style="width: 9%;"  class='pretty-table-center-th'>OS Version</th>
                        <th style="width: 9%;"  class='pretty-table-center-th'>Latest Version</th>
                        <th style="width: 30%;"  class='pretty-table-center-th'>Latest Version URL</th>
                        <th style="width: 13%;"  class='pretty-table-center-th'>Upgrade Status</th>
                        <?php if ($isAdmin) { ?>
                            <th style="width: 7%;"  class='pretty-table-center-th'></th>
                            <th style="width: 7%;"  class='pretty-table-center-th'></th>
                        <?php } ?>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($app_data as $value) { ?>
                        <tr>

                            <td class='pretty-table-center-td'><?php echo $value->app_id ?></td>
                            <td class='pretty-table-center-td'><?php echo $value->current_app_version ?></td>
                            <td class='pretty-table-center-td'><?php echo $value->os_type ?></td>
                            <td class='pretty-table-center-td'><?php echo $value->os_version ?></td>
                            <td class='pretty-table-center-td'><?php echo $value->latest_version ?></td>

                            <td class='pretty-table-center-td' >
                                <a class = "look-like-a-link" title="App version<?php echo $value->latest_version; ?> for <?php echo $value->os_type . ' ' . $value->os_version; ?>"
                                   href="<?php echo $value->latest_version_url; ?>"
                                   target="_blank"><?php echo $value->latest_version_url; ?>

                                </a>
                            </td>

                            <td class='pretty-table-center-td'>
                                <?php echo $status_array[$value->upgrade_status] ?>
                            </td>
                            <?php if ($isAdmin) { ?>
                                <td class='pretty-table-center-td'><a
                                        href=<?php echo $this->createUrl('app/update', array('h' => AppHelper::two_way_string_encrypt($value->id),)) ?>
                                        title="Edit App Version <?php echo $value->app_id ?>"
                                        class="look-like-a-link ">edit</a>
                                </td>

                                <td class='pretty-table-center-td'><div
                                        class="delete-single-app_version look-like-a-link "
                                        href=<?php echo $this->createUrl('api/app/appDelete', array('h' => AppHelper::two_way_string_encrypt($value->app_id))) ?>
                                        title="Delete App Version<?php echo $value->app_id ?>">delete</div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

    </form>
</fieldset>
