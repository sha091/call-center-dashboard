@include('includes.header')
@include('includes.sidebar')
@php
    function differenceInDays($date1, $date2) {
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);

        // Calculate the difference
        $difference = $date2->diff($date1);
        
        // Extract the difference in days, hours, minutes, and seconds
        $diffInDays = $difference->days; // Total days
        $diffInHours = $difference->h; // Remaining hours
        $diffInMinutes = $difference->i; // Remaining minutes
        $diffInSeconds = $difference->s; // Remaining seconds

        // Format hours, minutes, and seconds to be two digits
        $diffInHours = str_pad($diffInHours, 2, "0", STR_PAD_LEFT);
        $diffInMinutes = str_pad($diffInMinutes, 2, "0", STR_PAD_LEFT);
        $diffInSeconds = str_pad($diffInSeconds, 2, "0", STR_PAD_LEFT);

        return "$diffInHours:$diffInMinutes:$diffInSeconds";
    }
    function convertToAmPm($time) {
        // Convert the input time to a Unix timestamp
        $timestamp = strtotime($time);
        
        // Format the timestamp to AM/PM format
        return date('h:i:s A', $timestamp);
    }   
@endphp
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('includes.topbar')
    <!-- End Navbar -->
    <!-- CONTAINER START -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <nav aria-label="breadcrumb">
                            <h6 class="font-weight-bolder mb-0">Reports</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Reports</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Agent Summary Records</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">   
                    <div class="card-header p-3">
                        <div class="row ">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#myModal">Add Filter</button>
                                {{-- <button type="button" class="btn btn-outline-success float-end mx-3" id='exportBtn'>Export Excel</button> --}}
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route(auth()->user()->designation == 'Supervisor' ? 'agent.summary.reports' : 'agents.agent.summary.reports') }}" method="GET">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="dateInput">From Date:</label>
                                                                <input type="date" class="form-control" name="startDate" value="{{ isset($_GET['startDate']) ? $_GET['startDate'] : false }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label for="dateInput">To Date:</label>
                                                                <input type="date" class="form-control" name="endDate" value="{{ isset($_GET['endDate']) ? $_GET['endDate'] : false }}" />
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="javascript:void(0);" type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route(auth()->user()->designation == 'Supervisor' ? 'agent.summary.reports' : 'agents.agent.summary.reports') }}'">Clear</a>
                                                    <button type="submit" class="btn btn-primary">Apply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                     
                        <table id="outbound-reports-cdrs" class="table table-success table-striped table-bordered  dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>CALL DATE</th>
                                    <th>AGENT NAME</th>
                                    <th>ATTEMPTED CALLS</th>
                                    <th>ANSWERED CALLS</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cdrs as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->call_datetime }}</td>
                                        <td>{{ $value->full_name }}</td>
                                        <td>{{ $value->attempted_calls }}</td>
                                        <td>{{ $value->answered_calls }}</td>                                        
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Data not found</td>
                                        </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div id="paginationLinks">
                            {{ $cdrs->appends(request()->query())->links('vendor.pagination.bootstrap-5') }} 
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
$(document).ready(function() {
    $('#exportBtn').on('click', function() {
        // Show a loading spinner (optional)
        $('#exportBtn').prop('disabled', true).text('Exporting...');
        let startDate = "{{ isset($_GET['startDate']) ? $_GET['startDate'] : false }}";
        let endDate = "{{ isset($_GET['endDate']) ? $_GET['endDate'] : false }}";
        let admin_id = "{{ isset($_GET['admin_id']) ? $_GET['admin_id'] : false }}";
        
        // Send AJAX request to trigger export
        $.ajax({
            url: "{{ route('export.outbound.reports') }}", // The route to your export controller
            type: 'GET',
            data:{
                "startDate":startDate,
                "endDate":endDate,
                "admin_id":admin_id,
            },
            success: function(response) {
                // On success, trigger the download (this assumes the server sends the file for download)
                if (response.success) {
                    // You can use window.location to download the file
                    console.log(response.download_url);
                    window.location.href = response.download_url;
                } else {
                    alert('Error exporting data');
                }

                // Reset the button after export
                $('#exportBtn').prop('disabled', false).text('Export Data');
            },
            error: function(xhr, status, error) {
                // Handle errors if any
                alert('An error occurred while exporting data.');
                $('#exportBtn').prop('disabled', false).text('Export Data');
            }
        });
    });
});
</script>


