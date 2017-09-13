<div class="col-md-2 col-sm-3" >
    <div class=" whitebox">
        <div class="bd-mailbox-sidebar">
            <a class="pull-right"></a>
            <ul class="nav bd-mailbox-menu">
                <?php
                $x = 3;
                foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if ($folderName == 'INBOX' || $folderName == 'inbox' || $folderName=='Inbox') {
                        ?>
                        <li><a href="javascript:void(0);" id="<?php echo 0; ?>" class="leftbx <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>" onclick='getMailBoxData("<?php echo $folderName; ?>",<?php echo 0; ?>)' ><?php echo str_replace('[Gmail]/', '', $folderName); ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a></li>
                        <?php
                    }
                }
                foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if ($folderName == '[Gmail]/Sent Mail' || $folderName == 'Sent') {
                        ?>
                        <li><a href="javascript:void(0);" id="<?php echo 1; ?>" class="leftbx <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>" onclick='getMailBoxData("<?php echo $folderName; ?>",<?php echo 1; ?>)' ><?php echo str_replace('[Gmail]/', '', $folderName); ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a></li>
                        <?php
                    }
                }
                foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if ($folderName == '[Gmail]/Drafts' || $folderName == 'Drafts') {
                        ?>
                        <li><a href="javascript:void(0);" id="<?php echo 2; ?>" class="leftbx <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>" onclick='getMailBoxData("<?php echo $folderName; ?>",<?php echo 2; ?>)' ><?php echo str_replace('[Gmail]/', '', $folderName); ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a></li>
                        <?php
                    }
                }
// && ($folderName != '[Gmail]/Drafts' || $folderName != 'Drafts') && ($folderName != '[Gmail]/Sent Mail' || $folderName != 'Sent' )
                foreach ($leftMenuFolderCount as $folderName => $unreadCount) {
                    if (($folderName != 'INBOX' && $folderName != 'inbox' && $folderName != 'Inbox') && ($folderName != '[Gmail]/Drafts' && $folderName != 'Drafts') && ($folderName != '[Gmail]/Sent Mail' && $folderName != 'Sent' && $folderName != '[Gmail]/Trash')) {
                        ?>
                        <li><a href="javascript:void(0);" id="<?php echo $x; ?>" class="leftbx <?php echo str_replace('[Gmail]/', '', $folderName); ?> <?php echo (strtolower($folderName) == strtolower('INBOX')) ? "active" : ""; ?>" onclick='getMailBoxData("<?php echo $folderName; ?>",<?php echo $x; ?>)' ><?php echo str_replace('[Gmail]/', '', $folderName); ?> <span class="badge"><?php echo ($unreadCount > 0) ? $unreadCount : ''; ?></span></a></li>
                        <?php
                    }
                    $x++;
                }
                ?>

    <!-- <li><a href="#">Drafts <span class="badge">42</span></a></li>
    <li><a href="#">spam <span class="badge">42</span></a></li>
    <li><a href="#">sent messages <span class="badge">42</span></a></li>
    <li><a href="#">delete messages <span class="badge">42</span></a></li>
    <li class="bd-side-cust"><a href="#">custom folder 1 <span class="badge">42</span></a></li>-->

            </ul>
            <div class="bd-storage bd-mail-head bd-inbox col-md-12">
                <i class="fa fa-store-ico"></i><span><?php echo $percentageSize; ?><button id="refereshLeftbox"><i class=" bd-refresh-ico"></i></button></span>
            </div>
        </div>
    </div>
</div>

