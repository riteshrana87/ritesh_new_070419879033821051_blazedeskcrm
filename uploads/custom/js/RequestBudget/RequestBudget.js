function autofilldata(campaign_id)
{

    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/getCampaignDataById';
    $.ajax({
        type: "POST",
        url: send_url,
        data: {campaign_id: campaign_id},
        success: function (data)
        {
            var dataObj = JSON.parse(data);
            console.log(dataObj);
            $('#campaign_auto_id').val(dataObj[0].campaign_auto_id);
            $('#campaign_type_id').val(dataObj[0].camp_type_name).change();
            $('#responsible_employee_id').val(dataObj[0].responsible_employee_id).change();
            $('#start_date').datepicker('setDate', myDateFormatter(dataObj[0].start_date));
            $('#end_date').datepicker('setDate', myDateFormatter(dataObj[0].end_date));
            $('#campaign_description').code(dataObj[0].campaign_description);
            // $('#budget_ammount').val(dataObj[0].revenue_amount);
            // $('#revenue_goal').val(dataObj[0].revenue_goal);

            $('#budget_ammount_pri').val(dataObj[0].budget_ammount);
            $('#revenue_goal').val(dataObj[0].revenue_amount);
            
            $('#supplier_id').val(dataObj[0].supplier_id);
            $('#supplier_id').trigger('chosen:updated');
            $('#responsible_employee_id').trigger('chosen:updated');
            $('#campaign_type_id').trigger('chosen:updated');

            //$('#budget_for_product').val(dataObj[0].product_id);

            $('#hdn_campaign_id').val(dataObj[0].campaign_id);
            $('#hdn_auto_gen_id').val(dataObj[0].campaign_auto_id);

            show_campaing_product(dataObj[0].product_id);
            show_multipul_user(dataObj[0].campaign_id);

           
        }
    });
}

function show_campaing_product(product_id)
{

    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/show_Campaign_product';
    $.ajax({
        type: "POST",
        url: send_url,
        data: {product_id: product_id},
        success: function (data)
        {
            $('#product_data').html(data);
            $('#budget_for_product').chosen();

        }
    });
}


function show_multipul_user(campaign_id)
{
    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/show_Campaign_responsible';
    $.ajax({
        type: "POST",
        url: send_url,
        data: {campaign_id: campaign_id},
        success: function (data)
        {
            $('#responsible_employee').html(data);
            $('#responsible_employee_id').chosen();

        }
    });
}


/*function delete_request(campaign_id)
{
    BootstrapDialog.confirm("<?php echo $this->lang->line('delete_request_item');?>", function(result){
        if(result) {
            window.location.href = "RequestBudget/delete_request/" + campaign_id;
        }
    });
}
*/
function edit_request(budget_campaign_id)
{
    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/get_request_data';

    $.ajax({
        type: "POST",
        //url:"http://localhost/blazedeskcrm/RequestBudget/get_request_data",
        url: send_url,
        data: {'budget_campaign_id': budget_campaign_id},
        success: function (data)
        {
            $('#type_action').html('Edit&nbsp');
            var dataObj = JSON.parse(data);
            $('#master_compaign').val(dataObj[0].campaign_id);
            $('#master_compaign').attr('disabled', 'true');
            $('#campaign_auto_id').val(dataObj[0].campaign_auto_id);
            $('#campaign_type_id').val(dataObj[0].campaign_type_id).change();
            $('#responsible_employee_id').val(dataObj[0].employee_id).change();
            $('#start_date').datepicker('setDate', myDateFormatter(dataObj[0].start_date));
            $('#end_date').datepicker('setDate', myDateFormatter(dataObj[0].end_date));
            $('textarea#campaign_description').code(dataObj[0].campaign_description);
            $('#budget_ammount').val(dataObj[0].budget_ammount);
            $('#revenue_goal').val(dataObj[0].revenue_goal);
            $('#supplier_id').val(dataObj[0].supplier_id);
            $('#budget_for_product').val(dataObj[0].product_id);
            $('#aditional_notes').val(dataObj[0].aditional_notes);

            $('#btn_submit').attr('onclick', 'updateRecord()');
            $('#hdn_budget_campaign_id').val(budget_campaign_id);
            $('#hdn_campaign_id').val(dataObj[0].campaign_id);
            $('#hdn_auto_gen_id').val(dataObj[0].campaign_auto_id);

            if (dataObj[0].file != '')
            {
                var file_path = base_url + "/" + pathArray[1] + '/uploads/budgetCampaign/' + dataObj[0].file;
                $('#spn_download').html("<a href='" + file_path + "' download>" + dataObj[0].file + "</a>");
            }
        }
    });
    $("#createSalesCampaign").modal('show');
}
function add_person()
{
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
}
function myDateFormatter(dateObject)
{
    var d = new Date(dateObject);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var date = month + "/" + day + "/" + year;
    return date;
}
function validateFile(fname)
{
    //var fname = "the file name here.ext";
    var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i;
    if (!re.exec(fname))
    {
        return false;
    } else
    {
        return true;
    }
}

$(document).ready(function () {

    $('body').delegate("#budget_ammount,#revenue_goal","keyup", function () {
        var valid = /^\d{0,8}(\.\d{0,2})?$/.test(this.value),
            val = this.value;

        if (!valid) {
            this.value = val.substring(0, val.length - 1);
        }
    });
});

$(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});


    $('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                , $remote = $this.data('remote') || $this.attr('data-href')
                , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            var url=$remote+"?token="+generateFormToken;
            $modal.load(url);
            $("body").css("padding-right", "0 !important");
        }
    );
function delete_request(campaign_id)
{
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_meg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function(dialog) {
                    window.location.href = "RequestBudget/delete_request/" + campaign_id;
                    dialog.close();
                }
            }]
        });

}

