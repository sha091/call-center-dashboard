@include('includes.header')
@include('includes.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('includes.topbar')
    <!-- End Navbar -->
    <!-- CONTAINER START -->
    <div class="container-fluid py-4">
        <style>
            #pagination {
                text-align: right; /* Aligns the pagination to the right */
            }

            #pagination ul {
                list-style-type: none; /* Removes bullet points */
                padding: 0; /* Removes padding */
                margin: 0; /* Removes margin */
            }

            #pagination li {
                display: inline; /* Displays list items in a line */
                margin-left: 10px; /* Space between items */
            }

            #pagination a {
                text-decoration: none; /* Removes underline from links */
                padding: 6px 10px; /* Adjust padding for a smaller clickable area */
                border: 1px solid #3300c0; /* Adds border */
                border-radius: 4px; /* Rounded corners */
                color: #333; /* Text color */
                font-size: 12px; /* Sets text size to small */
            }

            #pagination a:hover {
                background-color: #f0f0f0; /* Changes background on hover */
            }

        </style>
        <div class="row">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <nav aria-label="breadcrumb">
                            <h6 class="font-weight-bolder mb-0"></h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Call Agent Dashboard</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Agent-State</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <style>
                            .arrow {
                                transition: transform 0.2s;
                            }
                            .collapsed .arrow {
                                transform: rotate(-90deg);
                            }
                            .table-wrapper {
                                max-height: 500px; /* Set your desired height */
                                overflow-y: auto;  /* Enable vertical scrolling */
                                border: 1px solid #dee2e6; /* Optional: border for the wrapper */
                            }
                        </style>

                        <div class="accordion" id="accordionExample">
                            @if (auth()->user()->designation == 'Supervisor')
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Agent Stats
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" >
                                        <div class="table-wrapper">
                                            <table id="Agent-Stats"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <th>CRM Login</th>
                                                        <th>Phone Login</th>
                                                        <th>Call Status</th>
                                                        <th>Status</th>
                                                        <th>Caller No</th>
                                                        <th>Time Duration</br>(Call Status)</th>
                                                        <th>Time Duration</br>(CRM Status)</th>
                                                        {{-- <th>Busy Time</br>(Last Call)</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Queue Stats
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" >
                                        <div class="table-wrapper">
                                            <table id="queue-stats"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Call Id</th>
                                                        <th>Caller Id</th>
                                                        <th>Call Start Time</th>
                                                        <th>Wait In Queue</th>
                                                        <th>Agent Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

			  {{--      <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button  class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Dropped Stats&nbsp;&nbsp;&nbsp;<span id="dropped-text" class="text-danger" ></span>
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" >
                                        <div class="table-wrapper">
                                            <table id="droppedcall-stats"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Caller Id</th>
                                                        <th>Call Time</th>
                                                        <th>Wait In Queue</th>
                                                        <th>Ivr Selection</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div> --}}

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Received Call Stats&nbsp;&nbsp;&nbsp;<span id="received-text" class="text-danger" ></span>
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" >
                                        <div class="table-wrapper">
                                            <table class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Caller Id</th>
                                                        <th>Wait In Queue</th>
                                                        <th>Agent Name</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Agent Stats
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" >
                                        <div class="table-wrapper">
                                            <table id="Agent-Stats"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center " style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <th>CRM Login</th>
                                                        <th>Phone Login</th>
                                                        <th>Call Status</th>
                                                        <th>Status</th>
                                                        <th>Caller No</th>
                                                        <th>Time Duration</br>(Call Status)</th>
                                                        <th>Time Duration</br>(CRM Status)</th>
                                                        {{-- <th>Busy Time</br>(Last Call)</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
</main>

<!--   Core JS Files   -->
@include('includes.footer')
@include('includes.custom')
<script>
    // Add event listeners for the accordion buttons
    const accordionButtons = document.querySelectorAll('.accordion-button');

    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Toggle the arrow on click
            const arrow = this.querySelector('.arrow');
            if (this.classList.contains('collapsed')) {
                arrow.style.transform = 'rotate(-90deg)'; // Collapsed state
            } else {
                arrow.style.transform = 'rotate(0deg)'; // Expanded state
            }
        });
    });
</script>


