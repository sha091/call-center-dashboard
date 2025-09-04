@include('includes.header')
@include('includes.sidebar')

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
                            <h6 class="font-weight-bolder mb-0">Campaign</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Campaign</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Campaign Records</li>
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
                                <button type="button" class="btn btn-outline-success float-end" data-bs-toggle="modal" data-bs-target="#myModal">Create Campaign</button>
                                <!-- Modal -->
                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Create Campagin</h5>
                                                <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('campaign.create') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Campaign Name</label>
                                                                <input type="text" class="form-control" name="campaign_name">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="my-0">
                                                                        <label>Upload Campaign file</label>
                                                                        <input type="file" class="form-control" name="csv_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 pb-2">
                                                                        <a href="{{ route('campaign.download.file') }}" class="float-end text-decoration-none">Download Simple File</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-12 col-sm-12 my-2">
                                                            <div class="form-group">
                                                                <label for="dateInput">Select Options</label>
                                                                <select class="form-select" name="option" id="optionSelect">
                                                                    <option value="now">Now</option>
                                                                    <option value="schedule">Schedule</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div id="scheduleContainer" class="row"></div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">                                                    
                                                    <button type="submit" class="btn btn-primary">create</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="table-responsive">
                            <table  class="table table-success table-striped table-bordered  dt-responsive nowrap text-center h6" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Campaign Name</th>
                                        <th>Campaign ID</th>
                                        <th>Campaign Type</th>
                                        <th>Campaign Start Time</th>
                                        <th>Campaign End Time</th>
                                        <th>Campaign Status</th>
                                        <th>Limit</th>
                                        <th>Action</th>
                                        <th>Prompt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($get_campaign as $key => $value)                                    
                                        <tr>                                            
                                            <td>{{ $value->campaign_name }}</td>
                                            <td>{{ $value->campaign_id }}</td>
                                            <td>{{ $value->call_type }}</td>
                                            <td>{{ $value->camp_start_time }}</td>
                                            <td>{{ $value->camp_end_time }}</td>
                                            <td>{{ $value->runningStatus }}</td>
                                            <td>{{ $value->call_limit }}</td>
                                            <td>
                                                <button class="btn btn-primary bg-gradient text-light badge campaign-toggle-btn" id="campaign_status_{{ $value->id }}"  data-id="{{ $value->id }}" data-status="{{ $value->status }}" @if($value->status == 2) disabled @endif >
                                                    @if ( $value->status == 0)
                                                        start
                                                    @else
                                                        pause
                                                    @endif
                                                </button>
                                                <button class="btn btn-danger text-light badge" @if($value->status == 2) disabled @endif >
                                                    delete
                                                </button>
                                                <button class="btn btn-success text-light badge" data-bs-toggle="modal" data-bs-target="#uploadModel" data-id="{{ $value->campaign_id }}" @if($value->status == 2) disabled @endif>
                                                    upload
                                                </button>
                                            </td>
                                            <td>
                                                <audio controls style="height: 35px;min-width: 250px;vertical-align: middle;">
                                                    <source src={{ asset('storage/app/public/campaign_prompts/'.$value->cc_id.'/'.$value->prompt.'.wav' ) }} type="audio/wav">
                                                    Your browser does not support the audio element.
                                                  </audio>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Data not found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div id="paginationLinks">
                                {{ $get_campaign->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>                                      
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="uploadModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Prompt</h5>
                    <button type="button" class="btn btn-sm btn-danger badge rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="campaign_id" id="campaignIdInput" value="">
                    <div class="modal-body">                        
                        <div class="row">                                                
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="my-0">
                                            <label>Upload Campaign Prompt</label>
                                            <input type="file" class="form-control" name="prompt" accept="audio/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="oldPrompt">Select old prompt's</label>
                                    <select class="form-select" name="option" id="oldPrompt">
                                        <option value="false">Select Prompt</option>
                                        @foreach ($get_campaign as $audio)
                                            <option value="{{ $audio->prompt }}">{{ $audio->prompt }}</option>
                                        @endforeach                                       
                                    </select>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="modal-footer">                                                    
                        <button type="submit" class="btn btn-success bg-gradient text-white">upload</button>
                    </div>
                </form>
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
        $('#optionSelect').on('change', function() {
            var selectedValue = $(this).val();
            // Remove previously added div if it exists
            
            if (selectedValue === 'schedule') {
                // Create and append a new div
                var newDiv = `
                <div class="col-md-3 col-lg-3 col-sm-3 my-2">
                    <div class="form-group">
                        <label for="dateInput">Start Date</label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                </div>

                <div class="col-md-3 col-lg-3 col-sm-3 my-2">
                    <div class="form-group">
                        <label for="dateInput">Start time</label>
                        <input type="time" class="form-control" name="start_time">
                    </div>
                </div>

                <div class="col-md-3 col-lg-3 col-sm-3 my-2">
                    <div class="form-group">
                        <label for="dateInput">End Date</label>
                        <input type="date" class="form-control" name="end_date">
                    </div>
                </div>

                <div class="col-md-3 col-lg-3 col-sm-3 my-2">
                    <div class="form-group">
                        <label for="dateInput">End time</label>
                        <input type="time" class="form-control" name="end_time">
                    </div>
                </div>

                `;            
                $('#scheduleContainer').append(newDiv);
            }else{
                $('#scheduleContainer').empty();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.campaign-toggle-btn').on('click', function() {
            var button = $(this);
            var campaignId = button.data('id');
            var currentStatus = button.data('status');
    
            // Toggle logic: 0 -> 1 (start), 1 -> 0 (pause)
            var newStatus = (currentStatus == 0) ? 1 : 0;
            
            $.ajax({
                url: '{{ route('update.campaign.status') }}', // Update to your actual route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: campaignId,
                    status: newStatus
                },
                success: function(response) {
                    // Update button text and data-status
                    button.data('status', newStatus);
                    button.text(newStatus == 0 ? 'start' : 'pause');
                    location.reload();
                },
                error: function() {
                    alert('Status update failed!');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#uploadModel').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var campaignId = button.data('id');  // Get data-id value

            // Set it to the hidden input
            $('#campaignIdInput').val(campaignId);

            // Optional: Debug
            console.log("Campaign ID:", campaignId);
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Function to check if the submit button should be enabled
        function toggleSubmitButton() {
            var isFileSelected = $('input[name="prompt"]').val() !== '';
            var isPromptSelected = $('#oldPrompt').val() !== 'false';
            var isSubmitEnabled = isFileSelected || isPromptSelected;
            $('button[type="submit"]').prop('disabled', !isSubmitEnabled);
        }
    
        // Event listener for file input change
        $('input[name="prompt"]').change(function() {
            if ($(this).val()) {
                $('#oldPrompt').prop('disabled', true);
                // Change the form action to the uploadPrompt route
                $('form').attr('action', '{{ route("upload.prompt") }}');
            } else {
                $('#oldPrompt').prop('disabled', false);
                // Set the form action to empty or default if file is not selected
                $('form').attr('action', '#');
            }
            toggleSubmitButton();
        });

        // Event listener for dropdown change
        $('#oldPrompt').change(function() {
            if ($(this).val() !== 'false') {
                $('input[name="prompt"]').prop('disabled', true);
                // Change the form action to the uploadPreviousPrompt route
                $('form').attr('action', '{{ route("upload.prev.prompt") }}');
            } else {
                $('input[name="prompt"]').prop('disabled', false);
                // Set the form action to empty or default if no prompt is selected
                $('form').attr('action', '#');
            }
            toggleSubmitButton();
        });
    
        // Initial check to set the correct state
        toggleSubmitButton();
    });
</script>

