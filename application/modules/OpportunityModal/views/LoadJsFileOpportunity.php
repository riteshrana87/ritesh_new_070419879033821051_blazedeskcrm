<script type="text/javascript">
    //$(function () {
    $('#prospect_generate').on('change', function () {

        var prospect_status = $('#prospect_generate').is(":checked");

        if (prospect_status == true)
        {

            $('#campaign_id').show();
        }
        else
        {

            $('#campaign_id').hide();
        }
    });
    $(document).on('click', '[type="submit"]', function () {
        check=true;
        var prospect_name_trim = $('#prospect_name').val();
        var prospect_name=$.trim(prospect_name_trim);
        if (prospect_name == "" || prospect_name == null)
        {
            $('#prospect_name_error').html("Please Enter Opportunity Name");
            $('#prospect_name_error').focus();
            check=false;
        }
        else
        {
            $('#prospect_name_error').html("");

        }
        var company_id = $('#company_id').val();
        if (company_id == "" || company_id == null)
        {
            $('#company_name_error').html("Please Enter Company Name");
            $('#company_name_error').focus();
            check=false;
        }
        else
        {
            $('#company_name_error').html("");

        }
        var prospect_owner_id = $('#prospect_owner_id').val();
        if (prospect_owner_id == "" || prospect_owner_id == null)
        {
            $('#prospect_owner_error').html("Please Select Prospect Owner");
            $('#prospect_owner_error').focus();
            check=false;
        }
        else
        {
            $('#prospect_owner_error').html("");

        }
        var branch_id = $('#branch_id').val();
        if (branch_id == "" || branch_id == null)
        {
            $('#branch_error').html("Please Select Branch");
            $('#branch_error').focus();
            check=false;
        }
        else
        {
            $('#branch_error').html("");

        }
       /* var contact_name_count = $('.contacts').length;
        //var valid = true;
        //alert(contact_name_count);

        for (var con_row_count = 0; con_row_count < contact_name_count; con_row_count++) {
            var contact_name = 'contact_name' + con_row_count;
            // alert(sEmail);
            contact_name = $('#contact_name' + con_row_count).val();

            //alert(con_row_count);

            if (contact_name == "" || contact_name == null)
            {
                $('#contact_name_error' + con_row_count).html("Please Enter Contact Name");
                $('#contact_name_error' + con_row_count).focus();
                check=false;
            }
            else
            {
                $('#contact_name_error' + con_row_count).html("");

            }
        }
        var phone_no_count = $('.contacts').length;
        //var valid = true;
        //alert(contact_name_count);

        for (var con_row_count = 0; con_row_count < phone_no_count; con_row_count++) {
            var contact_name = 'phone_no' + con_row_count;
            // alert(sEmail);
            contact_name = $('#phone_no' + con_row_count).val();

            //alert(con_row_count);

            if (contact_name == "" || contact_name == null)
            {
                $('#phone_no_error' + con_row_count).html("Please Enter Phone Number");
                $('#phone_no_error' + con_row_count).focus();
                check=false;
            }
            else
            {
                $('#phone_no_error' + con_row_count).html("");

            }
        }*/
        var email_count = $('.contacts').length;
        //var valid = true;
        //alert(email_count);

        for (var con_row_count = 0; con_row_count < email_count; con_row_count++) {
            var sEmail = 'email_id' + con_row_count;
            // alert(sEmail);
            sEmail = $('#email_id' + con_row_count).val();
            if (sEmail)
            {
                if (validateEmail(sEmail)) {
                    $('#email_error' + con_row_count).html("");
                }
                else {
                    $('#email_error' + con_row_count).html("Please Enter Valid Email Address");
                    $('#email_error' + con_row_count).focus();
                    check=false;
                }
            }
        }
        if(check==false){
            return false;
        }
        else{
             return true;
        }  

    });

    $('#creation_date').datepicker().on('changeDate', function (ev) {

        $('#creation_date').datepicker('hide');

    });
    $('#contact_date').datepicker().on('changeDate', function (ev) {

        $('#contact_date').datepicker('hide');

    });
    $('#creation_end_date').datepicker().on('changeDate', function (ev) {

        $('#creation_end_date').datepicker('hide');

    });
    $('#contact_end_date').datepicker().on('changeDate', function (ev) {

        $('#contact_end_date').datepicker('hide');

    });
    $('#search_contact_date').datepicker().on('changeDate', function (ev) {

        $('#search_contact_date').datepicker('hide');

    });
    $('#search_creation_date').datepicker().on('changeDate', function (ev) {

        $('#search_creation_date').datepicker('hide');

    });
    $('.add_opportunity').on('click', function () {
        cleardata();
        $('div.modelTitle').html('Create New Opportunity');
        $('#opp_submit_btn').val('Create Opportunity');
    });


    $('.edit_opportunity').click(function () {

        if (cleardata()) {
            var val = $(this).attr('data-id');
            var data = 'prospect_id=' + val;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url($opportunity_view); ?>/edit",
                data: data,
                dataType: 'json',
                success: function (data) {
                    $('div.modelTitle').html('Update Opportunity');
                    $('#opp_submit_btn').val('Update Opportunity');
                    $('#prospect_id').val(data.prospect_id);
                    $('#prospect_name').val(data.prospect_name);
                    $('#prospect_auto_id').val(data.prospect_auto_id)
                    $('#company_id').val(data.company_id);
                    $('#address1').val(data.address1);
                    $('#address2').val(data.address2);
                    $('#postal_code').val(data.postal_code);
                    $('#state').val(data.state);
                    $('#country_id').val(data.country_id);
                    $('#description').val(data.description);
                    $('#file_name').val(data.uploaded_file_name);
                    $('#file_name_text').html(data.uploaded_file_name);

                    //$('#file').val(data.uploaded_file_name);
                    $('#contact_date').val(data.contact_date);
                    $('#prospect_owner_id').val(data.prospect_owner_id);
                    $('#language_id').val(data.language_id);
                    $('#branch_id').val(data.branch_id);
                    $('#estimate_prospect_worth').val(data.estimate_prospect_worth);

                    if (data.prospect_generate == '1')
                    {
                        $('#campaign_id').show();
                        $('#prospect_generate').bootstrapToggle('on');
                    }
                    else {
                        $('#campaign_id').hide();
                    }

                    $('#campaign_id').val(data.campaign_id);
                    $('#creation_date').val(data.creation_date);
                    var contactCount = data.contact_name.length;
                    $.each(data.contact_name, function (key, value) {
                        $('#contact_name' + key).val(value);
                        $('#email_id' + key).val(data.email_id[key]);
                        $('#phone_no' + key).val(data.phone_no[key]);
                        if (key < contactCount - 1) {
//                                console.log(key+'='+value)
                            $('#add_row').click();
                        }
                    });

                    for (var product in data.product_id)
                    {
                        var optionVal = data.product_id[product];
                        {
                            $("#interested_products option[value='" + optionVal + "']").prop("selected", true);
                        }
                    }
                    $('#interested_products').trigger('chosen:updated');
                    $('#newOpportunity').modal('show');

                }
            });
            return false;
        }
    });


    var add_row_no = 0;
    $("#add_row").click(function () {
        add_row_no++;
        $('#add_more').append("<div id=\"add_contact" + add_row_no + "\" class = \"form-group row contacts\"><div class = 'col-sm-3'><input name='contact_name[]' type='text' placeholder='<?= $this->lang->line('contact_name') ?>' class='form-control' id='contact_name" + add_row_no + "'/> <span class='alert-danger' id='contact_name_error" + add_row_no + "'></span></div><div class = 'col-sm-4'><input name='email_id[]' type='text' placeholder='<?= $this->lang->line('email_address') ?>' class='form-control' id='email_id" + add_row_no + "'/> <span class='alert-danger' id='email_error" + add_row_no + "'></span></div><div class = 'col-sm-4'><input name='phone_no[]' type='text' placeholder='<?= $this->lang->line('phone_no') ?>' onkeypress='return isNumberKey(event)' size='20' maxlength='20' class='form-control' id='phone_no" + add_row_no + "'/> <span class='alert-danger' id='phone_no_error" + add_row_no + "'></span></div><div class = 'col-sm-1'><a id='delete_row' class='pull-right btn btn-default' onclick=\"delete_row(" + add_row_no + ");\"><span class='glyphicon glyphicon-trash'></span></a></div></div>");
    });

    function delete_row(removeNum) {
         var add_row_no = $('.contacts').length;
            if (add_row_no > 1) {
        jQuery('#add_contact' + removeNum).remove();
        add_row_no--;
        }
    }
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    function cleardata() {
        $('#prospect_id').val('');
        $('#prospect_name').val('');
        $('#pros_auto_id').val('');
        $('#company_id').val('');
        $('#address1').val('');
        $('#address2').val('');
        $('#postal_code').val('');
        $('#state').val('');
        $('#country_id').val('');
        $('#description').val('');
        $('#file_name').val('');
        $('#file_name_text').html('');
        $('#prospect_owner_id').val('');
        $('#language_id').val('');
        $('#contact_date').val('');
        $('#prospect_generate').bootstrapToggle('off');
        $('#language_id').val('');
        $('#branch_id').val('');
        $('#estimate_prospect_worth').val('');
        $('#campaign_id').val('');
        $('#creation_date').val('');
        $('#prospect_name_error').html("");
        $('#company_name_error').html("");
        $('#prospect_owner_error').html("");
        $('#branch_error').html("");
        var email_count = $('.contacts').length;
        //var valid = true;
        //alert(email_count);
        for (var con_row_count = 0; con_row_count <= email_count; con_row_count++) {
            $('#email_error' + con_row_count).html("");
            $('#contact_name' + con_row_count).html("");
        }
        //$('.email_error').html("");
        $('#campaign_error').html("");
        $('#interested_products').val('').trigger('chosen:updated');
        $('input[name*="contact_name[]"]').val('');
        $('input[name*="email_id[]"]').val('');
        $('input[name*="phone_no[]"]').val('');
        var email_count = $('.contacts').length;
        for (var con_row_count = 1; con_row_count <= email_count; con_row_count++) {
            $('#add_contact' + con_row_count).remove();
        }
        add_row_no = 0;
        var div_cnt = $('#interested_products_chosen');
        if (div_cnt.length > 1)
        {
            div_cnt.not(':last').remove();
        }
        return true;
    }
    function validateEmail(sEmail) {

        var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
        if (filter.test(sEmail)) {
            return true;
        }
        else {
            return false;
        }
    }
    function searchRecord()
    {
        var base_url = window.location.origin;
        var pathArray = window.location.pathname.split('/');
        var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2];

        $("#frm_search_box").attr("action", send_url);
        $("#frm_search_box").submit();
    }
    function resetRecord()
    {
        var base_url = window.location.origin;
        var pathArray = window.location.pathname.split('/');
        var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2];

        $("#frm_search_box").attr("action", send_url);
        $("#frm_search_box").submit();
    }
    /*$('.view_prospect').click(function () 
     {
     alert('hi');
     $('#newOpportunity').find('input, textarea, button, select').attr('disabled','disabled');
     $('#newOpportunity').find('submit').attr('hidden','hidden');
     $('#newOpportunity').modal('show');
     });*/

</script>
