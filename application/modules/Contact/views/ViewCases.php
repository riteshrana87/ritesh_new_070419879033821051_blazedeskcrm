<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $cases_view . '/' . $formAction;
?>

<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <!-- Modal content-->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h4>
        </div>

        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('title') ?> : </label>
                     <?= !empty($edit_record[0]['title']) ? $edit_record[0]['title'] : '' ?>
                </div>
                
                 <div class="col-sm-6">
                    <label class="control-label"><?= lang ('type') ?> : </label>
                     <?= !empty($edit_record[0]['incident_type_name']) ? $edit_record[0]['incident_type_name'] : '' ?>
                </div>
               

            </div>
            
            
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('BUSINESS_CASES') ?> : </label>
                    <?= !empty($edit_record[0]['business_cases']) ? $edit_record[0]['business_cases'] : '' ?>
                </div>
                <div class="col-sm-6">
                   <label class="control-label"><?= lang ('CASE_SUBJECT') ?> : </label>
                   <?= !empty($edit_record[0]['business_subject']) ? $edit_record[0]['business_subject'] : '' ?>
                </div>

            </div>
           
            
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('RESPONSIBLE') ?> : </label>
                    <?= !empty($edit_record[0]['responsible_name']) ? $edit_record[0]['responsible_name'] : '' ?>
                </div>
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('DEADLINE') ?> : </label>
                    <?= !empty($edit_record[0]['deadline']) ? $edit_record[0]['deadline'] : '' ?>
                </div>

            </div>
           
            
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="control-label"><?= lang ('INCIDENT_STATUS') ?> : </label>
                    <?php
                    if($edit_record[0]['incident_status'] == '1')
                    {
                        echo "In Process";
                    }else if($edit_record[0]['incident_status'] == '2')
                    {
                          echo "On Hold";
                    }
                    
                    ?>
                </div>
                <div class="col-sm-6">
                     <label class="control-label"><?= lang ('created_date') ?> : </label>
                     <?php echo date ('Y-m-d', strtotime ($edit_record[0]['created_date'])); ?>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('description') ?> : </label>
                    <?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?>
                </div>
                
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('file') ?> : </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 ">

                        <div class="row">
                            <div class="col-lg-12 bd-dragimage">
                                <?php
                                if (!empty($cases_files[0]['file_id'])) {
                                    if (count($cases_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                        $i = 15482564;
                                        foreach ($cases_files as $image) {
                                            $path = $image['file_path'];
                                            $name = $image['file_name'];
                                            
                                            $arr_list = explode('.', $name);
                                            
                                            $document_logo_file_name = getImgFromFileExtension($arr_list[1]);
                                            $document_logo_file_path = base_url()."/uploads/images/icons64/".$document_logo_file_name;
                                
                                            $image_path = base_url().$image['file_path']."/".$name;
                                            $arr = $arr_list[1];
                                            if (file_exists($path . '/' . $name)) {
                                                ?>
                                                <div id="img_<?php echo $image['file_id']; ?>" class="eachImage"> 
                                                  
                                                    <span id="<?php echo $i; ?>" class="preview">  
                                                        <p class="image_ext">
                                                            
                                                            <a href="<?php echo $image_path; ?>" download>
                                                                 <img src="<?php echo $document_logo_file_path; ?>" class="img-responsive"/>
                                                            </a>
                                                        </p>
<!--                                                        <img src="<?php echo $document_logo_file_path; ?>" alt="" class="img-responsive"/>-->
                                                        <p class="img_name"><?php echo $name; ?></p>
                                                        
                                                        
                                                    </span> </div>
                                            <?php } ?>
                                            <?php
                                            $i++;
                                        }
                                        
                                        ?>
                                 <div id="deletedImagesDiv"></div>
                                        <?php
                                    }
                                }else
                                {
                                    echo  lang('N_A');
                                }
                                ?>

                            </div>
                            <!--                                                    <div class="col-lg-4 text-right">
                                                                                    <button class="btn btn-primary mar_b6">Select attachment</button>
                                                                                    <label class="custom-upload btn btn-primary">
                                                                                        <input type="file" name="upload_file">
                                                                                        Choose file</label>
                                                                                </div>-->
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="clr"></div>
            </div>
        </div>

        <div class="modal-footer">

        </div>

    </div>
</div>
</div><!-- /.modal-dialog -->
<!-- Modal -->
<div class="modal fade" id="imgviewpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#imgviewpopup').modal('hide');"  aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Preview</h4>
            </div>
            <div class="modal-body">
                <img id="previewImg" class="img-responsive">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick=" $('#imgviewpopup').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function showPreview(elurl)
    {
        $('#previewImg').attr('src', elurl);
        $('#imgviewpopup').modal('show');
    }
    $('#imgviewpopup').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
</script>

