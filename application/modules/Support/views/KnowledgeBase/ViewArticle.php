<div class=""> 
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row"><ul class="breadcrumb nobreadcrumb-bg">
                    <li><a href="<?php echo base_url() . 'Support'; ?>">
                            <?= lang('support') ?>
                        </a></li>
                    <li class="active"><a href="<?php echo base_url() . 'Support/KnowledgeBase'; ?>">
                            <?= lang('knowledgebase') ?>
                        </a></li> 
                    <li class="active"><a href="<?php echo base_url() . 'Support/KnowledgeBase/ListofArticle'; ?>">
                            <?= lang('knowledge_article') ?>
                        </a></li> 
                    <li class="active">
                        <?= lang('view_article') ?>
                    </li>
                </ul></div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6 col-sm-6 col-md-6 no-left-pad">
                            <h3 class="white-link">
                                <?php echo!empty($update_record[0]['article_name']) ? ucfirst($update_record[0]['article_name']) : 'N/A' ?> 
                            </h3>
                        </div>

                        <div class="clr"></div>
                    </div>
                    <div class="whitebox">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div>
                                <div class="row">
                                    <div class="pad_tb20 col-xs-12 bd-content">
                                        <p>                   
                                            <?php echo!empty($update_record[0]['article_description']) ? $update_record[0]['article_description'] : 'N/A' ?>  
                                        </p>

                                        <ul class="files">
                                            <?php
                                            if (isset($image_data) && count($image_data) > 0) {

                                                foreach ($image_data as $data) {
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
                                                } 
                                            ?>

                                        </ul>  

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
</div>
