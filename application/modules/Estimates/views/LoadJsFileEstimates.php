<script type="text/javascript">
    //$(function () {

      $(document).on('click', '[type="submit"]', function () {
    var company_name = $('#company_name').val();
            if (company_name == "" || company_name == null)
    {
    $('#company_name_error').html("Please Enter Company Name");
            $('#company_name_error').focus();
            return false;
    }
    else
    {
    $('#company_name_error').html("");
    }
    
   
    return true;
      });
      $('#send_date').datepicker().on('changeDate', function (ev) {

        $('#send_date').datepicker('hide');

    });
    $('#due_date').datepicker().on('changeDate', function (ev) {

        $('#due_date').datepicker('hide');

    });
      
     $('.add_estimate').on('click', function () {
        cleardata();
         $('div.modelTitle').html('Create New Estimate');
        $('#estimate_submit').val('Create Estimate');
    });

    
    $('.edit_estimate').click(function () {
       
     cleardata()
    var val = $(this).attr('data-id');
            var data = 'estimate_id=' + val;
           
            $.ajax({
            type: "POST",
                    url: "<?php echo base_url($estimate_view); ?>/edit",
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                    $('div.modelTitle').html('Update Estimate');
                            $('#estimate_submit').val('Update Estimate');
                            $('#estimate_id').val(data.estimate_id);
                            $('#estimate_auto_id').val(data.estimate_auto_id);
                            $('#company_name').val(data.company_name);
                            $('#subject').val(data.subject);
                            $('#value').val(data.value);
                            $('#send_date').val(data.send_date);
                            $('#due_date').val(data.due_date);
                            $('#estimate').modal('show');
                    }
            });
            return false;
    
    });
            
    function cleardata() {
            $('#estimate_id').val('');
            $('#estimate_auto_id').val('');
            $('#company_name').val('');
            $('#subject').val('');
            $('#value').val('');
            $('#send_date').val('');
            $('#due_date').val('');
            $('#company_name_error').val('');
    }
</script>
