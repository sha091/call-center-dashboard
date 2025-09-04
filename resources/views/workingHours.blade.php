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
                            <h6 class="font-weight-bolder mb-0">Settings</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Settings</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Working Hours</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-5 ">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4 ">
                <div class="card">
                    <div class="card-header p-3 pt-2 bg-gradient bg-white">
                        <div class="row my-1" >
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <!-- Button trigger modal -->
                                <h3 class="text-success p-3">Time Conditions</h3>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-6 p-3">
                                <!-- Button trigger modal -->
                                <a href="{{ route('settings.getWorkingHours', ['cc_id' => Session::get('cc_id')]) }}" class="btn btn-outline-success float-end ">Edit</a>
                                <a href="{{ route('settings.resetWorkingHours', ['cc_id' => Session::get('cc_id')]) }}" class="btn btn-outline-success float-end ">Reset</a>
                            </div>
                        </div>
                        <div class="container  py-4">
                            <form>

                                <div class="row my-1">
                                    <div class="col-2 offset-1">
                                        <span class="text-danger">Days</span>
                                    </div>
                                    <div class="col-2 offset-1">
                                        <span class="text-danger">Start Time</span>
                                    </div>
                                    <div class="col-2 offset-1">
                                        <span class="text-danger">End Time</span>
                                    </div>
                                </div>
                                @foreach ($hours as $value)
                                    <div class="row my-4">
                                        <div class="col-2 offset-1">
                                            <span class="text-mute">{{ $value->today }}</span>
                                        </div>
                                        <div class="col-2 offset-1">
                                            <input type="text" class="form-control bs-timepicker" value="{{ $value->start_time }}" disabled />
                                        </div>
                                        <div class="col-2 offset-1">
                                            <input type="text" class="form-control bs-timepicker" value="{{ $value->end_time }}" disabled />
                                        </div>
                                    </div>
                                @endforeach

                            </form>
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


<script type="text/javascript">
$(function() {
    $('.bs-timepicker').timepicker();
});
</script>
