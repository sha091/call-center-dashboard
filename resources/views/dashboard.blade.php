@include('includes.header')
@include('includes.sidebar')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    @include('includes.topbar')
    <!-- End Navbar -->
    <!-- CONTAINER START -->



        {{-- Missed/Outbound Call Entries START --}}
        <div class="row my-5">

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">
                                @if (Auth::user()->designation == "Supervisor")
                                    login
                                @else
                                    phone
                                @endif
                            </i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0">
                                @if (Auth::user()->designation == "Supervisor")
                                    {{ number_format($dashboard['TotalLogins']) }}
                                @else
                                    {{ number_format($dashboard['TotalCalls']) }}
                                @endif
                            </h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">
                                @if (Auth::user()->designation == "Supervisor")
                                    TOTAL LOGIN
                                @else
                                    TOTAL CALLS
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">
                                @if (Auth::user()->designation == "Supervisor")
                                    phone_in_talk
                                @else
                                    call_made
                                @endif
                            </i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0">
                                @if (Auth::user()->designation == "Supervisor")
                                    {{ number_format($dashboard['LiveCalls']) }}
                                @else
                                    {{ number_format($dashboard['TodayCalls']) }}
                                @endif
                            </h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">
                                @if (Auth::user()->designation == "Supervisor")
                                    LIVE CALLS
                                @else
                                    TODAY CALLS
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">
                                @if (Auth::user()->designation == "Supervisor")
                                    phone
                                @else
                                    call_missed
                                @endif
                            </i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0">
                                @if (Auth::user()->designation == "Supervisor")
                                    {{ number_format($dashboard['TodayCalls']) }}
                                @else
                                    {{ number_format($dashboard['TodayMissedCalls']) }}
                                @endif
                            </h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">
                                @if (Auth::user()->designation == "Supervisor")
                                    TODAY CALLS
                                @else
                                    TODAY MISSED CALLS
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">
                                @if (Auth::user()->designation == "Supervisor")
                                    av_timer
                                @else
                                    tty
                                @endif
                            </i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0">
                                @if (Auth::user()->designation == "Supervisor")
                                    {{ number_format($dashboard['TodayAnswerdCalls']) }}
                                @else
                                    {{ number_format($dashboard['TodayAnswerdCalls']) }}
                                @endif
                            </h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0">
                            <span class="text-success text-sm font-weight-bolder">
                                @if (Auth::user()->designation == "Supervisor")
                                    TODAY ANSWERD CALLS
                                @else
                                    TODAY ANSWERD CALLS
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{-- Missed/Outbound Call Entries END --}}

        {{-- Agent Graph START --}}
        <div class="row">
            <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <nav aria-label="breadcrumb">
                            <h6 class="font-weight-bolder mb-0">Graph</h6>
                        </nav>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div style="width: auto; margin: auto;">
                                    <canvas id="chart-bars"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Agent Graph END --}}

    </div>
    <!-- CONTAINER END -->
</main>

{{-- <form name="dateRangeForm" id="dateRangeForm" action="{{ route('user.dashboard') }}" method="GET" >
    <input type="hidden" name="startDate" id="startDate" value="0"  />
    <input type="hidden" name="endDate" id="endDate" value="0"  />
</form> --}}

<!--   Core JS Files   -->
@include('includes.footer')
@include('includes.custom')

<script>

    var ctx = document.getElementById("chart-bars").getContext("2d");
    if("{{ Auth::user()->designation }}" == 'Supervisor'){
        // var myChart = new Chart(ctx, {
        //     type: 'bar',
        //     data: {
        //         labels: ["READY", "BUSY", "CONNECT","Assignment"],
        //         datasets: [{
        //                 label: 'Call Center status',
        //                 data: [
        //                     {{ number_format(isset($dashboard['TotalLogins'])? $dashboard['TotalLogins']:0 ) }},
        //                     {{ number_format(isset($dashboard['BusyCalls'])? $dashboard['BusyCalls']:0) }},
        //                     {{ number_format(isset($dashboard['LiveCalls'])? $dashboard['LiveCalls']:0) }},
        //                     {{ number_format(isset($dashboard['Assignment'])? $dashboard['Assignment']:0) }}
        //                 ],
        //                 backgroundColor: [
        //                 'rgba(255, 99, 132, 0.2)',
        //                 'rgba(255, 159, 64, 0.2)',
        //                 'rgba(255, 205, 86, 0.2)',
        //                 'rgba(75, 192, 192, 0.2)',
        //                 ],
        //                 borderColor: [
        //                 'rgb(255, 99, 132)',
        //                 'rgb(255, 159, 64)',
        //                 'rgb(255, 205, 86)',
        //                 'rgb(75, 192, 192)',
        //                 ],
        //                 borderWidth: 2
        //             }

        //         ]
        //     },
        //     options: {
        //             scales: {
        //                 x: {
        //                     stacked: true,
        //                 },
        //                 y: {
        //                     stacked: true
        //                 }
        //             },

        //     }
        // });
        var graphDates = @json( isset($dashboard['graphDates'])? $dashboard['graphDates']:0 );
        var count = @json( isset($dashboard['graphCounts'])? $dashboard['graphCounts']:0 );
        var myChart = new Chart(ctx, {
            type: 'line',
            options: {
                scales: {
                xAxes: [{
                    type: 'time',
                }]
                }
            },
            data: {
                labels: graphDates,
                datasets: [{
                label: 'Day Wise Calls',
                data: count,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
                }]
            }
        });
    }else{
        var graphDates = @json( isset($dashboard['graphDates'])? $dashboard['graphDates']:0 );
        var count = @json( isset($dashboard['graphCounts'])? $dashboard['graphCounts']:0 );
        var myChart = new Chart(ctx, {
            type: 'line',
            options: {
                scales: {
                xAxes: [{
                    type: 'time',
                }]
                }
            },
            data: {
                labels: graphDates,
                datasets: [{
                label: 'Day Wise Calls',
                data: count,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
                }]
            }
        });

    }

</script>

