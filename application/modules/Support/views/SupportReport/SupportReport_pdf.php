<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,  user-scalable=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Support Report</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="<?php echo base_url(); ?>uploads/reports/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="<?php echo base_url(); ?>uploads/reports/assets/css/custom-style.css" rel="stylesheet" />
        <!-- GOOGLE FONTS -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />
        <style></style>
    </head>
    <body style="font-family:Arial,verdana, tahoma;font-size:12px;line-height:20px">
        <div style="margin-bottom: 20px">
            <div style="float: left">
                <img src="<?php echo base_url(); ?>uploads/images/logo.png">
            </div>
            <div style="clear: both"></div>
        </div>
        <?php foreach ($supports_list as $support_report_data) { ?>
            <div class="main-container">
                <div style="width: 100%; margin-bottom: 20px">
                    <div style="float: left; width: 50%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('ticket_id'); ?> :</span> <span style="font-size: 14px"><?php echo $support_report_data->ticket_id; ?></span>
                    </div>
                    <div style="float: right; width: 50%">
                        <div>
                            <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('create_date'); ?> :</span> <span style="font-size: 14px"><?php echo $support_report_data->created_date; ?></span>
                        </div>
                        <div>
                            <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('due_date'); ?> :</span> <span style="font-size: 14px"><?php echo $support_report_data->due_date; ?></span>
                        </div>
                    </div>
                    <div style="clear: both"></div>
                </div>   
                <div style="width: 100%; margin-bottom: 30px ; ">
                    <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('ticket_sub'); ?> :</span> <span style="font-size:16px; font-weight: bold;"><?php echo $support_report_data->ticket_subject; ?></span>
                </div>

                <div>
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('ticket_desc'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->ticket_desc); ?></span>
                    </div> 
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('status'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->status_name); ?></span>
                    </div> 
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('type'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->type); ?></span>
                    </div> 
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('ass_to'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->fname); ?>&nbsp;<?php echo ucfirst($support_report_data->lname); ?></span>
                    </div> 
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('assign_by'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->firstname); ?>&nbsp;<?php echo ucfirst($support_report_data->lastname); ?></span>
                    </div> 
                    <div style="width: 100%">
                        <span style="font-size:16px; font-weight: bold; padding-right: 10px"><?php echo lang('ticket_holder'); ?> :</span> <span style="font-size: 14px"><?php echo ucfirst($support_report_data->prospect_name); ?></span>
                    </div> 

                </div>
            </div>
        <?php } ?>

        <img src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName.png" ?>'>

    </body>
</html>