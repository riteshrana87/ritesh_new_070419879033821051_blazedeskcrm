<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript">
    $(function () {

        $('#budget_ammount').keypress(function (event) {
            return isNumber(event, this)
        });

        $('#revenue_amount').keypress(function (event) {
            return isNumber(event, this)
        });


        $('#supplier_id').hide();
        $('#product_id').hide();
        $('#start_date').datepicker().on('changeDate', function (ev) {
            $('#start_date').datepicker('hide');

        });
        $('#end_date').datepicker().on('changeDate', function (ev) {

            $('#end_date').datepicker('hide');

        });


        $(document).on('click','[type="submit"]', function() {
            var campaign_name = $('#campaign_name').val();
            var campaign_type = $('#campaign_type_id').val();
            var start_date=$('input[name="start_date"]').val();
            var end_date=$('input[name="end_date"]').val();

            if(campaign_name == "" || campaign_name == null)
            {
                $('#campaign_name_error').html("Please Enter Campaign Name");
                return false;
            }
            if(campaign_type == "" || campaign_type == null)
            {
                $('#campaign_type_error').html("Please Select Campaign Type");
                return false;
            }
            if(start_date == "" || start_date == null)
            {
                $('#start_date_error').html("Please Select Start Date");
                return false;
            }
            if(end_date == "" || end_date == null)
            {
                $('#end_date_error').html("Please Select End Date");
                return false;
            }
            if(Date.parse(start_date) > Date.parse(end_date))
            {
                $('#start_date_error').html("Start date should not be greater than end date");
                return false;
            }
            else
            {
                return true;
            }
        });
    });

    function cleardata()
    {
        $('#display_filename').html('');
        $('#budget_requirement').bootstrapToggle('off');
        $('#campaign_supplier').bootstrapToggle('off');
        $('#revenue_goal').bootstrapToggle('off');
        $('#related_product').bootstrapToggle('off');
        $('#compaign_receipient').val('').trigger('chosen:updated');
        var div_cnt=$('#compaign_receipient_chosen');

        if (div_cnt.length > 1) {
            div_cnt.not(':last').remove();
        }
        $('#campaign_name_error').html('');
        $('#campaign_type_error').html('');
        $('#start_date_error').html('');
        $('#end_date_error').html('');

    }
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#budget_requirement').bootstrapToggle();
    $('#campaign_supplier').bootstrapToggle();
    $('#revenue_goal').bootstrapToggle();
    $('#related_product').bootstrapToggle();
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-�? CHECK MINUS, AND ONLY ONE.
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.�? CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>
<script type="text/javascript">
    var config = {
        support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",		// Valid file formats
        form: "demoFiler",					// Form ID
        dragArea: "dragAndDropFiles",		// Upload Area ID
        uploadUrl: "<?php echo $sales_view;?>/upload_file"				// Server side upload url
    }

    $(document).ready(function(){
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass : "active",
            on: {
                load: function(e, file) {
                    console.log(e);
                    console.log(file);
                    // check file type
                    var imageType = /image.*/;
                    if (!file.type.match(imageType)) {
                        alert("File \""+file.name+"\" is not a valid image file");
                        return false;
                    }

                    // check file size
                    if (parseInt(file.size / 1024) > 2050) {
                        alert("File \""+file.name+"\" is too big.Max allowed size is 2 MB.");
                        return false;
                    }

                    create_box(e,file);
                },
            }
        };
        FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
        var fileArr=[];

        create_box = function(e,file){
            var rand = Math.floor((Math.random()*100000)+3);
            var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
            var src		= e.target.result;

            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
            xhr[rand].open("post", "<?php echo base_url('/SalesCampaign/upload_file')?>", true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='"+rand+"'] span").css("width",(event.loaded / event.total) * 100 + "%");
                    $(".preview[id='"+rand+"'] .updone").html(((event.loaded / event.total) * 100).toFixed(2)+"%");
                }
                else {
                    alert("Failed to compute file upload length");
                }
            }, false);

            xhr[rand].onreadystatechange = function (oEvent) {
               var img = xhr[rand].response;
                if (xhr[rand].readyState === 4) {
                    if (xhr[rand].status === 200) {
                        var template = '<div class="eachImage" id="'+rand+'">';
                        var randtest = 'delete_row("' +rand+ '")';
                        template += '<a id="delete_row" class="remove_drag_img" onclick='+randtest+'>×</a>';
                        template += '<span class="preview" id="'+rand+'"><img src="'+src+'"><span class="overlay"><span class="updone"></span></span>';
                        template += '<input type="hidden" name="fileToUpload[]" value="'+img+'">';
                        template += '</span>';
                        template += '<div class="progress" id="'+rand+'"><span></span></div>';
                        $("#dragAndDropFiles").append(template);
                    }
                }
            };


            xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
            xhr[rand].setRequestHeader("X-File-Name", file.fileName);
            xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
            xhr[rand].setRequestHeader("X-File-Type", file.type);

            // Send the file (doh)
            xhr[rand].send(file);

        }
        upload = function(file,rand){
        }

    });
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }

    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked')) $(className).show();
        else $(className).hide();
    }

</script>