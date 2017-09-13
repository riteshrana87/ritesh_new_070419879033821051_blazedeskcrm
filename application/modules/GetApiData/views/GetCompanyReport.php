<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$formAction = 'CompanyReport';
$formAction = 'companyReportTest    ';
$path = $company_view . '/' . $formAction;
?>

<!-- Modal New Company-->
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?php echo lang('company_report');?></div></h4>
        </div>
        <form id="add_company" name="add_company" method="post"  action="<?php //base_url($path); ?>" data-parsley-validate="" enctype="multipart/form-data">
            <div class="modal-body">
                <input name="company_id" type="hidden" value="<?= !empty($editRecord[0]['company_id']) ? $editRecord[0]['company_id'] : '' ?>" />
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <select name="country_id" class="form-control chosen-select" id="country_id" onchange="api_call();" required tabindex="1" data-parsley-errors-container="#country-errors">
                            <option value="">
                                <?= $this->lang->line('select_country') ?> *
                            </option>
                            <?php if (isset($country) && count($country) > 0) { ?>
                                <?php foreach ($country as $country_data) { ?>
                                    <option data-taxincluded-amount="<?php echo $country_data['country_code']; ?>" value="<?php echo $country_data['country_id']; ?>" <?php
                                    if (!empty($editRecord[0]['country_id']) && $editRecord[0]['country_id'] == $country_data['country_id']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $country_data['country_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <div id="country-errors"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="company_name" id="company_name" tabindex="2" placeholder="<?= lang('company_name') ?> *" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('company_name');
                            ?><?php } else { ?><?= !empty($editRecord[0]['company_name']) ? $editRecord[0]['company_name'] : '' ?><?php } ?>"  required/><span></span>

                        <input type="hidden" name="com_reg_number" id="com_reg_number" value="">
                        <input type="hidden" name="company_id_data" id="company_id_data" value="">
                    </div>
                </div>
                <div class=" row">
                  <!--  <div class="col-sm-6 form-group">
                        <select name="report_types" class="form-control chosen-select" id="report_types"  required tabindex="3" data-parsley-errors-container="#report-types-errors">
                            <option value="">
                                <?php echo lang('report_types');?>
                            </option>
                            <option value="D44CI102">International credit report</option>
                            <option value="D44CI301">Chamber of commerce extract</option>
                            <option value="D44CI302">Basic report</option>
                            <option value="D44CI501">Annual report</option>
                            <option value="D44CI701">Credit report</option>
                            <option value="D44CI702">Credit report PLUS</option>
                            <option value="D44CI704">Rating report</option>
                            <option value="D44CI707">Credit report PLUS IPS</option>

                        </select>
                        <div id="report-types-errors"></div>
                    </div>-->
                    <div class="col-sm-6 form-group">
                        <select name="language" class="form-control chosen-select" id="language"  required tabindex="3" data-parsley-errors-container="#language-errors">
                            <option selected value="EN">English</option>
                            <option value="NL">Netherlands</option>

                        </select>
                        <div id="language-errors"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="address1" id="address1" tabindex="4" placeholder="<?= lang('address1') ?>" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('address1');
                            ?><?php } else { ?><?= !empty($editRecord[0]['address1']) ? $editRecord[0]['address1'] : '' ?><?php } ?>"  />

                    </div>
                </div>

                <div class=" row">

                    <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                    <input type="hidden" name="redirect_link" value="<?php echo $redirect_link;?>">
                    <div class="col-sm-6 form-group">
                        <input class="form-control" name="city" id="city" placeholder="<?= lang('city') ?>" tabindex="5" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('city');
                            ?><?php } else { ?><?= !empty($editRecord[0]['city']) ? $editRecord[0]['city'] : '' ?><?php } ?>"  />

                    </div>

                    <div class="col-sm-6 form-group ">
                        <input class="form-control" name="postal_code" id="postal_code" placeholder="<?= lang('postal_code') ?>" tabindex="6" type="text" value="<?PHP if ($formAction == "insertdata") {
                            echo set_value('postal_code');
                            ?><?php } else { ?><?= !empty($editRecord[0]['postal_code']) ? $editRecord[0]['postal_code'] : '' ?><?php } ?>"  />
                    </div>
                </div>

                <div class="modal-header">
                    <h4 class="modal-title">
                        <div class="modelTaskTitle"> <?= $this->lang->line('card_detail'); ?> </div>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class = " row contacts" id="add_contact">

                        <div class = "col-lg-12 col-xs-12 col-sm-12">
                            <div class="col-lg-12 col-xs-12 col-sm-12">

                                <div class="row">
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="text" class="form-control card-holder-name" data-parsley-pattern="/^([^0-9]*)$/"  size="20" autocomplete="off" placeholder="<?php echo lang('card_holder_name');?> *" autofocus name="card_name" id="card_name" required>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="text" class="form-control" maxlength="6" placeholder="$20" name="tot_cred" id="tot_cred" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="text" class="form-control card-number" onkeypress='return validateQty(event);'  size="20" autocomplete="off" placeholder="<?php echo lang('card_number');?> *" autofocus name="card_number" id="card_number" required>
                                        <div style="color: red;" id="errorjs_number"></div>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="password" onkeypress='return validateQty(event);' class="form-control card-cvc" maxlength="4" placeholder="<?php echo lang('cvc'); ?> *" autocomplete="off" name="cvc" id="cvc" required>
                                        <div style="color: red;" id="errorjs_cvc"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="text" onkeypress='return validateQty(event);' class="form-control card-expiry-month" size="2" placeholder="<?php echo lang('expm');?> *" autocomplete="off" name="exp_month" id="exp_month" required>
                                        <div style="color: red;" id="errorjs_month"></div>
                                    </div>
                                    <div class="col-sm-6 form-group ">
                                        <input value="" type="text" onkeypress='return validateQty(event);' size="4" class="form-control card-expiry-year" placeholder="<?php echo lang('expy');?> *" autocomplete="off" name="exp_year" id="exp_year" required/>
                                        <div style="color: red;" id="errorjs_year"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Company Report" />
                    </div>

            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#add_company').parsley();
        $('.chosen-select').chosen();
    });

    function api_call()
    {
        var country = $('#country_id').find(':selected').attr("data-taxincluded-amount");
        $("#company_name").autocomplete( {
            width: 260,
            matchContains: true,
            selectFirst: false,
            max:15,
            minLength: 3,
            source:'<?php echo base_url();?>GetApiData?country_id='+country,
            search: function(){
                $("#company_name").siblings().addClass("bd-input-load");
            },
            response: function (event, ui) {
                if (ui.content.length === 0) {
                    $(".bd-input-load").removeClass("bd-input-load");
                }
            },

            select: function( event , ui ) {
                $(".bd-input-load").removeClass("bd-input-load");
                $("#company_id_data").val(ui.item.id);
                $("#address1").val(ui.item.address);
                $("#postal_code").val(ui.item.zipcode);
                $("#city").val(ui.item.city);
                $("#com_reg_number").val(ui.item.reg_number);
                $("#com_est_number").val(ui.item.est_number);
                //alert( "You selected: " + ui.item.id);
            }
        });
    }
    <?php if (!empty($editRecord[0]['country_id'])) { ?>
    api_call();
    <?php } ?>
    //Not allow duplicate contact email code


