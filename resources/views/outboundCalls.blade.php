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
                            <h6 class="font-weight-bolder mb-0">Outbound</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Outbound</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Outbound Call Genrate</li>
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
                    <div class="card-body p-3">
                        
                        <form method="POST" action="{{ route(auth()->user()->designation == 'Supervisor' ? 'outbound.call' : 'agent.outbound.call') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Caller id</label>
                                        <input type="number" class="form-control" name='caller_id' required>
                                    </div>
                                </div>
                                <div class="col-md-4 p-2">
                                    <div class="input-group input-group-outline my-3">
                                        <button type="submit" class="btn btn-success rounded-pill ">
                                            <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">phone</i></li>
                                        </button>
                                    </div>
                                </div>
                            </div> 
                        </form>                    
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




