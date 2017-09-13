<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$readonly = 'disabled';
?>     
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?= $this->lang->line('view_sub_category') ?></div></h4>
        </div>
        <form id="from-model" method="post"  action="" data-parsley-validate>

            <div class="modal-body">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <label><?= $this->lang->line('sub_category_name') ?> :</label>
                        <?php echo!empty($edit_record[0]['sub_category_name']) ? ucfirst($edit_record[0]['sub_category_name']) : 'N/A' ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div class = "form-group row">
                            <div class = "col-sm-12">
                                <label> <?= $this->lang->line('main_category_name') ?> :</label>
                                <?php echo!empty($edit_record[0]['category_name']) ? ucfirst($edit_record[0]['category_name']) : 'N/A' ?>

                            </div>
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
                        <div class = "form-group row">
                            <div class = "col-sm-12">
                                <label> <?= $this->lang->line('article_owner') ?> :</label>
                                <?php echo!empty($edit_record[0]['firstname']) ? ucfirst($edit_record[0]['firstname']) . ' ' . ucfirst($edit_record[0]['lastname']) : 'N/A' ?>
                            </div>
                        </div>
             
                        <div class = "form-group row">
                            <div class = "col-sm-12">
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
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="clr"> </div>
            </div>
        </form>
    </div>
    <script>
        $('.product_related').trigger('chosen:updated');
        $('#client_visible').bootstrapToggle();
        $('#product_related').bootstrapToggle();
        $('#sub_cat').bootstrapToggle();

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