@if (auth()->user()->designation == 'Supervisor')
<script>
    $(document).ready(function() {
        fetchUsers();
        setInterval(fetchUsers, 20000);
    });
    function fetchUsers(page = 1) {
        $.ajax({
            //url: `{{ route('queue.agent.stats') }}?page=${page}`, // The route defined earlier
            url: `{{ route('queue.agent.stats') }}`,
            method: 'GET',
            success: function(data) {
                let tableBody = '';
                data.forEach(function(item) {
                    tableBody += `<tr>
                            <td>${item.unique_id }</td>
                            <td>${item.caller_id }</td>
                            <td>${item.call_datetime}</td>
                            <td>${item.duration}</td>
                            <td>${item.agent_name}</td>
                            <td>${item.status}</td>
                        </tr>`;
                });
                $('#queue-stats tbody').html(tableBody); // Populate the table
                // $('#pagination').empty();
                // for (let i = 1; i <= data.last_page; i++) {
                //     $('#pagination').append(`<a href="#" onclick="fetchUsers(${i})">${i}</a> `);
                // }
            },
            error: function(err) {
                console.error(err);
            }
        });
    }
</script>


<script>
    $(document).ready(function() {
       // fetchDropped();
       // setInterval(fetchDropped, 20000);
    });

    function fetchDropped(){
        $.ajax({
            url: "{{ route('dropped.call.stats') }}", // The route defined earlier
            method: 'GET',
            success: function(data) {

                let tableBody = '';
                var count = 0;
                data.forEach(function(item) {
                    tableBody += `<tr>
                        <td>${item.caller_id}</td>
                        <td>${item.time}</td>
                        <td>${item.duration}</td>
                        <td>${item.ivr_selection}</td>

                    </tr>`;
                    count++;
                });
                $('#dropped-text').text(" (Total number of Dropped calls:  "+count+")");
                $('#droppedcall-stats tbody').html(tableBody); // Populate the table
            },
            error: function(err) {
                console.error(err);
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        fetchRecived();
        setInterval(fetchRecived, 20000);
    });

    function fetchRecived(){
        $.ajax({
            url: "{{ route('received.call.stats') }}", // The route defined earlier
            method: 'GET',
            success: function(data) {
                let tableBody = '';
                var ReceivedCount = 0;
                data.forEach(function(item) {
                    tableBody += `<tr>
                        <td>${item.caller_id}</td>
                        <td>${item.duration}</td>
                        <td>${item.agent_name}</td>
                        <td>${item.date}</td>
                        <td>${item.time}</td>
                    </tr>`;
                    ReceivedCount++;
                });
                $('#collapseFour tbody').html(tableBody); // Populate the table
                $('#received-text').text(" (Total number of Received calls:  "+ReceivedCount+")");
            },
            error: function(err) {
                console.error(err);
            }
        });
    }
</script>
@endif

<script>
    $(document).ready(function() {
        var routeCallAgentStats = "{{ route(auth()->user()->designation == 'Supervisor' ? 'call.agent.stats' : 'agent.call.agent.stats') }}";
        function fetchData(){
            $.ajax({
                url: routeCallAgentStats, // The route defined earlier
                method: 'GET',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        var route = "{{ route(auth()->user()->designation == 'Supervisor' ? 'outbound.transferCall' : 'agent.outbound.transferCall', ['caller_id' => '']) }}"+item.agent_exten;
                        if(item.is_crm_login == 1 && item.is_busy == 0){
                            row  = "<a href="+route+" class='link-opacity-10-hover'>"+item.full_name+"</a>";
                        }else{
                            row = item.full_name;
                        }
                        tableBody += `<tr>
                            <td>${row}</td>
                            <td class='${item.is_crm_login == 1 ? "text-dark" : "text-danger" }'>${item.is_crm_login == 1 ? "Yes" : "No" }</td>
                            <td class='${item.is_phone_login == 1 ? "text-dark" : "text-danger"  }'>${item.is_phone_login == 1 ? "Yes" : "No" }</td>
                            <td class='${item.is_colour}'>${item.call_status}</td>
                            <td class='${item.is_crm_login == 1 ? "text-success" : "text-danger"  }'>${item.login_status}</td>
                            <td>${item.caller_id}</td>
                            <td>${item.t_duration}</td>
                            <td>${item.crm_status}</td>
                        </tr>`;
                    });
                    $('#Agent-Stats tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
        // Initial fetch
        fetchData();

        // Set interval to refresh every 20 seconds (20000 milliseconds)
        setInterval(fetchData, 20000);

    });
</script>






