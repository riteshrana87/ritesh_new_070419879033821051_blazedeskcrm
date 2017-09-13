<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?= !empty($meeting_data[0]['meet_title']) ? $meeting_data[0]['meet_title'] : '' ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="col-md-8 col-sm-7">
                <div class="form-group  bottom-gray-border ">

                    <div class="row mb15">  
                        <div><label><?php echo lang('MEETING_PARTICIPANTS'); ?> :</label></div>

                        <?php
                        foreach ($participant_array as $participant)
                        { ?>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="row">	
                                    <div class="col-md-4 col-sm-4"><img src="<?php echo $participant['participant_image'];?>" alt="" class="img-responsive"></div>
                                    <div class="col-md-8 col-sm-8"><label><?php echo $participant['participant_name'];?></label>
                                        <div><?php echo $participant['participant_title'];?></div>
                                        <div><?php echo $participant['participant_company'];?></div></div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        
                       <?php }
                        ?>
                        <div class="clr"></div>

                    </div>
                </div>
                <div class="form-group row ">

                    <label><?php echo lang('MEETING_DESCRIPTION'); ?> :</label>
                    <div> <?= !empty($meeting_data[0]['meeting_description']) ? $meeting_data[0]['meeting_description'] : 'N/A' ?></div>

                </div>
                <div class="form-group row">

                    <label><?php echo lang('ATTACHMENT_FILE'); ?> </label>
                    <div> <ul class="files">
                            <?php
                            if (isset($meeting_attach_data) && count($meeting_attach_data) > 0) {

                                foreach ($meeting_attach_data as $data) {
                                    $file_name = $data['file_name'];

                                    $file_extension = explode('.', $file_name);

                                    $document_logo_file_name = getImgFromFileExtension($file_extension[1]);
                                    $document_logo_file_path = base_url() . "/uploads/images/icons64/" . $document_logo_file_name;

                                    $image_path = base_url() . $data['file_path'] . "/" . $file_name;
                                    ?>
                                    <li id="contact_file_<?php echo $data['file_id']; ?>" class="bd-contact-rmv">
                                        <p class="text-center"><a href="<?php echo $image_path; ?>" download>
                                                <img src="<?php echo $document_logo_file_path; ?>" alt=""/>
                                            </a>
                                        </p>
                                        <p class="text-center"><a href="<?php echo $image_path; ?>" download><?php echo $file_name; ?></a></p>

                                    </li>
    <?php
    }
} else {
    echo "N/A";
}
?>

                        </ul></div>



                </div>
            </div>
            <div class="col-md-4 col-sm-5 meeting-grey-border bd-resp-noborder">
                <div class="form-group row">
                    <div class="bottom-gray-border "><div class="col-sm-12   ">
                            <label><?= $this->lang->line('MEETING_DATE') ?>:</label>
                            <div class="schdl-meeting-date"><?= !empty($meeting_data[0]['meeting_date']) ? configDateTime($meeting_data[0]['meeting_date']) : '' ?></div>
                        </div>
                        <div class="col-sm-12 ">
                            <div class="col-sm-6">
                                <label><?php echo lang('START');?> : </label> <?= !empty($meeting_data[0]['meeting_time']) ? convertTimeTo12HourFormat($meeting_data[0]['meeting_time']) : '' ?> 
                            </div>
                                
                            <div class="col-sm-6 text-right">
                                <label><?php echo lang('finish');?> : </label> <?= !empty($meeting_data[0]['meeting_end_time']) ? convertTimeTo12HourFormat($meeting_data[0]['meeting_end_time']) : '' ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div></div>

                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                       
                        <h4 class="bluecol cust-meeting-loc"> <?= !empty($meeting_data[0]['company_name']) ? $meeting_data[0]['company_name'] : '' ?></h4>
                    </div>
                    <div class="clr"></div>
                    <div class="col-sm-12">

                        <div class="mb15"><?= !empty($meeting_data[0]['meeting_location']) ? $meeting_data[0]['meeting_location'] : 'N/A' ?></div>

                        <div id="contact_address"></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>

            <div class="clr"></div>


        </div>
        <div class="modal-footer">

        </div>
    </div>

</div>

<script>
    var contact_address = '<?php echo $meeting_data[0]['meeting_location']; ?>';
    var embed = '<iframe width="200" height="150" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?&amp;q=' + encodeURIComponent(contact_address) + '&amp;output=embed"></iframe>';
    $('#contact_address').html(embed);
</script>