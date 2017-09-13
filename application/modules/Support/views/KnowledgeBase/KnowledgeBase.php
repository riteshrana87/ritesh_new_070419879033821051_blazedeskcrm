<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url() . 'Support'; ?>">
                    <?= lang('support') ?>
                </a></li>
            <li class="active">
                    <?= lang('knowledgebase') ?>
                </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="">
                <div class="row">
                    <div class="col-xs-12 col-lg-6 col-sm-6 col-md-4 no-left-pad">
                        <h3 class="white-link">
                            <?= $this->lang->line('know_base') ?>
                        </h3>
                    </div>
                    <div class="col-xs-12 col-lg-6 col-sm-6 col-md-8 no-left-pad text-right">
                        <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                            <a href="<?php echo base_url('Support/KnowledgeBase/ListofMainCategory'); ?>" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                <?= $this->lang->line('list_main_category') ?>
                            </a>
                            <?php /* echo base_url('Support/KnowledgeBase/AddMainCategory'); */ ?>

                        <?php } ?>
                        <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                            <a href="<?php echo base_url('Support/KnowledgeBase/ListofSubCategory'); ?>" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                <?= $this->lang->line('list_sub_category') ?>
                            </a>
                        <?php } ?>
                        <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                            <a href="<?php echo base_url('Support/KnowledgeBase/ListofArticle'); ?>" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                <?= $this->lang->line('list_knowledge_article') ?>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="whitebox">
                    <div class="col-lg-12 col-xs-12 col-sm-12">
                        <div>
                            <div class="row">
                                <div class="col-lg-9 col-xs-12 col-sm-9">
                                    <div class="row">
                                        <div class="col-lg-12 col-xs-12 col-sm-12">
                                            <div class="row">
                                                <?php
                                                $count = 0;
                                                if (!empty($all_cat) && count($all_cat) > 0) {
                                                    foreach ($all_cat as $row) {
                                                        $count++;
                                                        ?>
                                                        <?php if ($count % 2 == 0) { ?> 
                                                            <div class="row">
                                                            <?php } ?>
                                                            <div class="col-lg-6 col-xs-12 col-sm-6">
                                                                <div class="bd-category-box"> 
                                                                    <?php if(!empty($row['icon_image'])){ ?>
                                                                    <img class="img-responsive thumbnail" style="width: 28px" src="<?php echo base_url('uploads/knowledgebase') . '/' . $row['icon_image']; ?>">
                                                                    <?php } else {  ?>
                                                                    <img class="img-responsive thumbnail" style="width: 28px" src="<?php echo base_url('uploads/knowledgebase/icon.png'); ?>">
                                                                    <?php } ?>
                                                                    <div class="col-lg-11 col-xs-11 col-sm-10">
                                                                        <div class="bd-head">
                                                                            <h2 class="title-2">
                                                                                <?php echo ucfirst($row['category_name']); ?>
                                                                                <span class="bd-categ-count pull-right"><?php
                                                                                // echo $sub_count;
                                                                                ?></span></h2>
                                                                        </div>

                                                            <?php if (array_key_exists(0, $row)) { ?>

                                                                            <ul class="clr">
                                                                               
                                                            <?php foreach ($row[0] as $subcat) {  ?>
                                                                                 <li>
                                                                                    <?php if($userid != '') { if (checkPermission('KnowledgeBase', 'view')) { ?>
                                                                                    <a data-href="<?= base_url() ?>Support/KnowledgeBase/view/<?= $subcat['sub_category_id'] ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"> <?php echo ucfirst($subcat['sub_category_name']); ?></a>
                                                                                    <?php } } else {  ?>
                                                                                   <a data-href="<?= base_url() ?>Support/KnowledgeBase/view/<?= $subcat['sub_category_id'] ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"> <?php echo ucfirst($subcat['sub_category_name']); ?></a>
                                                                                    <?php } ?>
                                                                                    </li>
                                                                                <?php  } ?>
                                                                            </ul>

                                                        <?php if($userid != '') { if (!empty($main_category) && count($main_category) > 0) { ?>
                                                                        <form method="post" action="<?= base_url() ?>Support/KnowledgeBase/ListofGeneralCategory">
                                                                            <input type="hidden" name="id" value="<?php echo $row['main_category_id'] ?>">
                                                                            <input type="submit" name="" class="bd-cust-link" value="<?= $this->lang->line('all') ?>">
                                                                        </form>
                                                                                <!--<a href="javascript:;" onclick="listData('<?php echo $row['main_category_id']; ?>');"><?= $this->lang->line('all') ?></a>-->
                                                        <?php } } ?>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="clr"></div>
                                                                </div>
                                                            </div>
                                                        <?php if ($count % 2 == 0) { ?> 
                                                            </div>
                                                            <?php } ?>
                                                    <?php }
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="clr"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-12 col-sm-3">
                                   
                                    <div class="bd-grey-box bd-category-box bd-articlebox-likes">
                                        <h2 class="title-2">
                                            <?= $this->lang->line('last_add_article') ?>
                                        </h2>
                                        <ul class="clr ">
                                            <?php
                                            if (!empty($article) && count($article) > 0) {
                                                foreach ($article as $row) {
                                                    ?>
                                                    <?php
                                                    if ($row["like_count"] == 0) {
                                                        $str_like = "like";
                                                    } else {
                                                        $str_like = "unlike";
                                                    }
                                                    ?>
                                                    <li>
                                                        <div class=" col-lg-8 col-xs-9 col-sm-6"> <?php echo ucfirst($row['article_name']); ?></div>
                                                       <?php if($userid != '') { ?>
                                                        <div class="col-lg-3 col-xs-3 col-sm-6 text-right  pull-right" id="article-<?php echo $row['article_id']; ?>"> 
                                                            <a href="javascript:;" onclick="addLikes('<?php echo $row['article_id'] ?>', '<?php echo $str_like; ?>');" id="like_button">
                                                                <?php if ($row["like_count"] == 0) { ?>
                                                                    <i class="bd-wishlist-ico fa fa-heart-o" id="like"></i>
                                                                <?php } else { ?>
                                                                    <i class="bd-wishlist-ico fa fa-heart" id="like"></i>
                                                                <?php } ?>
                                                            </a>
                                                            <?php
                                                            $flag = false;
                                                            foreach ($result as $dt) {
                                                                foreach ($dt as $ct) {
                                                                    if ($ct['article_id'] == $row['article_id']) {
                                                                        $articleCount = $ct['like_count'];
                                                                        $flag = true;
                                                                    }
                                                                    if ($flag == false) {
                                                                        $articleCount = 0;
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <span class="bd-catag-count label-likes" id="likecnt_<?php echo $row['article_id']; ?>"><?php echo $articleCount; ?></span> </div>
                                                       <?php } ?>
                                                        <div class="clr"></div> </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    
                                </div>
                                <div class="clr"></div>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
</div>
<div class="clr"></div>

<script type="text/javascript">
    function addLikes(id, action) {
        $.ajax({
            url: "KnowledgeBase/addlike",
            data: 'id=' + id + '&action=' + action,
            type: "POST",
            dataType: "json",
            success: function (data) {
                var likes = parseInt($('#likes-' + id).val());
                switch (action) {
                    case "like":
                        $('#article-' + id + ' #like').removeClass('fa-heart-o').addClass('fa-heart');
                        $('#article-' + id + ' #like_button').attr("onclick", "addLikes('" + id + "',\'unlike\')");

                        var newlike = data.total_like;
                        $('#likecnt_' + id).html(newlike);

                        break;
                    case "unlike":
                        $('#article-' + id + ' #like').removeClass('fa-heart').addClass('fa-heart-o');
                        $('#article-' + id + ' #like_button').attr("onclick", "addLikes('" + id + "',\'like\')");

                        var newlike = data.total_like;
                        $('#likecnt_' + id).html(newlike);

                        break;
                }
                $('#likes-' + id).val(likes);
            }
        });
    }

    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
</script> 
