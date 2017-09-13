<div class="whitebox">
    <div class="row">
        <div class="col-lg-9 col-xs-12 col-sm-8">
            <div class="form-group bd-mail-head bd-inbox">
                <ul>
                    <li id="refereshCode">
                        <button type="button" data-boxtype="INBOX" id="refreshBn"><i class=" bd-refresh-ico"></i><span><?= lang('mail_refresh') ?></span></button>
                    </li>
                    <li>
                        <a href="<?php echo base_url('Mail/ComposeMail'); ?>"><i class="bd-compose-ico"></i><span><?= lang('mail_compose') ?></span></a>
                    </li>
                    <li id="replyEmail">
                        <button type="button" onclick="replyEmail();"  ><i class="bd-reply-ico"></i><span><?= lang('mail_reply') ?></span></button>
                    </li>
                    <li id="replyAll">
                        <button type="button" onclick="replyAll();" ><i class="bd-replyall-ico"></i><span><?= lang('mail_reply_all') ?></span></button>
                    </li>
                    <li id="forwardEmail">
                        <button type="button" onclick="forwardEmail();"><i class="bd-forward-ico"></i><span><?= lang('mail_forward') ?></span></button>
                    </li>
                    <li id="trashMail">
                        <button type="button" title="Move to trash"><i class="bd-remove-ico"></i><span><?= lang('mail_remove') ?></span></button>
                    </li>
                    <li id="flagMail">
                        <button type="button" title="Mark as flag"><i class="bd-flag-ico"></i><span><?= lang('mail_flag') ?></span></button>
                    </li>
                    <!--                                    <li>
                                                            <button type="button" ><i class="bd-more-ico"></i><span>More</span></button>
                                                        </li>-->
                </ul>
            </div>
        </div>
        <div class="col-lg-3 col-xs-12 col-sm-4">
            <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                <div class="bd-mail-detail border-0">
                    <div class="bd-sesrch-contact ">
                        <div class="search-top">
                            <div class="input-group">
                                <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                                <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="Search for..." aria-controls="example1" placeholder="Search" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                                <span class="input-group-btn">
                                    <button onclick="data_search('changesearch')" class="btn btn-default"  title="Search"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i></button>
                                    <button class="btn btn-default howler flt" onclick="reset_data()" title="Reset"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i></button> 
                                </span>
                            </div>
                            <!--                                            <div class="navbar-form row">
                                                                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                                                <input type="text" placeholder="" class="form-control col-lg-11 col-md-12 col-sm-12">
                                                                            </div>
                                                                            <button class="fa fa-search btn btn-default" type="submit"></button>
                                                                        </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!--                            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <select class="form-control" name="boxtype" id="boxtype">
                                                <option value="INBOX">INBOX</option>
                                                <option value="DRAFTS">DRAFTS</option>
                                                <option value="SPAM">SPAM</option>
                                            </select>
                                        </div>-->
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 bd-inbox-elem form-group" id="common_div">
            <?php echo $this->load->view('MailAjaxList'); ?>
        </div>
    </div>

    <!--                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="col-lg-8 col-md-8 col-sm-8 form-group">
                                        <select class="form-control ">
                                            <option>All</option>
                                            <option>1</option>
                                        </select>
                                    </div>
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
</div>