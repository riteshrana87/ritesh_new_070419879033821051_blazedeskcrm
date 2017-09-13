<script type="text/javascript">
    $('body').delegate('#searchForm', 'submit', function () {
        data_search('changesearch');
        return false;
    });


    $(document).ready(function () {

        //serch by enter
        $('#search_input').val("<?php echo (isset($searchtext) && $searchtext != '') ? $searchtext : ''; ?>");
        $('#search_input').keyup(function (event) {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }

        });

       
    });
    function data_group(fieldName, data) {
        var groupFieldName = [];
        var groupFieldData = [];
        $('.filter').each(function () {

            if ($(this).val() != '') {
                groupFieldName.push($(this).attr('data-field'));
                groupFieldData.push($(this).val());
            }
        });

        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Projectmanagement/' . $this->viewname); ?>/index/" + uri_segment,
            data: {
                result_type: 'ajax',
                perpage: $("#perpage").val(),
                searchtext: $("#search_input").val(),
                sortfield: $("#sortfield").val(),
                sortby: $("#sortby").val(),
                allflag: 'changesearch',
                groupFieldName: groupFieldName,
                groupFieldData: groupFieldData
            },
            /*beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },*/
            success: function (html) {
                $("#common_div").html(html);
                /*$('#common_div').unblock();*/
            }
        });
        return false;
    }
    //Search data
    function data_search(allflag) {

        var groupFieldName = [];
        var groupFieldData = [];
        $('.filter').each(function () {

            if ($(this).val() != '') {
                groupFieldName.push($(this).attr('data-field'));
                groupFieldData.push($(this).val());
            }
        });
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Meeting'); ?>/index/" + uri_segment,
            data: {
                result_type: 'ajax',
                perpage: $("#perpage").val(),
                searchtext: $("#search_input").val(),
                sortfield: $("#sortfield").val(),
                sortby: $("#sortby").val(),
                allflag: allflag,
                groupFieldName: groupFieldName,
                groupFieldData: groupFieldData
            },
            /*beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },*/
            success: function (html) {
                $("#common_div").html(html);
                /*$('#common_div').unblock();*/
            }
        });
        return false;
    }
    function reset_data() {
        $("#search_input").val("");
        apply_sorting('', '');
        data_search('all');
    }

    function changepages() {
        data_search('');
    }

    function apply_sorting(sortfilter, sorttype) {
        //alert('hi');
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
        var groupFieldName = [];
        var groupFieldData = [];
        $('.filter').each(function () {

            if ($(this).val() != '') {
                groupFieldName.push($(this).attr('data-field'));
                groupFieldData.push($(this).val());
            }
        });
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax',
                perpage: $("#perpage").val(),
                searchtext: $("#search_input").val(),
                sortfield: $("#sortfield").val(),
                sortby: $("#sortby").val(),
                groupFieldName: groupFieldName,
                groupFieldData: groupFieldData
            },
            /*beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },*/
            success: function (html) {
                $("#common_div").html(html);
                $.unblockUI();
            }
        });
        return false;

    });

</script>
