<!--   Core JS Files   -->

<script src="{{asset('admin_assets/js/cloudflare.jquery.js')}}">
</script>
<script src="{{asset('admin_assets/js/core/popper.min.js')}}">
</script>
<script src="{{asset('admin_assets/js/bootstrap-material-design.js')}}">
</script>
<!--  Google Maps Plugin  -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key={{site_setting('google_api_key')}}&libraries=places&sensor=false"></script>


<!--  Angular js plaugin  -->
<script src="{{asset('admin_assets/js/angular.js')}}"></script>
<script src="{{asset('admin_assets/js/angular-sanitize.js')}}"></script>
<script type="text/javascript">
  var app = angular.module('App', ['ngSanitize']);
</script>

<!--  Plugin for Date Time Picker and Full Calendar Plugin  -->
<script src="{{asset('admin_assets/js/plugins/moment.min.js')}}">
</script>
<!--    Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="{{asset('admin_assets/js/plugins/bootstrap-datetimepicker.min.js')}}">
</script>


<!--    Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="{{asset('admin_assets/js/plugins/jasny-bootstrap.min.js')}}">
</script>

<!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
<script src="{{asset('admin_assets/js/material-dashboard.js?v=2.0.1')}}">
</script>
<!-- Dashboard scripts -->
<!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js">
</script>
<!-- Forms Validations Plugin -->
<script src="{{asset('admin_assets/js/plugins/jquery.validate.min.js')}}">
</script>

<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
<script src="{{asset('admin_assets/js/plugins/jquery.datatables.js')}}">
</script>
<!-- Sweet Alert 2 plugin, full documentation here: https://limonte.github.io/sweetalert2/ -->
<script src="{{asset('admin_assets/js/plugins/sweetalert2.js')}}">
</script>


@if(navigation_active('admin.edit_static_page') || navigation_active('admin.add_static_page'))
<script src="{{asset('admin_assets/js/jquery.gre.js')}}"></script>
@endif
<script src="{{asset('admin_assets/js/common.js')}}"> </script>

@stack('scripts')

</html>