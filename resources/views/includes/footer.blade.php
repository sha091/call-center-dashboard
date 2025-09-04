<!--   Core JS Files   -->
<script src="{{ asset('public/js/core/popper.min.js') }}"></script>
<script src="{{ asset('public/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('public/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('public/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('public/js/dev/jquery-3.5.1.js') }}"></script>
<script src="{{ asset('public/js/dev/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/dev/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('public/js/dev/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/js/dev/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('public/js/dev/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/js/dev/choices.min.js') }}"></script>
<script type="text/javascript" src=" {{ asset('public/bootstrap-daterangepicker/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/bootstrap-daterangepicker/daterangepicker.js') }} "></script>
<script src="{{ asset('public/js/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('public/js/essential_audio.js') }}"></script>
<script src="{{ asset('public/js/timepicker.min.js') }}"></script>




        {!! Toastr::message() !!}

<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>


<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('public/js/material-dashboard.min.js?v=3.1.0') }}"></script>
@php
    $insertDate     = date("Y-m-d H:i:s");
    $campDatediff   = strtotime($insertDate) - strtotime(env('CAMP_START_DATE'));
    $campDays       = floor($campDatediff/(60*60*24));
@endphp
<script>
    $(function() {
        //////
        $('#form-date-range').daterangepicker({
                ranges: {
                    'All': [Date.today().add({
                        days: - {{ $campDays }}
                    }), 'today'],
                    'Today': ['today', 'today'],
                    'Yesterday': ['yesterday', 'yesterday'],
                    'Last 7 Days': [Date.today().add({
                        days: -6
                    }), 'today'],
                    'Last 30 Days': [Date.today().add({
                        days: -29
                    }), 'today'],
                    'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today()
                    .moveToLastDayOfMonth()],
                    'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                        months: -1
                    }), Date.today().moveToFirstDayOfMonth().add({
                        days: -1
                    })]
                },
                opens: 'left',
                format: 'MM/dd/yyyy',
                separator: ' to ',
                startDate: Date.today(),
                endDate: Date.today(),
                maxDate: Date.today(),
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                },
                showWeekNumbers: true,
                buttonClasses: ['btn-danger']
            },

            function(start, end) {
                $('#form-date-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString(
                    'MMMM d, yyyy'));
                $('input#startDate').val(start.toString('yyyy-MM-dd'));
                $('input#endDate').val(end.toString('yyyy-MM-dd'));
                $('form#dateRangeForm').submit();

            });
    });
</script>
<script>
    function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
</script>




</body>

</html>
