@include('SuperAdmin.includes.header')
@include('SuperAdmin.includes.sidebar')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
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
                                    <li class="breadcrumb-item text-sm d-flex"><i class="material-icons opacity-10">report</i><a class="opacity-5 text-dark ms-2" href="javascript:;">Company</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Company</li>
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
                    <div class="card-header p-3 pt-2">
                        <form role="form" class="text-start" action="{{ route('store.company') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name='company_name' @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Master Number</label>
                                        <input type="number" class="form-control" name='master_number' @error('master_number') is-invalid @enderror" value="{{ old('master_number') }}" required autocomplete="master_number" autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Poc Name</label>
                                        <input type="text" class="form-control" name='poc_name' @error('poc_name') is-invalid @enderror" value="{{ old('poc_name') }}" required autocomplete="poc_name" autofocus>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="my-3">
                                                <input type="file" class="form-control" name='agents' accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            </div>
                                        </div>
                                        <div class="col-12 pb-2">
                                                <a href="{{ route('download.file') }}" class="float-end text-decoration-none">Download Simple File</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="input-group input-group-outline my-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name='email' @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6">
                                    <div class="input-group input-group-outline my-3">
                                        <textarea class="form-control" name="address" rows="3" placeholder="Address"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="my-3 float-end">
                                        <button type="submit" class="btn bg-gradient-success"> Create Comapny</button>
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
@include('SuperAdmin.includes.footer')



