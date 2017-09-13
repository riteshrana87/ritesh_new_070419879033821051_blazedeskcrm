<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div id="mailTable">
    <div class="table table-responsive" >
        <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
        <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
        <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>"/>


        <div class="table table-bordered ">
            <ul class="inbox-table-head">
                <li class="bd-select"><input type="checkbox" class="checkbox" id="selectall"></li>
                <li class="bd-in-stats"><a href="javascript:;"></a></li>
                <li  <?php
                if (isset($sortfield) && $sortfield == 'mail_subject') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-3 sorting_desc'";
                    } else {
                        echo "class = ' col-sm-2 col-md-3 sorting_asc'";
                    }
                } else {
                    echo "class = ' col-sm-2 col-md-3 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('mail_subject', '<?php echo $sorttypepass; ?>')" ><a href="javascript:;"> <?= lang('mail_subject') ?></a></li>
                <li class="bd-in-star"><a href="#"><i class="fa fa-star"></i></a></li>
                <li  <?php
                if (isset($sortfield) && $sortfield == 'from_mail') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-3 sorting_desc'";
                    } else {
                        echo "class = 'col-sm-2 col-md-3 sorting_asc'";
                    }
                } else {
                    echo "class = 'col-sm-2 col-md-3 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('from_mail', '<?php echo $sorttypepass; ?>')"><a href="javascript:;"><?= lang('mail_from') ?></a></li>
                <li  <?php
                if (isset($sortfield) && $sortfield == 'send_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'col-sm-2 col-md-3 sorting_desc'";
                    } else {
                        echo "class = 'col-sm-2 col-md-3 sorting_asc'";
                    }
                } else {
                    echo "class = 'col-sm-2 col-md-3 sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('send_date', '<?php echo $sorttypepass; ?>')"><a href="javascript:;"> <?= lang('mail_datetime') ?></a> </li>
                <li class="bd-in-size"><a href="javascript:;"><?= lang('mail_attachment_size') ?></a></li>
                <li class="bd-in-flag"><a href="javascript:;"><i class="bd-flag-ico"></i></a></li>
                <li class="bd-in-attach"><a href="javascript:;"><i class="bd-attach-ico"></i></a></li>


            </ul>
            <div class="table">
                <div class="clr"></div>
                <ul class="inbox-table-body">
                    <?php if (count($mail_data) > 0) { ?>
                        <?php
                        foreach ($mail_data as $mail) {
                            $flagReply = strpos($mail['mail_subject'], 'RE:');
                            ?>


                            <li class="mail-tr <?php echo ($mail['is_flagged'] == 1) ? ' bd-in-mark' : ''; ?> "  data-forward="<?php echo base_url('Mail/forwardEmail/' . $mail['uid']); ?>" data-reply="<?php echo base_url('Mail/replyEmail/' . $mail['uid']); ?>" data-replyall="<?php echo base_url('Mail/replyEmailAll/' . $mail['uid']); ?>">
                                <div class="bd-select tabl-style"><input type="checkbox" name="checkedIds[]" id="<?php echo $mail['uid']; ?>" class="checkbox-inline actioncheckbx" ></div>
                                <div class="bd-in-stats tabl-style"><?php echo ($flagReply !== false ) ? '<a href="javascript:;"><i class="bs-share-ico"></i></a>' : '<a href="javascript:;"></a>'; ?></div>
                                <div class="col-sm-2 col-md-3 bd-desc tabl-style"><a  onclick="markasUnread('<?php echo $mail['uid']; ?>')" data-href="<?php echo base_url('Mail/viewThread/' . $mail['uid']); ?>" data-toggle="ajaxModal"><?php echo ($mail['is_unread'] == 1) ? "<span id=" . $mail['uid'] . " class='font-bold'>" . $mail['mail_subject'] . "</strong>" : $mail['mail_subject']; ?></a></div>
                                <div class="bd-in-star tabl-style">  <?php if ($mail['is_starred'] == 0) { ?>
                                        <a href="javascript:void(0);" class="starred" data-id="<?php echo $mail['uid']; ?>" ><i id="star_<?php echo $mail['uid']; ?>" class="fa fa-star-o"></i></a>

                                    <?php } else { ?>
                                        <a href="javascript:void(0);" class="unstarred" data-id="<?php echo $mail['uid']; ?>" ><i  id="star_<?php echo $mail['uid']; ?>" class="fa fa-star"></i></a>

                                    <?php } ?></div>
                                <div class="col-sm-2 col-md-3 tabl-style table-mail-resp"><a href="javascript:void(0);"><?php echo $mail['from_mail']; ?></a></div>
                                <div class="col-sm-2 col-md-3 tabl-style hidden-xs bd-in-date"><a href="javascript:void(0);"><?php echo datetimeformat($mail['send_date']); ?></a></div><div class="visible-xs bd-mail-date"><?php echo date('d M', strtotime($mail['send_date'])); ?></div><div class="visible-xs bd-detaildesc"><p><?= !empty($mail['mail_body']) ? html_substr(strip_tags($mail['mail_body']), 0, 50) : '' ?></p></div>
                                <div class="bd-in-size tabl-style"><a href="javascript:void(0);"><?php echo ($mail['file_size'] != '') ? $mail['file_size'] . '.kb' : '-'; ?></a></div>
                                <div class="bd-in-flag tabl-style <?php echo ($mail['is_flagged'] == 1) ? ' bd-in-mark' : ''; ?>"> <?php if ($mail['is_flagged'] == 0) { ?>
                                        <a href="javascript:void(0);" class="flagged" data-id="<?php echo $mail['uid']; ?>" > <i id="flag_<?php echo $mail['uid']; ?>"  class="bd-flag-ico"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);" class="unflagged" data-id="<?php echo $mail['uid']; ?>" > <i id="flag_<?php echo $mail['uid']; ?>"  class="bd-flag-ico"></i></a>
                                    <?php } ?>    
                                </div>
                                <div class="bd-in-attach tabl-style"><?php
                                    if ($mail['file_size'] != '') {
                                        $url = base_url('Mail/saveAttachment/' . $mail['email_unq_id']);
                                        $icon = '<i class="bd-attach-ico"></i>';
                                    } else {
                                        $url = 'javascript:;';
                                        $icon = '';
                                    }
                                    ?>
                                    <a target="_blank" href="<?php echo $url; ?>"><?php echo $icon; ?></a>
                                </div>
                                <div class="clr"></div>
                            </li>

                            <?php
                        }
                    } else {
                        ?>
                        <center> <?= lang('mail_not_found') ?> </center>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="clr"></div>
    <!--    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="col-lg-8 col-md-8 col-sm-8 form-group"><select class="form-control ">
                                        <option>All</option>
                                        <option>1</option>
                                    </select></div>
                            </div>
                <div class="col-lg-2 form-group bd-in-pagn-msg col-md-2 col-sm-2">
                    <span>Message 1 to 13  of 34</span>
                </div>
                
                <div class="col-lg-3 form-group col-md-3 col-sm-3">
                    <ul class="bd-inbox-pagin">
                        <li><a href="#"><i class="disabled fa fa-backward"></i></a></li>
                        <li><a href="#"><i class="disabled fa fa-caret-left"></i></a></li>
                        <li><a href="#"><i class="fa fa-caret-right "></i></a></li>
                        <li><a href="#"><i class="fa fa-forward"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>-->
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
</div>
<script>
    $(document).ready(function () {

        var liveCount =<?php echo $liveCount; ?>;
        var dbCount =<?php echo $dbCount; ?>;

        if (liveCount > dbCount) {
            $('.fa-backward').hide();
            $('.fa-forward').hide();
        } else
        {
            $('.fa-backward').hide();
            $('.fa-forward').hide();
        }
    });
</script>