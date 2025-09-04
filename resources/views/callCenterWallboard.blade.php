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
                            <h6 class="font-weight-bolder mb-0">Reports</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                    <li class="breadcrumb-item text-sm d-flex"><i
                                            class="material-icons opacity-10">report</i><a
                                            class="opacity-5 text-dark ms-2" href="javascript:;">Call Agent
                                            Dashboard</a></li>
                                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Wall-Board
                                    </li>
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

                        <div class="row g-6 ">
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body bg-info bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Total Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['TotalCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body bg-secondary  bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Total Inbound Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['InboundCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body bg-warning bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Inbound Calls Answer</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['InboundCallsAnswer']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body bg-primary bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Missed Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['DropCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-xl-3 col-sm-6 col-12 mt-5">
                                <div class="card">
                                    <div class="card-body bg-danger  bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Total Outbound Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['OutboundCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 col-12 mt-5">
                                <div class="card">
                                    <div class="card-body bg-success bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Transfer Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['TransferCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 col-12 mt-5">
                                <div class="card">
                                    <div class="card-body bg-dark  bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Agents on call</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['AgentCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 col-12 mt-5">
                                <div class="card">
                                    <div class="card-body bg-info  bg-gradient">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-white text-sm d-block mb-2">Off Time Calls</span>
                                                <span class="h3 font-bold mb-0 text-white ">{{ number_format($data['OffTimeCalls']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{-- <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Calls (I+O+Campaign+Shift+OffTime+Transfer)
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['TotalCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Transfer Calls
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['TransferCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Shift Calls
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['ShiftCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Inbound Calls Answer
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['InboundCallsAnswer']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Drop Calls
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['DropCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Campaign Calls
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['CampaignCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Inbound Calls(Answer + Drop + Shift + Transfer)
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['InboundCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Outbound Calls (Success + Unsuccess)
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['OutboundCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Agents on call
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['AgentCalls']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Off Time Calls
                                <span
                                    class="badge bg-primary rounded-pill">{{ number_format($data['OffTimeCalls']) }}</span>
                            </li>
                        </ul> --}}

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
