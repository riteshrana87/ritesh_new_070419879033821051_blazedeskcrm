<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
    <input type="hidden" id="uri_segment" name="uri_segment"
           value="<?php if (isset($uri_segment)) echo $uri_segment; ?>"/>


    <table id="statustable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th <?php
            if (isset($sortfield) && $sortfield == 'status_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('status_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('status_name') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'status_color') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('status_color', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('status_color') ?>
            </th>

             <th><?= lang ('status_font_icon') ?></th>
            <?php if (checkPermission ('ProjectStatus', 'edit') || checkPermission ('ProjectStatus', 'delete') || checkPermission ('ProjectStatus', 'view')) { ?>
                <th><?= lang ('actions') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($status_data) && count ($status_data) > 0) { ?>
            <?php foreach ($status_data as $status_data) { ?>
                <tr id="status_<?=$status_data['status_id']?>">
                    <td><?= !empty($status_data['status_name']) ? $status_data['status_name'] : '' ?></td>
                    <td><span class="color_box"
                              style="background-color:<?= !empty($status_data['status_color']) ? $status_data['status_color'] : '' ?>">&nbsp;</span>
                    </td>
                    <td><?= !empty($status_data['status_font_icon']) ? '<i class="fa fa-' . $status_data["status_font_icon"] . ' blackcol"></i>' : '' ?></td>
                    <?php if (checkPermission ('ProjectStatus', 'edit') || checkPermission ('ProjectStatus', 'delete') || checkPermission ('ProjectStatus', 'view')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ("ProjectStatus", 'view')) { ?><a
                                data-href="<?= base_url () ?>ProjectStatus/view_record/<?= $status_data['status_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission ('ProjectStatus', 'edit')) { ?><a
                                data-href="<?= base_url () ?>ProjectStatus/edit_record/<?= $status_data['status_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if(checkPermission('ProjectStatus','delete')){ if(empty($status_data['default_status'])) {?><a href="javascript:void(0);" onclick="deleteItem(<?php echo $status_data['status_id']; ?>)" title="<?php echo lang('delete');?>"><i class="fa fa-remove redcol"></i></a><?php } } ?>
                        </td>
                    <?php } ?>
                </tr>

            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center">
                    <?= lang ('common_no_record_found') ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-md-12 text-center">
        <div id="common_tb" class="no_of_records">
            <?php
            if (isset($pagination)) {
                echo $pagination;
            }
            ?>
        </div>
    </div>
</div>
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('project_status_delete_message'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        window.location.href = "<?php echo base_url('ProjectStatus/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });
    }
    var fixHelper = function(e, ui) {  
  ui.children().each(function() {  
    $(this).width($(this).width());  
  });  
  return ui;  
};
    $("#statustable1 tbody").sortable({
            //connectWith: ".connectedSortable",
            helper: fixHelper,
            update: function(event, ui) {

                   /*var position = ui.placeholder.index()
                   sta = ui.item[0].attributes[0].nodeValue;
                   attr_id = sta.split('_');*/
                   var statusArray = new Array();
                   $( "#statustable1 tbody tr" ).each(function( index ) {
                    var id = $( this ).attr('id');
                     attr_id = id.split('_');
                      //console.log( index + ": " + $( this ).attr('id') );
                      statusArray.push(attr_id[1]);
                    });
                   
                  $.ajax({
                    url: "<?php echo base_url('ProjectStatus/update_order'); ?>",
                    type: 'POST',
                    data: {'status_order': statusArray},
                    success: function (res) {
                        if (res == 'done') {
                            paginationClick();
                            getHomeHeader();
                            getActivity();
                            getTodayTask();
                            return false;

                        }
                    },
                    error: function () {
                        console.log('Error in call');
                    }

                });
            },
            receive: function (event, ui) {
                alert('hi');
                 //console.log(ui.item);
                 console.log(ui.item.attributes.id);
                var cls;
                var cur_status;
                sta = this.id;
                var status = sta.substr(sta.length - 1);
                var tid = ui.item[0].id;
                attr_id = tid.split('-');
                task_id = attr_id[1];
                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/ProjectTask/update_status'); ?>",
                    type: 'POST',
                    data: {'status': status, 'task_id': task_id},
                    success: function (res) {
                        if (res == 'done') {
                            paginationClick();
                            getHomeHeader();
                            getActivity();
                            getTodayTask();
                            return false;

                        }
                    },
                    error: function () {
                        console.log('Error in call');
                    }

                });
            }
        }).disableSelection();
</script>