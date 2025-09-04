<script>
    $(document).ready(function() {
        function callCenterStatus(){
            var routeCallCenterStatus = "{{ route(auth()->user()->designation == 'Supervisor' ? 'call.center.status' : 'agent.call.center.status') }}";
            $.ajax({
                url:routeCallCenterStatus, // The route to your export controller
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        $('#call_center_status').html('Call Center Is On');
                    } else {
                        $('#call_center_status').html('Call Center Is Off');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred');
                }
            });
        }
        callCenterStatus();
        setInterval(callCenterStatus, 5000);
    });
</script>

<script>
    $(document).ready(function() {
        function callCenterStatus(){
            var routeCallCenterStatus = "{{ route(auth()->user()->designation == 'Supervisor' ? 'update.and.get.queue' : 'agent.update.and.get.queue') }}";
            $.ajax({
                url: routeCallCenterStatus, // The route to your export controller
                type: 'GET',
                success: function(response) {
                    let status_str = null;
                    if(response['status'] == "-1"){
                        status_str = "Please Set WorkCodes";
                        pic = "fa-solid fa-clipboard-list";
                    }else if(response['status'] == "1"){
                        status_str = "Agent is Free";
                        pic = "fa-solid fa-phone";
                    }else if(response['status'] == "2"){
                        status_str = "Ringing/"+response['caller_id'];
                        pic = "fa-solid fa-phone-volume";
                    }else if(response['status'] == "3"){
                        pic = "fa-solid fa-phone";
                        status_str = response['caller_id'];
                    }else if(response['status'] == '5'){
                        status_str = "Please login your soft phone.";
                        pic = "fa-solid fa-mobile-retro";
                    }else{
                        status_str = "Agent is Free";
                        pic = "fa-solid fa-phone";
                    }

                    if(response['status'] == "-1"){
                        $('#agent_status').html("<h6 type='button' class='font-weight-bolder mb-0 text-white text-md' data-bs-toggle='modal' data-bs-target='#workCodeModal'><i class='"+pic+"'></i>&nbsp;"+status_str+"</h6>")
                    }else{
                        $('#agent_status').html("<h6 class='font-weight-bolder mb-0 text-white text-md'><i class='"+pic+"'></i>&nbsp;"+status_str+"</h6>")
                    }


                },
                error: function(xhr, status, error) {
                    console.log('An error occurred');
                }
            });
        }
        callCenterStatus();
        setInterval(callCenterStatus, 5000);
    });
</script>

<script>

    $(document).ready(function() {
    // When a list item in the dropdown is clicked
    $('.d-item').on('click', function() {
        // Get the text of the clicked item
        var selectedValue = $(this).text();
        var routeCallCenterStatus = "{{ route(auth()->user()->designation == 'Supervisor' ? 'update.break.time' : 'agent.update.break.time') }}";
        $.ajax({
            url: routeCallCenterStatus, // The route to your export controller
            type: 'GET',
            data:{
                "selectedValue":selectedValue,
                "admin_id":{{ Session::get('admin_id') }}
            },
            success: function(response) {
                if(response){
                    $('#TimeStatus').html("<i class='fa-solid fa-clock px-2' aria-hidden='true'></i>"+selectedValue);
                }
            },
            error: function(xhr, status, error) {
                console.log('An error occurred');
            }
        });
        // You can also update the button text if needed
    });
    });


</script>

{{-- <script type="text/javascript">
    $(document).ready(function() {
        var inactivityTime = 15 * 60 * 1000; // 15 minutes in milliseconds
        var timeout;  // To hold the timer reference

        // Function to reset the timer
        function resetTimer() {
            clearTimeout(timeout);  // Clear previous timeout
            timeout = setTimeout(function() {
                // Trigger logout action if the user is inactive for 15 minutes
                logoutUser();
            }, inactivityTime);
        }

        // Function to handle logout
        function logoutUser() {
            window.location.href = "{{ route('logout') }}";
        }

        // Listen for any mouse movement or keypress activity and reset the timer
        $(this).on('mouseover mousedown touchstart click keydown mousewheel DDMouseScroll wheel scroll', function() {
            resetTimer();
        });

        // Initialize the timer
        resetTimer();
    });
</script> --}}

<script>
    $(document).ready(function(){
        // Set up interval for get_notification_alert
        setInterval(get_notification_alert, 5000);

    });


    function get_notification_alert(is_seen = 0) {

            $.ajax({
                url: "{{ route(auth()->user()->designation == 'Supervisor' ? 'fetch.notification.alert' : 'agent.fetch.notification.alert') }}",  // Laravel route
                type: "GET",
                data: {
                    receiver_id: "{{ auth()->user()->admin_id }}",  // Get the current user ID via Laravel's Auth system
                    is_seen: is_seen
                },
                dataType: 'json',
                success: function(data) {
                    if (data != 0) {
                        console.log(data);
                        // Display Toastr notification
                        toastr.info(data.text, "New Notification", {
                            closeButton: true,
                            positionClass: "toast-top-full-width",  // Customize the position
                            timeOut: 0,  // Set how long the toast will appear (in milliseconds)
                            extendedTimeOut: 0
                        });
                    }
                },
            });

    }


</script>
