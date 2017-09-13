<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$readonly = 'disabled';
?>    
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?= $this->lang->line('view_main_category') ?></div></h4>
        </div>
        <form id="from-model" method="post"  action="" data-parsley-validate> 
            <div class="modal-body">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <label> <?= $this->lang->line('category_name') ?>  :</label>
                        <?php echo!empty($edit_record[0]['category_name']) ? ucfirst($edit_record[0]['category_name']) : 'N/A' ?>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <label> <?= $this->lang->line('type') ?>  :</label>
                        <?php echo!empty($edit_record[0]['type']) ? ucfirst($edit_record[0]['type']) : 'N/A' ?>
                    </div>
                </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label><?php echo lang('client_visible'); ?> :</label>
                               <?php if($edit_record[0]['client_visible'] == 1) {?>
                                YES
                                   <?php } else { ?>
                                NO
                                   <?Php } ?>
                            </div>
                           
                        </div>
                <div class="form-group row">
                            <div class="col-sm-12">
                                <label><?php echo lang('article_owner'); ?> :</label>
                                <?php echo!empty($edit_record[0]['firstname']) ? ucfirst($edit_record[0]['firstname']) . ' ' . $edit_record[0]['lastname'] : 'N/A' ?>
                            </div>
                           
                        </div>
                  
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label>  <?php echo lang('product_related'); ?> :</label>
                                <?php
                            if(count($product_info) > 0 && $product_info != "0") {
                                $tmp_str = '';
                                foreach ($product_info as $row) {
                                    if (in_array($row['product_id'], $product_data)) {
                                        ?>
                                        <?php $tmp_str .= $row['product_name'] . ','; ?>
                                    <?php }
                                }
                                echo rtrim($tmp_str, ',');
                            }else{
                                echo "N/A";
                            }
                            ?>
                           
                            </select>
                            </div>
                        </div>
                <div class="form-group row">
                     <div class="col-sm-12">
                <?php if (!empty($edit_record[0]['icon_image'])) { ?>
                                 <div class="col-lg-6">
                                     <img class="img-responsive thumbnail" style="width: 30px" src="<?php echo base_url('uploads/knowledgebase') . '/' . $edit_record[0]['icon_image']; ?>">
                            </div>
<?php } ?>
                   </div>
                     </div>
                
            </div>
            <div class="modal-footer">

            </div>
        </form>

    </div>
</div>
<script>
    $('.product_related').trigger('chosen:updated');
    $('#client_visible').bootstrapToggle();
    $('#product_related').bootstrapToggle();

    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
</script>
<script>
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
</script>