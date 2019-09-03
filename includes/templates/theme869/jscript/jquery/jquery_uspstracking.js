
$(document).ready(function () {
    $('#trackingForm').submit(function (event) {
        event.preventDefault();
        $('input[type="submit"]').val('Processing...');
        $.ajax(
                {
                    url: 'ajax/usps_track_confirm.php?tracking_number=' + $('#tracking_field').val(),
                    type: "POST",
                    success: function (data)
                    {
                        $('#trackingInformation').html(data);
                        $('input[type="submit"]').val('Submit');

                    }
                });
    });
    $('#sideboxTrackingInformation').hide();
    $('#sidebox_tracking_form').submit(function (event) {
        event.preventDefault();
        $('input[type="submit"]').val('Processing...');
        $.ajax(
                {
                    url: 'ajax/usps_track_confirm.php?tracking_number=' + $('#sidebox_tracking_field').val(),
                    type: "POST",
                    success: function (data)
                    {
                        var trackingCloseDiv = '<div id="uspsSideboxTrackingClose"><a id="closeSideboxTracking" href=""javascript:history.go(0)">Close</a></div>';
                        $('#sideboxTrackingInformation').show();
                        $('#sideboxTrackingInformation').html(trackingCloseDiv + data);
                        $('input[type="submit"]').val('Submit');

                    }
                });
    });
});