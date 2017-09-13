<div  id="today_task">
    <div class="whitebox sortableDiv" id="today_task">
        <h2 class="title-2"><?= lang('today_task') ?></h2>
        <div class="table table-responsive verticl-scroll">
            <div id="datatable1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped dataTable no-footer" id="" role="grid"
                               aria-describedby="datatable1_info">
                            <thead>
                                <tr role="row">
                                    <th><?php echo lang('task_name') ?></th>
                                    <th><?php echo lang('status') ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($today_task)) {
                                    foreach ($today_task as $row) {
                                        ?>

                                        <tr role="row" class="odd">
                                            <td class="sorting_1"><?= !empty($row['task_name']) ? ucfirst($row['task_name']) : '' ?></td>
                                            <td>
                                                <div class="color_box" style="background-color:<?= $row['status_color'] ?>"></div>
                                            </td>
                                            <!-- <td>Emplo Y.</td> -->
                                            <!-- <td><a href="#"><i class="fa fa-search bluecol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-pencil bluecol"></i></a></td> -->
                                        </tr>
                                    <?php }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="3">No task found.</td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>