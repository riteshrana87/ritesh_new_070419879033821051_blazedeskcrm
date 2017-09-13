
<script type="text/javascript">
    $(document).ready(function () {

        function bindClicks() {
            $("#taskTable ul.tsc_pagination a").click(paginationClick);
        }
        function bindClicksSort() {
            $("body").delegate('th.sortTask a', 'click', (paginationClick));
        }
        function bindClicksSales() {
            $("#salesTable ul.tsc_pagination a").click(paginationClickSales);
        }
        function bindClicksSortSales() {
            $("body").delegate('th.salesSort a', 'click', (paginationClickSales));
        }

        function paginationClick() {
            var href = $(this).attr('href');
            $("#rounded-corner").css("opacity", "0.4");
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    //alert(response);
                    $("#rounded-corner").css("opacity", "1");
                    $("#taskDataId").empty();
                    $("#taskDataId").html(response);
                    bindClicks();
                }
            });
            return false;
        }
        function paginationClickSales() {
            var href = $(this).attr('href');
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: href,
                data: {search:search},
                success: function (response)
                {
                    //alert(response);
                    $("#salesTable").empty();
                    $("#salesTable").html(response);
                    bindClicksSales();
                }
            });
            return false;
        }
        function loadSalesTable()
        {
            var search = $('#search_input').val();
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('SalesOverview/salesAjaxList'); ?>",
                data: {search: search},
                success: function (response)
                {
                    $("#rounded-corner").css("opacity", "1");
                    $("#salesTable").empty();
                    $("#salesTable").html(response);
                    bindClicksSales();
                }
            });
        }
        $('body').delegate('#searchForm', 'submit', function () {

            loadSalesTable();


            return false;
        });

        bindClicks();
        bindClicksSort()
//        loadSalesTable();
        bindClicksSortSales();
        bindClicksSales();
        //Default load
       
    });


</script>
