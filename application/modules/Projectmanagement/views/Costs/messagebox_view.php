<?php if (checkPermission("Messages", 'view')) { ?>
    <div id="taskchat">
        <div class="whitebox sortableDiv" id="messagebox_view">
            <h2 class="title-2"><?php echo lang('messages'); ?></h2>
            <div class="clr"></div>
            <div id="received" class="scrollbar bd-chat-box"><ul></ul>  </div>
     
            <form>
                <div class="form-group"><input class="form-control" id="text" placeholder="<?php echo lang('write_msg'); ?>" type="text" name="user"></div>
                <div class="form-group col-lg-12"><a class="view_his btn btn-blue " href="<?= base_url('Projectmanagement/Messages') ?>"><?= lang('view_history') ?> </a>
                    <input id="send_message" class="btn btn-default pull-right" type="submit" value="<?php echo lang('send'); ?>"><div class="clr"></div></div>
                <div class="clr"></div>
            </form>
        </div>
    </div>
    <script>
        var time = 0;

        //insert message
        var sendChat = function (message, cb) {
            $.getJSON("Messages/insert_message?message=" + message, function (data) {
                cb();
            });
        }

        var addDataToReceived = function (arrayOfData) {
            arrayOfData.forEach(function (data) {
                $("#received ul").append(data[0]);
            });
        }

        var getNewChats = function () {
            $.getJSON("Messages/get_message?time=" + time, function (data) {
                addDataToReceived(data);
                // reset scroll height
                if(data != '')
                {
                    setTimeout(function () {
                        chatBox = document.getElementById("received");
                        chatBox.scrollTop = chatBox.scrollHeight
                    }, 0);
                }
                if (data.length > 0) {
                    time = data[data.length - 1][1];
                }

            });
        }

        // using JQUERY's ready method to know when all dom elements are rendered
        $(document).ready(function () {
            // set an on click on the button
            $("form").submit(function (evt) {
                evt.preventDefault();
                var data = $("#text").val();
                $("#text").val('');
                // get the time if clicked via a ajax get queury
                if (data != '') {
                    sendChat(data, function () {
                        //alert("dane");
                    });
                }
            });
            setInterval(function () {
                getNewChats(0);

            }, 2000);
        });

    </script>
<?php
} ?>