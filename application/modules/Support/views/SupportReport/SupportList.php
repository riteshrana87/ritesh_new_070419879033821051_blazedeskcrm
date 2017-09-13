<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>

<div class="table table-responsive whitebox">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>" />
    <table id="SupportTable" class="table table-striped dataTable" role="grid" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th <?php
if (isset($sortfield) && $sortfield == 'ticket_subject') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
}
?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('ticket_subject', '<?php echo $sorttypepass; ?>')">

                    <?= $this->lang->line('ticket_subject') ?>
                </th>
                <th <?php
                    if (isset($sortfield) && $sortfield == 'ticket_desc') {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    }
                    ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('ticket_desc', '<?php echo $sorttypepass; ?>')">

                <?= $this->lang->line('ticket_desc') ?>

                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'prospect_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('prospect_name', '<?php echo $sorttypepass; ?>')">

                <?= $this->lang->line('ticket_holder') ?>

                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'status') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')">
            <?= $this->lang->line('status') ?>

                </th>
                <th <?php
            if (isset($sortfield) && $sortfield == 'support_type_id') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('support_type_id', '<?php echo $sorttypepass; ?>')">
            <?= $this->lang->line('type') ?>

                </th>

                <th <?php
            if (isset($sortfield) && $sortfield == 'created_Date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('created_Date', '<?php echo $sorttypepass; ?>')">
        <?= $this->lang->line('create_date') ?>

                </th>
                <th <?php
        if (isset($sortfield) && $sortfield == 'due_date') {
            if ($sortby == 'asc') {
                echo "class = 'sorting_desc'";
            } else {
                echo "class = 'sorting_asc'";
            }
        } else {
            echo "class = 'sorting'";
        }
        ?> tabindex="0" aria-controls="supportreport" rowspan="1" colspan="1" onclick="apply_sorting('due_date', '<?php echo $sorttypepass; ?>')">
<?= $this->lang->line('due_date') ?>
                </th>
            </tr>
        </thead>
        <tbody>
<?php
if (isset($support_report) && count($support_report) > 0) {
    foreach ($support_report as $row) {
        ?>
                    <tr>
                        <td style="width: 15%"><?php echo $row['ticket_subject']; ?></td>
                        <td style="width: 30%"><?php echo $row['ticket_desc']; ?></td>
                        <td style="width: 15%"><?php echo $row['prospect_name']; ?></td>
                        <td><?php echo $row['status_name']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo date("m/d/Y", strtotime($row['created_date'])); ?></td>
                        <td><?php echo date("m/d/Y", strtotime($row['due_date'])); ?></td>
                    </tr>
        <?php
    }
} else {
    ?>
                <tr>
                    <td colspan="11" class="text-center"><?= lang('common_no_record_found') ?></td>
                </tr>
<?php }
?>
        </tbody>
    </table>
    <div class="clearfix visible-xs-block"></div>
    <div id="common_tb" class="no_of_records">
<?php
if (isset($pagination)) {
    echo $pagination;
}
?>
    </div>
</div>





