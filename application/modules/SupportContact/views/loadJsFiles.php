<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript">
   $(function () { 
       $('#contact_start_date').datepicker();
       $('#contact_end_date').datepicker();
       $('#creation_start_date').datepicker();
       $('#creation_end_date').datepicker();
       
       $(document).on('click','[type="submit"]', function() {
        var contact_name = $('#contact_name').val();
        var email = $('#email').val();
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        
        if(contact_name == "" || contact_name == null)
        {
            $('#contact_name_error').html("Please Enter Contact Name");
            return false;
        }
        if(email == "" || email == null)
        {
            $('#email_error').html("Please Enter Email Address");
            return false;
        }
        if(!filter.test(email))
        {
            $('#email_error').html("Please Enter Valid Email Address");
            return false;
        }
        else
        {    
            return true;
        }   
    
       });
       

        
   });
            
   function do_filter(cname)
   {
        $.ajax({
        url: "<?php echo $contact_view;?>/cfilter",
        data: {cname: cname},
        success: function(data){
            //alert(data);
              $.each(data, function (index) {
                  $.each(index, function (key,value) {
                 // alert(key);
                 // alert(value);
              });
              });
            //window.location="<?php echo $contact_view;?>";
           /*var newOptions7=data;
            var $el = $("#filter");
                    $el.empty(); 
                      //alert(newOptions7);
                      //alert(newOptions7.size());
                $.each(newOptions7, function (index,val) {
                   // alert(val);
                   console.log(val);
                });*/
            }
        });    
   }

</script>