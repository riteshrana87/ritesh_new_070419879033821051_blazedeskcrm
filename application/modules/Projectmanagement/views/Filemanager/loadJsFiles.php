<script type="text/javascript">
    $(document).ready(function () {

        var oTable = $('#costTable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": '<?php echo base_url($project_view . '/listCosts'); ?>',
            "sPaginationType": "full_numbers",
            "iDisplayStart ": 20,
            "bFilter": true,
            "oLanguage": {
                  "sProcessing": "<img src='<?php echo base_url('uploads/dist/img/loading-sm.gif'); ?>'>"
            },
                    
            aoColumns: [
                {data: "cost_name", "searchable": true, aTargets: [0]},
                {data: "cost_type"},
                {data: "ammount"},
                {data: "supplier_name"},
                {data: "status"},
                {data: "Actions","sortable":false} //refers to the expression in the "More Advanced DatatableModel Implementation"
            ],
            "columnDefs": [{
                    "targets": 0,
                    "searchable": true
                }],
            "fnInitComplete": function () {
                //oTable.fnAdjustColumnSizing();
            },
            'fnServerData': function (sSource, aoData, fnCallback) {
                $.ajax
                        ({
                            'dataType': 'json',
                            'type': 'POST',
                            'url': sSource,
                            'data': aoData,
                            'success': fnCallback
                        });
            }
        });
    });
</script>
<script>
    
    //ajax popup open
        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
            function (e) {
                $('#ajaxModal').remove();
                e.preventDefault();
                var $this = $(this)
                        , $remote = $this.data('remote') || $this.attr('href')
                        , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.load($remote);
            }
    );
</script>
