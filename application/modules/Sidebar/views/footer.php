<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->

<script src="<?= base_url() ?>uploads/dist/js/datatables.min.js"></script>
<!--<script src="<?php //echo base_url('uploads/dist/js/bootstrap.min.js');     ?>"></script>-->
<script src="<?php echo base_url('uploads/dist/js/highchart.js'); ?>"></script>
<script src="<?php echo base_url('uploads/dist/js/data.js'); ?>"></script>
<script src="<?php echo base_url('uploads/dist/js/exporting.js'); ?>"></script>


<script src="<?= base_url() ?>uploads/custom/js/jquery.webui-popover.js"></script>
<link href="<?= base_url() ?>uploads/custom/css/jquery.webui-popover.css" rel="stylesheet">
<?php if (isset($drag) && $drag == true): ?>
    <script src="<?= base_url() ?>uploads/custom/js/jquery-ui.min.js"></script>
<?php endif; ?>
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
<link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
<script src="<?= base_url() ?>uploads/custom/js/projectmanagement/parsley.js"></script>
<script src="<?= base_url() ?>uploads/dist/js/bootstrap-datepicker.js"></script>
<link href="<?= base_url() ?>uploads/custom/css/projectmanagement/parsley.css" rel="stylesheet">


<!-- summernote Core JS-->
<script src="<?= base_url() ?>uploads/dist/js/summernote.min.js"></script>
<link href="<?= base_url() ?>uploads/dist/css/summernote.css" rel="stylesheet">
<script src="<?php echo base_url('uploads/custom/js/jsignature/jSignature.min.js'); ?>"></script>
<link href="<?php echo base_url('uploads/custom/css/bootstrap-dialog.css'); ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url('uploads/custom/js/bootstrap-dialog-min.js'); ?>"></script>
<link href='<?= base_url() ?>uploads/custom/css/projectmanagement/fullcalendar.css' rel='stylesheet' />
<script src='<?= base_url() ?>uploads/custom/js/projectmanagement/moment.js'></script>
<script src='<?= base_url() ?>uploads/custom/js/projectmanagement/fullcalendar.js'></script>


<script type="text/javascript">
    $(document).ready(function () {

        $('#datepicker,  #datepicker1, #datepicker2').datepicker();

        $('#datatable, #datatable1, #datatable2, #datatable3, #datatable4, #datatable5, #datatable6, #datatable7').DataTable();

    });
    $(document).ready(function () {
        var sideslider = $('[data-toggle=collapse-side]');
        var sel = sideslider.attr('data-target');
        var sel2 = sideslider.attr('data-target-2');
        sideslider.click(function (event) {
            $(sel).toggleClass('in');
            $(sel2).toggleClass('out');
        });
    });

    $(document).ready(function () {
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
        });
        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 8000);

    });


</script>

<!-- by brt-->
<script type="text/javascript">
setTimeout(function () {
         //  get_mail_count();
        }, 3000);

    function getData() {

        var send_url = '<?php echo base_url() ?>Mail/getUnreadMailCount';
        // 1) create the jQuery Deferred object that will be used
        var deferred = $.Deferred();

//   ---- AJAX Call ---- //

        var xhr = new XMLHttpRequest();
        xhr.open("GET", send_url, true);

        // register the event handler
        xhr.addEventListener('load', function () {
            if (xhr.status === 200) {
                // 3.1) RESOLVE the DEFERRED (this will trigger all the done()...)
                deferred.resolve(xhr.response);
            } else {
                // 3.2) REJECT the DEFERRED (this will trigger all the fail()...)
                deferred.reject("HTTP error: " + xhr.status);
            }
        }, true);

        // perform the work
        xhr.send();
        // Note: could and should have used jQuery.ajax.
        // Note: jQuery.ajax return Promise, but it is always a good idea to wrap it
        //       with application semantic in another Deferred/Promise
        // ---- /AJAX Call ---- //

        // 2) return the promise of this deferred
        return deferred.promise();
    }



    function get_mail_count()
    {
        var send_url = '<?php echo base_url() ?>Mail/getUnreadMailCount';
        // $('.mailCount').html(3);

        $.ajax({
            type: "POST",
            url: send_url,
            data: {'Mail_count': 'Mail_count'},
            success: function (data)
            {
                $('.mailCount').html('('+data+')');
            },
            async: true,
        });

    }
    $(document).ready(function () {
        // get_mail_count();
    });
</script>
<!-- brt ends-->

<!-- Start code FOR System Notification
Added By Sanket on 03/05/2016

-->
<script type="text/javascript">
    //var articles = [["There is some new message Blazedesk Notification.","http://www.9lessons.info/2015/11/customize-youtube-embed-player.html"]];
    document.addEventListener('DOMContentLoaded', function ()
    {
        if (Notification.permission !== "granted")
        {
            Notification.requestPermission();
        }
    });

    function getNotify()
    {
        if (!Notification)
        {
            console.log('Desktop notifications not available in your browser..');
            return;
        }
        var send_url = '<?php echo base_url() ?>Help/getNotifiedMessage';

        var arr = [];
        $.ajax({
            type: "POST",
            url: send_url,
            async: true,
            data: {'Mail_count': 'Mail_count'},
            success: function (data)
            {
                var json_data = $.parseJSON(data);
                var total_msg = json_data.length;

                for (var j = 0; j < total_msg; j++)
                {
                    var title = 'New Message From ' + json_data[j]['from_name'];
                    var desc = json_data[j]['message_subject'];
                    var url = json_data[j]['url'];
                    arr.push({title: title, desc: desc, url: url});
                }

            }

        });


        $.each(arr, function (index, value)
        {

            var title = value.title;
            var desc = value.desc;
            var url = value.url;

            if (Notification.permission !== "granted")
            {
                Notification.requestPermission();
            }
            else
            {

                var notification = new Notification(title, {
                    icon: '<?php echo base_url() . "uploads/images/notification_logo.png" ?>',
                    body: desc,
                });

                // Remove the notification from Notification Center when clicked.
                notification.onclick = function () {
                    window.open(url);
                };

                // Callback function when the notification is closed.
                notification.onclose = function () {
                    console.log('Notification closed');
                };

            }
        });
    }
    $(document).ready(function ()
    {
        var ms_ie = false;
        var ua = window.navigator.userAgent;
        var old_ie = ua.indexOf('MSIE ');
        var new_ie = ua.indexOf('Trident/');

        if ((old_ie > -1) || (new_ie > -1)) {
            ms_ie = true;
        }

        if (ms_ie == false)
        {
            setInterval(function () {
                getNotify();
            }, 1000 * 60 * 2);
            getNotify();
        }
        /*
         if ( ms_ie ) {
         alert('this is internet exploreer');
         }
         */


    });

</script>
<script src="http://maps.google.com/maps/api/js"></script>
<!-- Start code FOR System Notification-->

<!-- Script added by sanket for selected tab after changing language-->
<script>
    /*
 $(document).ready(function(){
  
    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href"); 
   
    $.ajax({
        type: "POST",
        url: '<?php echo base_url()."CommonModel/change_session_tab"?>',
        data: {tabName: target},
        success: function ()
        {
            
        }
    });
});
});
*/
</script>

