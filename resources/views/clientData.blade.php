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
                            <h6 class="font-weight-bolder mb-0">Client Data</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">task</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Client Data</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Client</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2" id='client-form' style="display: none">
                        <form action="{{ route('insert.client.data') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="client-name">Client Name:</label>
                                            <input type="text" class="form-control" name="client_name" placeholder="Please Enter Client Name">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="poc-name">POC Name:</label>
                                            <input type="text" class="form-control" name="poc_name" placeholder="Please Enter POC Name">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="Contact">Contact:</label>
                                            <input type="number" class="form-control" name="contact" placeholder="Please Enter Contact">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="city">City:</label>
                                            <input type="text" class="form-control" name="city" placeholder="Please Enter City">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer my-3">
                                <button type="submit" class="btn btn-primary">Insert</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-header p-3 pt-2 " id='client-table' style="display: none">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Client Name:
                                          <span class="badge text-black fw-bold" id="client_name"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            POC Name:
                                          <span class="badge text-black fw-bold " id="poc_name"></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Contact:
                                          <span class="badge text-black fw-bold " id='contact'></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            City:
                                          <span class="badge text-black fw-bold " id="city"></span>
                                        </li>
                                    </ul>
                                </div>

                            </div>
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
        function checkSessionStatus(){
            //var routeCallCenterStatus = "{{ route(auth()->user()->designation == 'Supervisor' ? 'call.center.status' : 'agent.call.center.status') }}";
            $.ajax({
                url:"{{ route('check.session.status') }}", // The route to your export controller
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        $('#client-form').hide();
                        $('#client-table').show();
                        $('#client_name').text(response.data.client_name)
                        $('#poc_name').text(response.data.poc_name)
                        $('#contact').text(response.data.contact)
                        $('#city').text(response.data.city)
                    } else {
                        $('#client-form').show();
                        $('#client-table').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred');
                }
            });
        }
        checkSessionStatus();
        setInterval(checkSessionStatus, 5000);
    });
</script>


