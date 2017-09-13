<?php
$salesSortDefault = '<i class="fa fa-sort"></i>';
$salesSortAsc = '<i class="fa fa-sort-desc"></i>';
$salesSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($salessortOrder == "asc")
    $salessortOrder = "desc";
else
    $salessortOrder = "asc";
?>     
<div class="whitebox" id="salesTable">
    <div class="table table-responsive">
        <table class="table table-striped dataTable" role="grid">
            <thead>
                <tr>
                    <th class="salesSort col-md-2">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=prospect_name&sortOrder=<?php echo $salessortOrder ?>">

                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'prospect_name') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'prospect_name') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>
                            <?php echo lang('name') ?>
                        </a>
                    </th>
                    <th class="salesSort col-md-1">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=prospect_auto_id&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'prospect_auto_id') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'prospect_auto_id') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>
                            <?php echo lang('id') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=contact_count&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'contact_count') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'contact_count') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>
                            <?php echo lang('no_of_contacts') ?>
                        </a>
                    </th>
                    <th class="salesSort col-md-2">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=contact_name&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'contact_name') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'contact_name') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>                                   
                            <?php echo lang('primary_contact') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=creation_date&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'creation_date') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'creation_date') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?> 
                            <?php echo lang('client_since') ?>
                        </a>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=creation_date&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'creation_date') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'creation_date') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>                                    
                            <?php echo lang('contract_expiration') ?>
                            <a/>
                    </th>
                    <th class="salesSort">
                        <a  href="<?php echo base_url(); ?>SalesOverview/salesAjaxList/<?php echo $salePage ?>/?orderField=status_type&sortOrder=<?php echo $salessortOrder ?>">
                            <?php
                            if ($salessortOrder == 'asc' && $salessortField == 'status_type') {
                                echo $salesSortAsc;
                            } else if ($salessortOrder == 'desc' && $salessortField == 'status_type') {
                                echo $salesSortDesc;
                            } else {
                                echo $salesSortDefault;
                            }
                            ?>                                     
                            <?php echo lang('status') ?>
                        </a>
                    </th>
                    <th></th>

                    <th><?php echo lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                    <?php foreach ($prospect_data as $data) { ?>
                        <tr id="opportunity_<?php echo $data['prospect_id']; ?>">
                            <td><?php echo $data['prospect_name']; ?></td>
                            <td><?php echo $data['prospect_auto_id']; ?></td>
                            <td class="text-center"><?php if ($data['contact_count']) {
                    echo $data['contact_count'];
                } else {
                    echo '0';
                } ?></td>
                            <td><?php echo $data['contact_name']; ?></td>
                            <td><?php echo configDateTime($data['creation_date']); ?></td>
                            <td><?= $this->lang->line('not_a_client_yet') ?></td>
                            <td><?php echo $status[$data['status_type']]; ?></td>
                                <?php $redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>

                                <?php if ($data['status_type'] == 1) { ?>
                                <td><?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-green"  data-href="javascript:;" onclick="ConvertWin('<?php echo $data['prospect_id']; ?>');" ><?= $this->lang->line('win') ?></a><?php } ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                <?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-danger"  data-href="javascript:;" onclick="ConvertLost('<?php echo $data['prospect_id']; ?>');" ><?= $this->lang->line('lost') ?></a><?php } ?></td>
                                <td class="bd-actbn-btn"><?PHP if (checkPermission('Opportunity', 'view')) { ?><a href="<?= base_url('Opportunity/viewdata/' . $data['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                                <?PHP if (checkPermission('Opportunity', 'edit')) { ?><a data-href="<?php echo base_url('Opportunity/editdata/' . $data['prospect_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
            <?PHP if (checkPermission('Opportunity', 'delete')) { ?> <a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_opportunity('<?php echo $data['prospect_id']; ?>');" ><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                                </td>
                                <?php } ?>
                                <?php if ($data['status_type'] == 2) { ?>
                                <td><?PHP if (checkPermission('Lead', 'edit')) { ?><a class="btn btn-sm btn-green" data-href="javascript:;" onclick="convertToOpporutnity('<?php echo $data['prospect_id']; ?>');" ><?= $this->lang->line('qualify_lead') ?></a><?php } ?></td>

                                <td class="bd-actbn-btn"> <?PHP if (checkPermission('Lead', 'view')) { ?><a href="<?= base_url('Lead/viewdata/' . $data['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                                <?PHP if (checkPermission('Lead', 'edit')) { ?><a data-href="<?php echo base_url('Lead/editdata/' . $data['prospect_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
            <?PHP if (checkPermission('Lead', 'delete')) { ?> <a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_lead('<?php echo $data['prospect_id']; ?>');" ><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                                </td>
                                <?php } ?>
                                <?php if ($data['status_type'] == 3) { ?>
                                <td></td>

                                <td class="bd-actbn-btn"><?PHP if (checkPermission('Account', 'view')) { ?><a href="<?= base_url('Account/viewdata/' . $data['prospect_id']) ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            <?PHP if (checkPermission('Account', 'edit')) { ?><a data-href="<?php echo base_url('Account/editdata/' . $data['prospect_id']); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                            <?PHP if (checkPermission('Account', 'delete')) { ?> <a data-href="javascript:;" onclick="delete_client('<?php echo $data['prospect_id']; ?>');" > <i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                                </td>
        <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="text-center"><?= lang('common_no_record_found') ?></td>
                    </tr>
<?php } ?> 
            </tbody>
        </table>

    </div>
    <div class="row">
        <div class="col-md-12 text-center">
<?php echo (!empty($paginationSales)) ? $paginationSales : ''; ?>
        </div>
    </div>
    <div class="clr"></div>
</div>


