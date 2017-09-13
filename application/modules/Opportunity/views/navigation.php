 
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$path = base_url() . 'Account/sendProspectEmail';
?>
<div class="modal-dialog modal-lg" id="myModal">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?php echo $modal_title; ?></h4>
        </div>
        <div class="modal-body">
            <div class=' text-center redcol'><p id="error"></p></div>
            <form id="calculate-route" name="calculate-route" >

                <input type="hidden" id="from" name="from" value="<?php echo $all_records[0]['address1'] . ' ' . $all_records[0]['address2'] . ' ' . $all_records[0]['city'] . ' ' . $all_records[0]['state'] . ' ' . $all_records[0]['country_name']; ?>" required="required" placeholder="An address" size="30" />
                <!--<a id="from-link" href="#">Get my position</a>-->
                <input type="hidden" id="to" name="to" value="<?php echo $company_record[0]['address1'] . ' ' . $company_record[0]['address2'] . ' ' . $company_record[0]['city'] . ' ' . $company_record[0]['state'] . ' ' . $company_record[0]['country_name']; ?>" required="required" placeholder="Another address" size="30" />
                <!--<a id="to-link" href="#">Get my position</a>-->
                <input type="submit" id="submit" value="Get direction" class="hidden"/>
                <div id="map"></div>

            </form>

        </div>	
    </div>    
</div>

<script>

    function calculateRoute(from, to) {

        //var from=$("#from").val();
        //var to=$("#to").val();
        // Center initialized to Naples, Italy
        var myOptions = {
            zoom: 10,
            center: new google.maps.LatLng(40.84, 14.25),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // Draw the map
        var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);

        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
            origin: from,
            destination: to,
            travelMode: google.maps.DirectionsTravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
                directionsRequest,
                function (response, status)
                {
                    if (status == google.maps.DirectionsStatus.OK)
                    {
                        new google.maps.DirectionsRenderer({
                            map: mapObject,
                            directions: response
                        });
                    }
                    else
                        $("#error").html("<?php echo lang('unable_route'); ?>");
                }
        );
    }


    function openMap()
    {
        calculateRoute($("#from").val(), $("#to").val());
    }
    $(document).ready(function () {
        //	calculateRoute($("#from").val(), $("#to").val());
        setTimeout(openMap, 100);

        calculateRoute($("#from").val(), $("#to").val());
        $("#calculate-route").click(function (event) {

            event.preventDefault();
            calculateRoute($("#from").val(), $("#to").val());
    }); 
      });
</script>