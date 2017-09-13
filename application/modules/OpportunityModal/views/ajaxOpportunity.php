<table id="datatable1" class="table table-striped">
    <thead>
        <tr>
            <th><?= lang('name') ?></th>
            <th><?= lang('id') ?></th>
            <th><?= lang('no_of_contacts') ?></th>
            <th><?= lang('primary_contact') ?></th>
            <th><?= lang('client_since') ?></th>
            <th><?= lang('contract_expiration') ?></th>
            <th><?= lang('status') ?></th>
            <th><?= lang('actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
            <?php foreach ($prospect_data as $data) { ?>
                <tr>
                    <td><?php echo $data['prospect_name']; ?></td>
                    <td><?php echo $data['prospect_auto_id']; ?></td>
                    <td class="text-center"><?php echo $data['contact_count']; ?></td>
                    <td><?php echo $data['contact_name']; ?></td>
                    <td>Not a client yet</td>
                    <td>Not a client yet</td>
                    <td>Opportunity<?php //echo ($data['status']==1)?'Active':'Inactive';      ?></td>
                    <td><a href="#"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Opportunity', 'edit')) { ?><button class="edit_opportunity" id="edit_opportunity" type="button" data-id=<?php echo $data['prospect_id']; ?>  ><i class="fa fa-pencil fa-x bluecol"></i></button><?php } ?>&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Opportunity', 'edit')) { ?><a href="<?php echo base_url($opportunity_view . '/deletedata?id=' . $data['prospect_id']); ?>"><i class="fa fa-remove fa-x redcol"></i></a><?php } ?></td>
                </tr>
            <?php } ?>
        <?php } ?> 
    </tbody>
</table>

<script>
    $('#datatable1').DataTable();
</script>