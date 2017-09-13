<!--<style type="text/css">
    table.print-friendly {
        width:450px;
        margin-left:450px;
        padding-top:-70px;
    }
    table.print-friendly div{
        page-break-inside: avoid !important;
        border:1px solid #DDD !important;
        width:450px;
    }
    table.print-friendly div td, table.print-friendly div th{
        page-break-inside: avoid !important;
        border:1px solid #DDD !important;
    }
    @page
    {
        height: 11.69in;

    }
    table tr td{ font-size:12px !important; line-height:20px !important;}
</style>
-->
<?php ob_start(); ?>

<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td style=" font-size:12px;line-height:20px;background-color:#1c476d; display: table-cell; padding:5px; margin-top:15px;color:#ffffff;">
            <b><?php echo lang('credit_Report_plus'); ?></b></td>
    </tr>
</table>

<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px;  display: table-cell;font-weight:bold;"> <?php echo lang('report_date'); ?></td>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell;"><?php echo date('Y-m-d'); ?></td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('company'); ?></td>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['name']) && $contact_info['name'] != "") { ?>
                <?php echo $contact_info['name']; ?>
            <?php } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('addresss'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px; display: table-cell;">
            <?php if (isset($contact_info['address1']) && $contact_info['address1'] != "") { ?>
                <?php echo $contact_info['address1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['zipcode1']) && $contact_info['zipcode1'] != "") { ?>
                <?php echo $contact_info['zipcode1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['city1']) && $contact_info['city1'] != "") { ?>
                <?php echo $contact_info['city1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['country1']) && $contact_info['country1'] != "") { ?>
                <?php echo $contact_info['country1']; ?>
            <?php } ?></td>
    </tr>
</table>
<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td style="font-size:14px;line-height:20px;background-color:#1c476d; padding:5px; display: table-cell; color:#ffffff;">
            <b><?php echo lang('resume'); ?></b></td>
    </tr>
</table>

<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('company'); ?></td>
        <td width="450px"
            style="font-size:12px;line-height:20px;font-weight:bold; display: table-cell;"><?php if (isset($contact_info['name']) && $contact_info['name'] != "") { ?>
                <?php echo $contact_info['name']; ?>
            <?php } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('business_address'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px; display: table-cell;">
            <?php if (isset($contact_info['address1']) && $contact_info['address1'] != "") { ?>
                <?php echo $contact_info['address1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['zipcode1']) && $contact_info['zipcode1'] != "") { ?>
                <?php echo $contact_info['zipcode1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['city1']) && $contact_info['city1'] != "") { ?>
                <?php echo $contact_info['city1'] . ','; ?>
            <?php } ?>
            <?php if (isset($contact_info['country1']) && $contact_info['country1'] != "") { ?>
                <?php echo $contact_info['country1']; ?>
            <?php } ?></td>
    </tr>
</table>
<table width="900px" cellpadding="0" cellspacing="0"
       style="margin-bottom: 20px;border-bottom:3px solid #1c476d; border-top:3px solid #1c476d; background: #ecf4f9;">
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('credit_advice'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($review_data_list['limit_advice']) && $review_data_list['limit_advice'] != "") { ?>
                <?php echo $review_data_list['limit_advice']; ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('Score'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($review_data_list['score']) && $review_data_list['score'] != "") {
                echo $review_data_list['score'];
            } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('Payment_score'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($payments['score']) && $payments['score'] != "") {
                echo $payments['score'];
            } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('Rating'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($review_data_list['rating']) && $review_data_list['rating'] != "") {
                echo $review_data_list['rating'];
            } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('Risk'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($review_data_list['risk']) && $review_data_list['risk'] != "") {
                echo $review_data_list['risk'];
            } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold;text-align:left; display: table-cell;"><?php echo lang('Company_Status'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px;text-align:left; display: table-cell;">
            <?php if (isset($registration['active']) && $registration['active'] != "") {
                if ($registration['active'] == true) {
                    echo 'Active';
                } else {
                    echo 'Inactive';
                }
                ?>
            <?php } ?></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 20px; padding:5px;">
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('Legal'); ?></td>
        <td width="450px" style="font-size:12px;line-height:20px; display: table-cell;">
            <?php if (isset($registration['legal_current']) && $registration['legal_current'] != "") {
                echo $registration['legal_current'];
            } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('REGISTRATION_HEADER_MENU_LABEL'); ?></td>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['reg_number']) && $registration['reg_number'] != "") {
                echo $registration['reg_number'];
            } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style="font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('Tax_ID_RSIN'); ?></td>
        <td width="450px"
            style="font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['vat_number']) && $registration['vat_number'] != "") { ?>
                <?php echo $registration['vat_number']; ?>
            <?php } ?></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 20px;border-spacing:0">
    <thead>
    <tr>
        <th style="text-align:left;border-bottom:3px solid #1c476d; display: table-cell; border-top:3px solid #1c476d;background: #ecf4f9; padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Year'); ?></th>
        <?php
        $tmpFlag = 1;
        foreach ($balance_sheets as $balance) {
            ?>
            <th style="border-bottom:3px solid #1c476d; display: table-cell; border-top:3px solid #1c476d;text-align:left;background: #ecf4f9; padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo $balance['balance_year']; ?></th>
            <th style="border-bottom:3px solid #1c476d; display: table-cell; border-top:3px solid #1c476d;text-align:left;background: #ecf4f9; padding:5px; font-size:12px;line-height:20px; font-weight:bold;"></th>
            <th style="border-bottom:3px solid #1c476d; display: table-cell; border-top:3px solid #1c476d;text-align:left;background: #ecf4f9; padding:5px; font-size:12px;line-height:20px; font-weight:bold;"> <?php if ($tmpFlag != count($balance_sheets)) { ?>
                    <?php echo lang('Mutation'); ?>
                <?php } ?>
            </th>
            <?php $tmpFlag++;
        } ?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px;  display: table-cell;"><?php echo lang('fixed_assets'); ?></td>
        <?php
        $negativeNumber = -15;
        $positiveNumber = 15;
        $fixedNegativeFloatValue = number_format((float)$negativeNumber, 2, '.', '');
        $fixedPositiveValue = number_format((float)$positiveNumber, 2, '.', '');
        ?>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['balance_assets_fix_total']; ?></td>
            <?php if (isset($balance['difference'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['difference'], 2, '.', '');
                    ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['difference']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo lang('total_receivables'); ?></td>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['balance_assets_cur_receivable_total']; ?></td>
            <?php if (isset($balance['assets_cur_receivable_difference'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['assets_cur_receivable_difference'], 2, '.', '');

                    ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['assets_cur_receivable_difference']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo lang('total_equity'); ?></td>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['balance_liabilities_capandres_total']; ?></td>
            <?php if (isset($balance['CapandresTotal'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['CapandresTotal'], 2, '.', ''); ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['CapandresTotal']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; "><?php echo lang('current_liabilities') ?></td>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['balance_liabilities_st_total']; ?></td>
            <?php if (isset($balance['StTotal'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['StTotal'], 2, '.', ''); ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['StTotal']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo lang('Working_capital'); ?></td>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['working_capital']; ?></td>
            <?php if (isset($balance['WorkingCapitalDifference'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['WorkingCapitalDifference'], 2, '.', ''); ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['WorkingCapitalDifference']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    <tr>
        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; "><?php echo lang('Quick_ratio'); ?></td>
        <?php foreach ($balance_sheets as $balance) { ?>
            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;">
                € <?php echo $balance['quick_ratio']; ?></td>
            <?php if (isset($balance['QuickRatioTotal'])) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php
                    $differenceFloatValue = number_format((float)$balance['QuickRatioTotal'], 2, '.', ''); ?>
                    <?php if ($differenceFloatValue > $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down-right.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedNegativeFloatValue && $differenceFloatValue < 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/down.png" ?>'>
                    <?php } elseif ($differenceFloatValue > $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up.png" ?>'>
                    <?php } elseif ($differenceFloatValue < $fixedPositiveValue && $differenceFloatValue > 0) { ?>
                        <img style="width:20px;" src='<?php echo base_url() . "/uploads/images/up-right.png" ?>'>
                    <?php } ?></td>
                <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $balance['QuickRatioTotal']; ?></td>
            <?php } ?>
        <?php } ?>
    </tr>
    </tbody>
</table>
<pagebreak />
<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px; text-align: center;">
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td width="100%" style="text-center;"><img style="width:600px !important; margin: 0 auto; display: block;"
                              src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName_equity.png" ?>'>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
</table>
<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px; color:#ffffff; display: table-cell;">
            <b><?php echo lang('Contact_Information'); ?></b></td>
    </tr>
    <tr>

        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('company'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['name']) && $contact_info['name'] != "") { ?>
                            <?php echo $contact_info['name']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; font-weight:bold; display: table-cell; padding-bottom:20px;"><?php echo lang('tradenames'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;padding-bottom:20px;"><?php if (isset($contact_info['name']) && $contact_info['name'] != "") { ?>
                            <?php echo $contact_info['name']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px;padding-bottom:10px;padding-top:10px; font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('business_address'); ?></td>
                    <td width="450px"
                        style="padding-bottom:10px;padding-top:10px;  font-size:12px;line-height:20px; display: table-cell;">
                        <?php if (isset($contact_info['address']) && $contact_info['address'] != ""){ ?>
                        <table>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"><?php echo $contact_info['address'] . ','; ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"><?php if (isset($contact_info['zipcode']) && $contact_info['zipcode'] != "") { ?>
                                        <?php echo $contact_info['zipcode'] . ','; ?>
                                    <?php } ?>
                                    <?php if (isset($contact_info['city']) && $contact_info['city'] != "") { ?>
                                        <?php echo $contact_info['city'] . ','; ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"> <?php if (isset($contact_info['country']) && $contact_info['country'] != "") { ?>
                                        <?php echo $contact_info['country']; ?>
                                    <?php } ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; padding-bottom:20px; display: table-cell; font-weight:bold;"><?php echo lang('Mailing_address'); ?></td>
                    <td width="450px"
                        style="  font-size:12px;line-height:20px; display: table-cell; padding-bottom:20px;"><?php if (isset($contact_info['address1']) && $contact_info['address1'] != ""){ ?>
                        <table>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"><?php echo $contact_info['address1'] . ','; ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"> <?php if (isset($contact_info['zipcode1']) && $contact_info['zipcode1'] != "") { ?>
                                        <?php echo $contact_info['zipcode1'] . ','; ?>
                                    <?php } ?>
                                    <?php if (isset($contact_info['city1']) && $contact_info['city1'] != "") { ?>
                                        <?php echo $contact_info['city1'] . ','; ?>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td style=" font-size:12px;line-height:20px;"> <?php if (isset($contact_info['country1']) && $contact_info['country1'] != "") { ?>
                                        <?php echo $contact_info['country1']; ?>
                                    <?php } ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('phone_no'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['tel_number']) && $contact_info['tel_number'] != "") { ?>
                            <?php echo $contact_info['tel_number']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Fax_number'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['fax_number']) && $contact_info['fax_number'] != "") { ?>
                            <?php echo $contact_info['fax_number']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('COMMON_LABEL_EMAIL'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['mail']) && $contact_info['mail'] != "") { ?>
                            <?php echo $contact_info['mail']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;  display: table-cell;font-weight:bold;"><?php echo lang('website'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($contact_info['website']) && $contact_info['website'] != "") { ?>
                            <?php echo $contact_info['website']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>

</table>
<pagebreak />
<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d;padding:5px; display: table-cell; color:#ffffff;">
            <b><?php echo lang('REGISTRATION_HEADER_MENU_LABEL'); ?> </b></td>
    </tr>
    <tr>
        <td style="display: table-cell;">
            <table width="100%" style="margin-bottom: 20px;">
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Registration_Number'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['reg_type']) && $registration['reg_type'] != "") { ?>
                            <?php echo $registration['reg_type']; ?>
                        <?php } ?>
                        <?php if (isset($registration['reg_number']) && $registration['reg_number'] != "") { ?>
                            <?php echo ':' . $registration['reg_number']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Establishment_number'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['branch_number']) && $registration['branch_number'] != "") { ?>
                            <?php echo $registration['branch_number']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Tax_ID_RSIN'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['vat_number']) && $registration['vat_number'] != "") { ?>
                            <?php echo $registration['vat_number']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style="padding:5px; font-size:12px;line-height:20px; display: table-cell; padding-top:20px; font-weight:bold;"><?php echo lang('Company_Status'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;padding-top:20px;"><?php if (isset($registration['active']) && $registration['active'] != "") {
                            if ($registration['active'] == true) {
                                echo 'Active';
                            } else {
                                echo 'Inactive';
                            }
                            ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('First_enrollment_trade'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['registration']) && $registration['registration'] != "") { ?>
                            <?php echo $registration['registration']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;  display: table-cell;font-weight:bold;"><?php echo lang('Memorandum'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['memorandum']) && $registration['memorandum'] != "") { ?>
                            <?php echo $registration['memorandum']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell; font-weight:bold;"><?php echo lang('Date_of_settlement'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell;"><?php if (isset($registration['settlement']) && $registration['settlement'] != "") { ?>
                            <?php echo $registration['settlement']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Legal'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['legal_current']) && $registration['legal_current'] != "") { ?>
                            <?php echo $registration['legal_current']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell; font-weight:bold;"><?php echo lang('Last_amendment'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell;"><?php if (isset($registration['modification']) && $registration['modification'] != "") { ?>
                            <?php echo $registration['modification']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;  display: table-cell; font-weight:bold;"><?php echo lang('Published'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;   display: table-cell;"><?php if (isset($registration['paidup']) && $registration['paidup'] != "") { ?>
                            <?php echo $registration['paidup']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('landfilled'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($registration['issued']) && $registration['issued'] != "") { ?>
                            <?php echo $registration['issued']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px;  display: table-cell;color:#ffffff;">
            <b><?php echo lang('activities'); ?></b></td>
    </tr>
    <tr>
        <td>
            <table width="100%" style="margin-bottom: 20px;">
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; padding-bottom:20px; font-weight:bold;">
                        SBI
                    </td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;padding-bottom:20px;"><?php foreach ($activities as $activitie) {
                            ?>
                            <?php echo $activitie['text'] . '(' . $activitie['code'] . ')<br/>' ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Exporter'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($activitie_data['exporter']) && $activitie_data['exporter'] == false) {
                            echo 'No';
                        } else {
                            echo 'Yes';
                        } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell; font-weight:bold;"><?php echo lang('Importer'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell;"><?php if (isset($activitie_data['importer']) && $activitie_data['importer'] == false) {
                            echo 'No';
                        } else {
                            echo 'Yes';

                        } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell; font-weight:bold;"><?php echo lang('Branch_Organisations'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px;padding-bottom:20px; display: table-cell;"><?php if (isset($activitie_data['branche_organisations']) && $activitie_data['branche_organisations'] != "") { ?>
                            <?php echo $activitie_data['branche_organisations']; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td width="450px"
                        style=" padding:5px;vertical-align:top; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('target'); ?></td>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($activitie_data['goal']) && $activitie_data['goal'] != "") { ?>
                            <?php echo $activitie_data['goal']; ?>
                        <?php } ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="900px" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px; display: table-cell; color:#ffffff;">
            <b><?php echo lang('Business_Connections'); ?></b>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%"  style="margin-bottom: 20px;">
                <tr>
                    <td width="450px"
                        style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Companies_in_the_same_address'); ?></td>
                    <?php if (!empty($relations)) { ?>
                        <?php foreach ($relations as $relations_data) { ?>
                            <td width="450px"
                                style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $relations_data['relations_name'] . '<br/>' ?><?php echo 'Registration:' . $relations_data['reg_number'] ?></td>
                        <?php }
                    } ?>
                </tr>
            </table>
        </td>
    </tr>
</table>

<pagebreak />

<!-- Management-->

<table width="900px" cellpadding="0" cellspacing="0">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; display:table-cell; padding:5px; color:#ffffff;">
            <b><?php echo lang('Management'); ?></b></td>
    </tr>
</table>

<?php if (!empty($management)) { ?>
    <table  class="sample-text" style="margin-bottom:20px; width:900px;padding:5px; font-size:12px;line-height:20px; display: block;">
        <tr>
            <td width="450px"
                style="vertical-align:top; padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Managers_in_office'); ?></td>
        </tr>
   </table>
              <table border="0" cellpadding="0" cellspacing="0" width="450" class="print-friendly" style=" width:450px; margin-left:450px;padding-top:-70px;">
        <?php
        $i = 1;
        foreach ($management as $managements) {
        $data = $i%5;
        if($data == 0)
        {?>

        <?php }
            if (isset($managements['name'])) {
                ?>
                <?php if (isset($managements['name']) && $managements['name'] != "") { ?>
                    <tr>
                        <td style="font-weight:bold; font-size:12px;line-height:20px;">
                             <?php echo $managements['name'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['address']) && $managements['address'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['address'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['zipcode']) && $managements['zipcode'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['zipcode']; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['city']) && $managements['city'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['city'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['country']) && $managements['country'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['country'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['reg_number']) && $managements['reg_number'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo 'Registration Number:' . $managements['reg_number'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['qualification']) && $managements['qualification'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo 'Competence:' . $managements['qualification'] . '<br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                <td style="font-size:12px;line-height:20px;">
                <?php if (isset($managements['function']) && $managements['function'] != ""){ ?>
                    <?php echo 'Function:' . $managements['function'] . '<br/>'; ?>
                    </td>
                    </tr>
                <?php } ?>
                <?php if (isset($managements['date_started']) && $managements['date_started'] != "") { ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo 'start date:' . $managements['date_started'] . '<br/><br/><br/>'; ?>
                        </td>
                    </tr>
                <?php } ?>

                <!-- management in initials-->

            <?php }elseif(isset($managements['initials']) && $managements['initials'] != ""){?>
                <?php if(isset($managements['initials']) && $managements['initials'] != ""){  ?>
                    <tr>
                        <td style="font-weight:bold; font-size:12px;line-height:20px;padding-top: 15px;">
                            <?php echo 'Initials:'.$managements['initials'].'<br/>';?>
                        </td>
                    </tr>
                <?php }?>
                <?php if(isset($managements['fullname']) && $managements['fullname'] != ""){ ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo 'Full Name:'.$managements['fullname'].'<br/>';?>
                        </td>
                    </tr>
                <?php }?>
                <?php if(isset($managements['city']) && $managements['city'] != ""){ ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['city'].'<br/>';?>
                        </td>
                    </tr>
                <?php }?>
                <?php if(isset($managements['country']) && $managements['country'] != ""){ ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo $managements['country'].'<br/>';?>
                        </td>
                    </tr>
                <?php }?>
                <?php if(isset($managements['qualification']) && $managements['qualification'] != ""){ ?>         <tr>
                    <td style="font-size:12px;line-height:20px;">
                        <?php echo 'Competence:'.$managements['qualification'].'<br/>';?>
                    </td>
                </tr>
                <?php }?>
                <?php if(isset($managements['function']) && $managements['function'] != ""){ ?>
                    <tr>
                        <td style="font-size:12px;line-height:20px;">
                            <?php echo 'Function:'.$managements['function'].'<br/>';?>
                        </td>
                    </tr>
                <?php }?>
                <?php if(isset($managements['date_started']) && $managements['date_started'] != ""){ ?>             <tr>
                    <td style="font-size:12px;line-height:20px;">
                        <?php echo 'start date:'.$managements['date_started'];?>                         </td>
                </tr>
                <?php }?>
                <?php if(isset($managements['involvements']) && $managements['involvements'] != ""){
                    foreach($managements['involvements'] as $involvements){?>
                        <tr>
                            <td style="font-size:12px;line-height:20px; padding-left: 30px; ">
                                <table>
                                    <?php if (isset($involvements->name) && $involvements->name != "") { ?>
                                        <tr>
                                            <td style="font-weight:bold;padding-top: 15px; font-size:12px;line-height:16px;">            <?php echo $involvements->name; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->address) && $involvements->address != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo $involvements->address; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->zipcode) && $involvements->zipcode != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo $involvements->zipcode; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->city) && $involvements->city != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo $involvements->city . '<br/>'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->country) && $involvements->country != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo $involvements->country . '<br/>'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->reg_number) && $involvements->reg_number != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo 'Registration Number:' . $involvements->reg_number . '<br/>'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (isset($involvements->function) && $involvements->function != ""){ ?>
                                    <tr>
                                        <td style="font-size:12px;line-height:16px;">
                                            <?php echo 'Function:' . $involvements->function . '<br/>'; ?>                            </td>
                                    </tr>
                                <?php } ?>
                                    <?php if (isset($involvements->date_started) && $involvements->date_started != "") { ?>
                                        <tr>
                                            <td style="font-size:12px;line-height:16px;">
                                                <?php echo 'start date:' . $involvements->date_started . '<br/><br/><br/>'; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </table>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

            <?php }}?>

                </div>
    </table>

<?php } ?>

<!--end Management-->
<pagebreak />
<!-- Employees-->
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px; color:#ffffff;">
            <b><?php echo lang('Employees'); ?></b></td>
    </tr>
    <?php if (!empty($employees)) { ?>
        <tr>
            <td>
                <table width="100%" style="margin-bottom:20px;">
                    <tr>
                        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Year'); ?></td>
                        <?php foreach ($employees as $employee) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $employee['year'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('total'); ?></td>
                        <?php foreach ($employees as $employee) { ?>
                            <td style=" padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $employee['total'] ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
</table>
<table width="900px" style="text-align: center;">
    <tr>
        <td width="100%" style="text-align: center; padding-top: 30px;"><img style="width:600px !important; margin: 0 auto; display: block;" src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName.png" ?>'>
        </td>
    </tr>
</table>
<!-- end Employees-->

<!-- payments-->
<table>
    <tr>
        <td style="font-size:12px;line-height:20px; background-color:#1c476d; padding:5px; color:#ffffff;">
            <b><?php echo lang('Payments'); ?></b></td>
    </tr>
    <?php if (!empty($payments)) { ?>
        <tr>
            <td>
                <table width="100%" style="margin-bottom:20px;">
                    <tr>
                        <td style="width:450px;padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Score'); ?></td>
                        <td style="width:450px;padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($payments['score']) && $payments['score'] != "") {
                                echo $payments['score'];
                            } ?></td>
                    </tr>
                    <tr>
                        <td style="width:450px;padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('EST_LABEL_DESCRIPTION'); ?></td>
                        <td style="width:450px;padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php if (isset($payments['description']) && $payments['description'] != "") {
                                echo $payments['description'];
                            } ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
</table>


<!-- end payment-->

<!--key figures -->
<pagebreak />
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px; background-color:#1c476d; padding:5px; color:#ffffff;">
            <b><?php echo lang('key_figures'); ?></b></td>
    </tr>
    <?php if (!empty($key_figures)) { ?>
        <tr>
            <td>
                <table width="100%" style="margin-bottom: 20px;">
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold; display: table-cell;"><?php echo lang('Year'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $key_figure['year'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px;  display: table-cell;font-weight:bold;"><?php echo lang('Quick_ratio'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $key_figure['quick_ratio'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Current ratio'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell;"><?php echo $key_figure['current_ratio'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; display: table-cell; font-weight:bold;"><?php echo lang('Working_capital_total_assets'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['working_capital_div_assets'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Equity_total_assets'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['capandres_div_assets'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Equity_assets'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['capandres_div_assets_fixed']; ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Equity_debt'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['capandres_div_liabilities_debts'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Balance_sheet_debt'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['solvency'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Working_capital'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['working_capital'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Equity'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['capandres'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style=" padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Change_in_equity'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['capandres_mutation'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Change_in_current_liabilities'); ?></td>
                        <?php foreach ($key_figures as $key_figure) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $key_figure['liabilities_st_mutation'] ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
</table>
<!-- resume 1-->
<table width="900px" style="margin-bottom: 20px;">
    <tr>
        <td style="width:250px;vertical-align:top;font-size:14px; padding:5px;line-height:20px; font-weight:bold;display:table-cell;"><?php echo lang('resume'); ?></td>
        <td style="width:750px;display:table-cell;padding:5px; font-size:13px;line-height:20px;">
            <?php if (isset($key_figures_text) && $key_figures_text != "") { ?>
                <?php echo $key_figures_text; ?>
            <?php } ?></td>
    </tr>
</table>
<pagebreak />
<table width="900px" style="text-align: center;">
    <tr>
        <td width="100%" style="text-align: center; padding-top: 40px; padding-bottom: 40px;"><img style="width:600px !important; display: block; margin: 0 auto;"
                              src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName_key_figures.png" ?>'>
        </td>
    </tr>
    <tr>
        <td width="100%" style="text-align: center; padding-top: 90px;"><img style="width:600px !important;display: block; margin: 0 auto;"
                              src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName_ratio.png" ?>'>
        </td>
    </tr>
</table>
<!-- end key figures-->
<pagebreak />
<!--Financial statements-->
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px; color:#ffffff;display:table-cell;">
            <b> <?php echo lang('Financial_statements'); ?> </b></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Latest_Financial_Statements'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($annual_report['annual_last']) && $annual_report['annual_last'] != "") { ?>
                <?php echo $annual_report['annual_last']; ?>
            <?php } ?>
        </td>

    </tr>
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Notice_accounts'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($annual_report['remark']) && $annual_report['remark'] != "") { ?>
                <?php echo $annual_report['remark']; ?>
            <?php } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('species_accounts'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($annual_report['annual_type']) && $annual_report['annual_type'] != "") { ?>
                <?php echo $annual_report['annual_type']; ?>
            <?php } ?></td>
    </tr>
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('financial_statements'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($annual_report['name']) && $annual_report['name'] != "") { ?>
                <?php echo $annual_report['name'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($annual_report['address']) && $annual_report['address'] != "") { ?>
                <?php echo $annual_report['address'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($annual_report['zipcode']) && $annual_report['zipcode'] != "") { ?>
                <?php echo $annual_report['zipcode'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($annual_report['city']) && $annual_report['city'] != "") { ?>
                <?php echo $annual_report['city'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($annual_report['reg_number']) && $annual_report['reg_number'] != "") { ?>
                <?php echo 'Registration Number' . $annual_report['reg_number'] . '<br/>'; ?>
            <?php } ?></td>
    </tr>
</table>


<!-- end Financial statements-->

<!--balance sheet-->
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px; background-color:#1c476d; padding:5px;display:table-cell; color:#ffffff;">
            <b><?php echo lang('Balance_Sheet'); ?></b></td>
    </tr>
    <?php if (!empty($balance_sheets)) { ?>
        <tr>
            <td>
                <table width="100%" style="margin-bottom: 20px;">
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Year'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_year'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; display:table-cell;font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Date_Closed'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_date'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Tangible_assets'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_assets_fix_tangible_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Fixed_assets'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_assets_fix_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Total_Inventories'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_assets_cur_stocks'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Total_Receivables'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_assets_cur_receivable_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Liquid_Assets'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px; display:table-cell;font-size:12px;line-height:20px;"><?php echo $result['balance_assets_cur_liquid'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Current_Asset'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px; display:table-cell;font-size:12px;line-height:20px;"><?php echo $result['balance_assets_cur_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Total_Assets'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_assets_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Issued_Capital'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_capandres_issued_capital'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style=" padding:5px; display:table-cell;font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Other_Reserves'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_capandres_reserves_other'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"> <?php echo lang('Total_reserves'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_capandres_reserves_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"> <?php echo lang('Total_Equity'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_capandres_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Current_Liabilities'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_st_total'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px; display:table-cell;font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Total_Debt'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_debts'] ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Total_Liabilities'); ?></td>
                        <?php foreach ($balance_sheets as $result) { ?>
                            <td style="padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php echo $result['balance_liabilities_total'] ?></td>
                        <?php } ?>
                    </tr>
                </table>
            </td>
        </tr>
    <?php } ?>
</table>
<!-- resume 2-->
<table width="900px" style="margin-bottom: 20px;">
    <tr>
        <td style="width:250px;display:table-cell;vertical-align:top; padding:5px; font-size:14px;line-height:20px; font-weight:bold;"><?php echo lang('resume'); ?></td>
        <td style="width:750px;display:table-cell; font-size:13px;line-height:20px;">
            <?php if (isset($balance_sheets_text) && $balance_sheets_text != "") { ?>
                <?php echo $balance_sheets_text; ?>
            <?php } ?></td>
    </tr>
</table>
<pagebreak />
<table width="900px">
    <tr>
        <td width="100%" style="text-align: center; padding-top: 40px; padding-bottom: 40px;"><img style="width:600px !important; display: block; margin: 0 auto;"
                              src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName_balance_sheet.png" ?>'>
        </td>
    </tr>
    <tr>
        <td width="100%" style="text-align: center;padding-top: 90px; "><img style="width:600px !important; display: block; margin: 0 auto;"
                              src='<?php echo base_url() . "/uploads/mediagenerated/inrep_$chartName_total_balance_sheet.png" ?>'>
        </td>
    </tr>
</table>
<!-- industry Analysis -->

<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d; padding:5px;display:table-cell; color:#ffffff;">
            <b> <?php echo lang('Industry_Analysis') ?> </b></td>
    </tr>
</table>

<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style=" padding:5px; font-size:12px;line-height:20px;display:table-cell; font-weight:bold;"><?php echo lang('Industry_SBI'); ?></td>
        <td width="450px" style=" padding:5px; font-size:12px;line-height:20px;display:table-cell;">
            <?php if (isset($branche['sbi_description']) && $branche['sbi_description'] != "") { ?>
                <?php echo $branche['sbi_description']; ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Region'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($branche['region']) && $branche['region'] != "") { ?>
                <?php echo $branche['region']; ?>
            <?php } ?>
            <?php if (isset($branche['companies_sbi']) && $branche['companies_sbi'] != "") { ?>
                <?php echo $branche['companies_sbi'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($branche['companies_sbi_region']) && $branche['companies_sbi_region'] != "") { ?>
                <?php echo $branche['companies_sbi_region'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($branche['insolvencies_sbi']) && $branche['insolvencies_sbi'] != "") { ?>
                <?php echo $branche['insolvencies_sbi'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($branche['insolvencies_sbi_region']) && $branche['insolvencies_sbi_region'] != "") { ?>
                <?php echo $branche['insolvencies_sbi_region'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($branche['risk_sbi']) && $branche['risk_sbi'] != "") { ?>
                <?php echo $branche['risk_sbi'] . '<br/>'; ?>
            <?php } ?>
            <?php if (isset($branche['risk_sbi_region']) && $branche['risk_sbi_region'] != "") { ?>
                <?php echo $branche['risk_sbi_region'] . '<br/>'; ?>
            <?php } ?></td>
    </tr>
</table>
<!-- Publications -->
<pagebreak />
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d;display:table-cell; padding:5px; color:#ffffff;">
            <b> <?php echo lang('Publications'); ?> </b></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="450px" style=" padding:5px; vertical-align:top; font-size:12px;line-height:20px;display:table-cell; font-weight:bold;"><?php echo lang('Latest_filings'); ?></td>
        <td width="450px" style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($deposits) && $deposits != "") {
                foreach ($deposits as $deposit) {
                    echo $deposit . '<br/>';
                }
                ?>
            <?php } ?>
        </td>
    </tr>
</table>

<table class="sample-text" style="margin-bottom:20px; width:900px;padding:5px; font-size:12px;line-height:20px; display: block;">
    <tr>
        <td width="450"
            style="vertical-align:top; padding:5px; font-size:12px;line-height:20px; font-weight:bold;">
            <?php echo lang('functions'); ?>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="450" class="print-friendly" style=" width:450px;margin-left:450px;padding-top:-70px;">
    <?php if (isset($functions) && $functions != "") {
            foreach ($functions as $function) {?>
    <tr>
        <td style="font-size:12px;line-height:16px;">
            <?php echo $function . '<br/>';?>
        </td>
    </tr>
        <?php }} ?>
</table>

<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style=" padding:5px;display:table-cell;vertical-align:top; font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('modifications'); ?></td>
        <td width="450px"
            style=" padding:5px;display:table-cell; font-size:12px;line-height:20px;"><?php if (isset($modifications) && $modifications != "") {
                foreach ($modifications as $modification) {
                    echo $modification . '<br/>';
                }
                ?>
            <?php } ?>
        </td>
    </tr>
</table>
<pagebreak />
<table width="900px">
    <tr>
        <td style="font-size:12px;line-height:20px;background-color:#1c476d;display:table-cell; padding:5px; color:#ffffff;">
            <b> <?php echo lang('Company_Structure'); ?> </b></td>
    </tr>
</table>
<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="450px"
            style=" padding:5px; display:table-cell;font-size:12px;line-height:20px; font-weight:bold;"><?php echo lang('Business_Connections'); ?></td>
        <td width="450px" style=" padding:5px; display:table-cell;font-size:12px;line-height:20px;"></td>
    </tr>
</table>
<table width="900px" style="margin-bottom: 20px;">
    <tr>
        <?php if (!empty($relations)) { ?>
            <?php foreach ($relations as $relations_data) { ?>
                <td style=" padding:5px; font-size:12px;line-height:20px;display:table-cell;">
                    <table>
                        <tr>
                            <td style="font-size:12px;line-height:20px;display:table-cell;">
                                <b><?php echo $relations_data['relations_name'] . '<br/>' ?></b></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;line-height:20px;display:table-cell;"><?php echo $relations_data['address'] . '<br/>' ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;line-height:20px;display:table-cell;"><?php echo $relations_data['zipcode'] . '<br/>' ?><?php echo $relations_data['city'] . '<br/>' ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;line-height:20px;display:table-cell;"><?php echo $relations_data['country'] . '<br/>' ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:12px;line-height:20px;display:table-cell;"><?php echo 'Registration:' . $relations_data['reg_number'] ?></td>
                        </tr>
                    </table>
                </td>
            <?php }
        } ?>
    </tr>
</table>
<?php if (isset($contact_info) && $contact_info != "") { ?>
    <table width="900px">
        <tr>
            <td>
                <ul>
                    <li><b>
                            <?php if (isset($contact_info['name']) && $contact_info['name'] != "") { ?>
                                <?php echo $contact_info['name'] . '<br>'; ?>
                            <?php } ?>
                        </b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td style="padding-left:10px;"><?php if (isset($contact_info['address']) && $contact_info['address'] != "") { ?>
                    <?php echo $contact_info['address'] . '<br>'; ?>
                <?php } ?></td>
        </tr>
        <tr>
            <td style="padding-left:10px;"><?php if (isset($contact_info['zipcode']) && $contact_info['zipcode'] != "") { ?>
                    <?php echo $contact_info['zipcode'] . '<br>'; ?>
                <?php } ?>
                <?php if (isset($contact_info['city']) && $contact_info['city'] != "") { ?>
                    <?php echo $contact_info['city'] . '<br>'; ?>
                <?php } ?></td>
        </tr>
        <tr>
            <td style="padding-left:10px;"><?php if (isset($contact_info['country']) && $contact_info['country'] != "") { ?>
                    <?php echo $contact_info['country'] . '<br>'; ?>
                <?php } ?></td>
        </tr>
        <tr>
            <td style="padding-left:10px;"><?php if (isset($registration['reg_number']) && $registration['reg_number'] != "") { ?>
                    <?php echo 'Registration:' . $registration['reg_number'] . '<br>' ?>
                <?php } ?></td>
        </tr>
    </table>
<?php } ?>
<table
    style="margin-bottom: 20px;font-size:12px;line-height:20px;border:2px solid #1c476d; padding: 5px; background: #ecf4f9; width:900px;">
    <thead>
    <th style="text-align: left;"><?php echo lang('Description_Score'); ?></th>
    </thead>
    <tbody>
    <tr>
        <td>1 = Insolvent / Activities discontinued</td>
    </tr>
    <tr>
        <td>2 = Not enough data available</td>
    </tr>
    <tr>
        <td>3 = Very high risk</td>
    </tr>
    <tr>
        <td>4 = high risk</td>
    </tr>
    <tr>
        <td> 5 = Increased risk</td>
    </tr>
    <tr>
        <td> 6 = Medium Risk</td>
    </tr>
    <tr>
        <td> 7 = Reduced risk</td>
    </tr>
    <tr>
        <td>8 = Low Risk</td>
    </tr>
    </tbody>
</table>
<table
    style="margin-bottom: 20px; font-size:12px;line-height:20px;border:2px solid #1c476d; padding: 5px; background: #ecf4f9; width:900px;">
    <thead>
    <th style="text-align: left;"><?php echo lang('Disclaimer'); ?></th>
    </thead>
    <tbody>
    <tr>
        <td style="margin-bottom: 20px;">This report is intended solely for Fa-med BV.</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td> The report has been compiled as carefully as possible. Any inaccuracies are beyond our responsibility. The
            general terms and conditions
            apply to this product.
        </td>
    </tr>
    </tbody>
</table>
<!--End Publications -->





