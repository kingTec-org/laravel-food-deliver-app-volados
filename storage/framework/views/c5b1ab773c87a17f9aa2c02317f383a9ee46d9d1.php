<script src="<?php echo e(asset('js/app.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/owl.carousel.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/bootstrap-select.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/bootstrap-toggle.min.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(url('admin_assets/js/plugins/jquery.validate.min.js')); ?>"></script>

<!-- angular js -->
<script src="<?php echo e(asset('admin_assets/js/angular.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/js/angular-sanitize.js')); ?>"></script>
<script type="text/javascript">
  var app = angular.module('App', ['ngSanitize']);
  var jquery_datetimepicker_date_format = "<?php echo e(site_setting('jquery_date_format')); ?>";
</script>

<!--  Google Maps Plugin  -->
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo e(site_setting('google_api_key')); ?>&libraries=places&sensor=false&language=<?php echo e((Session::get('language')) ? Session::get('language') : $default_language[0]->value); ?>"></script>
<script src="<?php echo e(asset('messages.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/store_detail.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/driver.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/store.js')); ?>" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?php echo e(asset('js/common.js')); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('js/locationpicker.jquery.js')); ?>?dasd" type="text/javascript"></script>

<script type="text/javascript">
    $('select').addClass('selectpicker');

    var APP_URL = <?php echo json_encode(url('/')); ?>;
    function myMap() {
        var mapOptions = {
            center: new google.maps.LatLng(51.5, -0.12),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.HYBRID
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
    }

// get url data
var ajax_url_list ={

    search             : '<?php echo e(route("search")); ?>',

    store_location     : '<?php echo e(route("store_location")); ?>',

    search_result	   : '<?php echo e(route("search_result")); ?>',

    signup2	   		   : '<?php echo e(route("signup2")); ?>',

    signup_data		   : '<?php echo e(route("signup_data")); ?>',

    item_details	   : '<?php echo e(route("item_details")); ?>',

    orders_store	   : '<?php echo e(route("orders_store")); ?>',

    orders_remove	   : '<?php echo e(route("orders_remove")); ?>',

    orders_change	   : '<?php echo e(route("orders_change")); ?>',

    category_details   : '<?php echo e(route("category_details")); ?>',

    checkout           : '<?php echo e(route("checkout")); ?>',

    card_details       : '<?php echo e(route("card_details")); ?>',

    place_order_details: '<?php echo e(route("place_order_details")); ?>',

    order_track        : '<?php echo e(route("order_track")); ?>',

    search_data        : '<?php echo e(route("search_data")); ?>',

    session_clear_data : '<?php echo e(route("session_clear_data")); ?>',

    place_order 	   : '<?php echo e(route("place_order")); ?>',

    order_invoice 	   : '<?php echo e(route("order_invoice")); ?>',

    add_cart           : '<?php echo e(route("add_cart")); ?>',

    add_promo_code_data: '<?php echo e(route("add_promo_code_data")); ?>',

    schedule_store     : '<?php echo e(route("schedule_store")); ?>',

    password_change    : '<?php echo e(route("password_change")); ?>',

    change_password    : '<?php echo e(route("store.change_password")); ?>',

    dasboard           : '<?php echo e(route("store.dashboard")); ?>',

    cancel_order       : '<?php echo e(route("cancel_order")); ?>',

    location_check     : '<?php echo e(route("location_check")); ?>',

    location_not_found : '<?php echo e(route("location_not_found")); ?>',

    get_payout_preference : '<?php echo e(route("store.get_payout_preference")); ?>',

    offers_status      : '<?php echo e(route("store.offers_status")); ?>',

    remove_time        : '<?php echo e(route("store.remove_time")); ?>',

    send_message       : '<?php echo e(route("store.send_message")); ?>',

    confirm_phone_no   : '<?php echo e(route("store.confirm_phone_no")); ?>',

    profile_pic_upload : '<?php echo e(route("driver.profile_upload")); ?>',

    status_update      : '<?php echo e(route("store.status_update")); ?>',

    show_comments      : '<?php echo e(route("store.show_comments")); ?>',

    particular_order   : '<?php echo e(route("driver.particular_order")); ?>',

    invoice_filter     : '<?php echo e(route("driver.invoice_filter")); ?>',

    ajax_help_search     : '<?php echo e(route("ajax_help_search")); ?>',

    help     : '<?php echo e(route("help")); ?>',
};

function getUrl(key, replaceValues={}) {
	url = ajax_url_list[key];
	//console.log(url);
	var replace_url = url;
	$.each(replaceValues, function(i, v) {
     replace_url = replace_url.replace('@'+i, v);
	  //console.log(replace_url);
	});
	return replace_url;
}

function getUrls(key) {
	url = ajax_url_list[key];
	//console.log(url);
	var replace_url = url;
	return replace_url;
}
$.datepicker.setDefaults($.datepicker.regional[ "<?php echo e((Session::get('language')) ? Session::get('language') : $default_language[0]->value); ?>" ])
</script>
<?php if(Session::get('language') != 'en'): ?>
<?php echo Html::script('js/i18n/datepicker-'.Session::get('language').'.js'); ?>

<?php endif; ?>
<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js">
</script>
<script type="text/javascript">
  $(document).ready(function(){
    var tz = jstz.determine(); // Determines the time zone of the browser client
    var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
     $.get('<?php echo e(route("home")); ?>', {tz: timezone}, function(data) {
     });
  });
</script> -->
<?php echo $__env->yieldPushContent('scripts'); ?>
</body></html>