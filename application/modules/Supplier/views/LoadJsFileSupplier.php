<script type="text/javascript">
    //$(function () {

      $(document).on('click', '[type="submit"]', function () {
    var prospect_id = $('#prospect_id').val();
            if (prospect_id == "" || prospect_id == null)
    {
    $('#prospect_id_error').html("Please Select Prospect");
            $('#prospect_id_error').focus();
            return false;
    }
    else
    {
    $('#prospect_id_error').html("");
    }
    
    var deal_name = $('#deal_name').val();
            if (deal_name == "" || deal_name == null)
    {
    $('#deal_name_error').html("Please Enter Deal Name");
            $('#deal_name_error').focus();
            return false;
    }
    else
    {
    $('#deal_name_error').html("");
    }

    var deal_worth = $('#deal_worth').val();
            if (deal_worth == "" || deal_worth == null)
    {
    $('#dealworth_error').html("Please Enter Deal Worth");
            $('#dealworth_error').focus();
            return false;
    }
    else
    {
    $('#dealworth_error').html("");
    }
    return true;
      });
     $('.add_deal').on('click', function () {
        cleardata();
         $('div.modelTitle').html('Create New Deal');
        $('#deal_submit_btn').val('Create Deal');
    });

    
    $('.edit_deal').click(function () {
       
     cleardata()
    var val = $(this).attr('data-id');
            var data = 'deal_id=' + val;
           
            $.ajax({
            type: "POST",
                    url: "<?php echo base_url($deal_view); ?>/edit",
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                    $('div.modelTitle').html('Update Deal');
                            $('#deal_submit_btn').val('Update Deal');
                            $('#deal_id').val(data.deal_id);
                            $('#deal_name').val(data.deal_name);
                            $('#prospect_id').val(data.prospect_id);
                            $('#deal_worth').val(data.deal_worth);
                            $('#created_date').val(data.created_date);
                            $('#deal').modal('show');
                    }
            });
            return false;
    
    });
            
    function cleardata() {
            $('#deal_id').val('');
            $('#deal_name').val('');
            $('#prospect_id').val('');
            $('#deal_worth').val('');
            $('#created_date').val('');
            $('#prospect_id_error').val('');
            $('#deal_name_error').val('');
            $('#dealworth_error').val('');
    }
</script>
