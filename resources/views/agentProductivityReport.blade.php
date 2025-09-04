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
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Reports</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Agent Productivity Report</li>
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
                        <div class="row my-3">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group" style="width: inherit;">
                                    <label for="dateInput">To Date:</label>
                                    <input type="date" class="form-control" id="startDate"/>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="dateInput">Options</label>
                                    <select class="form-select" id='agent' name="admin_id">
                                        <option value="" >Please Select Agent</option>
                                        @foreach ($agentDropDown as $agent)
                                            <option value="{{ $agent->admin_id }}" >{{ $agent->full_name }} ({{ $agent->agent_exten }},{{ $agent->status =='1' ? 'Active' : 'Inactive' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2">
                                <div class="form-group mx-3">
                                    <br>
                                    <button type="button" class="btn btn-primary m-2" id='search'>Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Working Time
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" >
                                    <div class="table-wrapper">
                                        <table id="working-time"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Agent Name</th>
                                                    <th>Online Time</th>
                                                    <th>Offline Time</th>
                                                    <th>Time Duration</th>
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
                                        Break Times Summary
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" >
                                    <div class="table-wrapper">
                                        <table id="break-time-stats"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>CRM Status</th>
                                                    <th>Time Difference</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        On Call & Busy Times
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" >
                                    <div class="table-wrapper">
                                        <table id="call-and-busy-time"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Agent Name</th>
                                                    <th>Call Type</th>
                                                    <th>No of Calls</th>
                                                    <th>Abandoned Calls</th>
                                                    <th>On Call Time</th>
                                                    <th>Busy Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Break Times
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" >
                                    <div class="table-wrapper">
                                        <table id="break-time"  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Agent</th>
                                                    <th>CRM Status</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Time Difference</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="accordion-item">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        Work Timing Distribution
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse show" aria-labelledby="headingSix" >
                                    <div class="table-wrapper">

                                    </div>
                                </div>
                            </div> --}}

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
    $(document).ready( function() {
        var now     = new Date();
        var day     = ("0" + now.getDate()).slice(-2);
        var month   = ("0" + (now.getMonth() + 1)).slice(-2);
        var today   = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $('#toDate').val(today);
    });
</script>

<script>
    $(document).ready(function() {
        function fetchData(){
            $.ajax({
                url: "{{ route('agent.productivity.working.time') }}", // The route defined earlier
                method: 'GET',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.onlineTime}</td>
                            <td class='${item.is_colour}'>${item.login_time}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#working-time tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
        // Initial fetch
        fetchData();

        // Set interval to refresh every 20 seconds (20000 milliseconds)
        //setInterval(fetchData, 20000);

    });
</script>

<script>
    $(document).ready(function() {
        function fetchData(){
            $.ajax({
                url: "{{ route('agent.productivity.break.time') }}", // The route defined earlier
                method: 'GET',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.crm_status}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#break-time-stats tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
        // Initial fetch
        fetchData();

        // Set interval to refresh every 20 seconds (20000 milliseconds)
        //setInterval(fetchData, 20000);

    });
</script>

<script>
    $(document).ready(function() {
        function fetchData(){
            $.ajax({
                url: "{{ route('agent.productivity.call.buzy.time') }}", // The route defined earlier
                method: 'GET',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.call_type}</td>
                            <td>${item.cnt}</td>
                            <td>${item.abandon_calls}</td>
                            <td>${item.call_duration}</td>
                            <td>${item.busy_duration}</td>
                        </tr>`;
                    });
                    $('#call-and-busy-time tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
        // Initial fetch
        fetchData();

        // Set interval to refresh every 20 seconds (20000 milliseconds)
        //setInterval(fetchData, 20000);

    });
</script>

<script>
    $(document).ready(function() {
        function fetchData(){
            $.ajax({
                url: "{{ route('agent.productivity.b.t') }}", // The route defined earlier
                method: 'GET',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.status}</td>
                            <td>${item.start_time}</td>
                            <td>${item.end_time}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#break-time tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });
        }
        // Initial fetch
        fetchData();

        // Set interval to refresh every 20 seconds (20000 milliseconds)
        //setInterval(fetchData, 20000);

    });
</script>

<script>
     $(document).ready(function() {
        $('#search').on('click', function() {
            // Show a loading spinner (optional)
            $('#search').prop('disabled', true).text('Searching...');
            let startDate = $('#startDate').val();
            let admin_id = $('#agent option:selected').val();
            // Send AJAX request to trigger export
            $.ajax({
                url: "{{ route('agent.productivity.working.time') }}", // The route to your export controller
                type: 'GET',
                data:{
                    "startDate":startDate,
                    "admin_id":admin_id,
                },
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.onlineTime}</td>
                            <td class='${item.is_colour}'>${item.login_time}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#working-time tbody').html(tableBody); // Populate the table
                    $('#search').prop('disabled', false).text('Search');
                },
                error: function(err) {
                    console.error(err);
                }
            });

            $.ajax({
                url: "{{ route('agent.productivity.break.time') }}", // The route defined earlier
                method: 'GET',
                data:{
                    "startDate":startDate,
                    "admin_id":admin_id,
                },
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.crm_status}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#break-time-stats tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });

            $.ajax({
                url: "{{ route('agent.productivity.call.buzy.time') }}", // The route defined earlier
                method: 'GET',
                data:{
                    "startDate":startDate,
                    "admin_id":admin_id,
                },
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.call_type}</td>
                            <td>${item.cnt}</td>
                            <td>${item.abandon_calls}</td>
                            <td>${item.call_duration}</td>
                            <td>${item.busy_duration}</td>
                        </tr>`;
                    });
                    $('#call-and-busy-time tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });

            $.ajax({
                url: "{{ route('agent.productivity.b.t') }}", // The route defined earlier
                method: 'GET',
                data:{
                    "startDate":startDate,
                    "admin_id":admin_id,
                },
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(item) {
                        tableBody += `<tr>
                            <td>${item.agent_name}</td>
                            <td>${item.status}</td>
                            <td>${item.start_time}</td>
                            <td>${item.end_time}</td>
                            <td>${item.duration}</td>
                        </tr>`;
                    });
                    $('#break-time tbody').html(tableBody); // Populate the table
                },
                error: function(err) {
                    console.error(err);
                }
            });

        });
    });
</script>



