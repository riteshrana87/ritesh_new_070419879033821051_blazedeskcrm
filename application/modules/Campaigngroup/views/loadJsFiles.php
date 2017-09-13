<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script type="text/javascript">
    $(function () {
        $('#campaign_id').hide();
        $("#select_all").click(function(){
          $('input[name="add_to_group[]"]' ).each( function() {
            $(this).prop('checked', true);
          });


        });


    });
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked')) $(className).show();
        else $(className).hide();
    }


</script>