</script>
<script type="text/javascript">
    // this identifies your website in the createToken call below
    //                Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs');
     //Stripe.setPublishableKey('pk_test_suxHAAvKSymUCw8lxGk7ZxLs');  // DEV1's publishable key
    Stripe.setPublishableKey('<?php echo STRIPE_KEY_PK;?>');

    function stripeResponseHandler(status, response) {
         //alert('hi');
        //alert(response);return false;
        if (response.error) {
            // re-enable the submit button
            $("#submit_btn").removeAttr("disabled");
            // show the errors on the form
            console.log(response.error.code);
            if(response.error.code == 'invalid_expiry_month'){
                $("#errorjs_month").html(response.error.message);
            }
            if(response.error.code == 'invalid_cvc'){
                $("#errorjs_cvc").html(response.error.message);
            }
            if(response.error.code == 'invalid_expiry_year'){
                $("#errorjs_year").html(response.error.message);
            }
            if(response.error.code == 'incorrect_number'){
                $("#errorjs_number").html(response.error.message);
            }

          //  $("#errorjs").html(response.error.message);

        } else {

           /* var form$ = $("#add_company");
            var token = response.id;
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            // and submit
            form$.get(0).submit();
*/
                var tmp ;
                var country_id = $('#country_id').find(':selected').attr("data-taxincluded-amount");
                var company_name = $("input#company_name").val();
                var com_reg_number = $("input#com_reg_number").val();
                var company_id_data = $("input#company_id_data").val();
                var report_types = $("#report_types").val();
                var language = $("#language").val();
                var token = response.id;
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "GetApiData/CompanyInquiry",
                    dataType: 'json',
                    data: {country_id: country_id, company_name: company_name,com_reg_number:com_reg_number,company_id_data:company_id_data,report_types:report_types,language:language,token:token},
                    success: function(res) {
                        if (res.inquery)
                        {
                            console.log(res);
                            tmp = res;
                        }else{
                            var delete_meg = res.error;
                            BootstrapDialog.show(
                                {
                                    title: '<?php echo $this->lang->line('Information');?>',
                                    message: delete_meg,
                                    buttons: [{
                                        label: '<?php echo $this->lang->line('ok');?>',
                                        action: function(dialog) {
                                            dialog.close();
                                        }
                                    }]
                                });
                            $(".close").click();
                            return false;
                        }
                    },

                    complete: function() {
                        if (tmp) {
                            console.log(tmp);
                            var loaderIMG = '<div id="show_company"><img src="<?php echo base_url()."/uploads/images/load1.gif"; ?>" /> </div>' ;
                            $('#show_company_loader').html(loaderIMG);
                            $('#show_company_loader').show();
                            setTimeout(function () { // use this way
                                window.location.href = '<?php echo base_url();?>GetApiData/CompanyReport/'+ tmp.inquery +'/'+ tmp.report_type
                            $('#show_company_loader').hide();
                            }, 20000);

                            $(".close").click();
                        }

                    }

                });
            }
    }

    $(document).ready(function() {
        $('#add_company').parsley();
        $("#add_company").submit(function(event) {

            // $('#paymentfrm').parsley('validate');
            //alert($('#support_notify').length();
            //alert($('[name="support_notify"]:checked').length);
            // disable the submit button to prevent repeated clicks
            if($('#card_number').val()==''){
                $("#errorjs_number").html('<?php echo lang('enter_credit_card_number');?>');
                return false;
            }
            var currentYear = (new Date).getFullYear();

            var currentMonth = (new Date).getMonth() + 1;
            if($('.card-expiry-month').val()<currentMonth && $('.card-expiry-year').val()==currentYear){
                $("#errorjs_month").html('<?php echo lang('invalid_month');?>');
                return false;
            }
            if($('.card-expiry-year').val()<currentYear){
                $("#errorjs_year").html('<?php echo lang('invalid_year');?>');
                return false;
            }

            // $('.submit-button').hide();
            $('#submit_btn').prop('disabled', true);

// createToken returns immediately - the supplied callback submits the form if there are no errors
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val(),
                quantity:$('#no_of_std').val()
            }, stripeResponseHandler);

            return false; // submit from callback
        });

    });

    function validateQty(event) {
        var key = window.event ? event.keyCode : event.which;

        if (event.keyCode == 8 || event.keyCode == 46
            || event.keyCode == 37 || event.keyCode == 39) {
            return true;
        }
        if (event.keyCode == 9) {
            return true;
        }
        else if (key < 48 || key > 57) {
            return false;
        }
        else
            return true;
    }
    ;
</script>

<script>
    //disabled after submit
    $('body').delegate('#task_submit_btn', 'click', function () {
        if ($('#add_company').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#add_company').submit();
        }
    });

    //chosen select code
    $('.chosen-select').chosen();

    $(document).ready(function () {
        //parsley validation
        //$('#paymentfrm').parsley();

        window.Parsley.addValidator('gteq',
            function (value, requirement) {
                return Date.parse($('#end_date input').val()) >= Date.parse($('#start_date input').val());
            }, 32)
            .addMessage('en', 'le', 'This value should be less or equal');

    });
    //toggle button
    $('#reminder').bootstrapToggle();





</script>



