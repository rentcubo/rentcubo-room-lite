<script src="{{ asset('assets/js/jquery.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('admin-assets/node_modules/datatables.net/js/jquery.dataTables.js')}}"></script>

<script src="{{ asset('admin-assets/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js')}}"></script>

<!-- price plugin -->
<script src="{{ asset('assets/js/obj.min.js')}}"></script>
<script src="{{ asset('assets/js/addSlider.min.js')}}"></script>
<!-- price plugin -->
<script src="{{ asset('assets/slick/slick.min.js')}}"></script>
<script src="{{ asset('assets/js/flex-slider.min.js')}}"></script>
<script src="{{ asset('assets/js/slick.js')}}"></script>
<script src="{{ asset('assets/js/script.js')}}"></script>

<script type="text/javascript">

@if(isset($account_page)) 

    $("#{{$account_page}}").addClass("active"); 

@endif

(function($) {
  'use strict';
    $(function() {
        
        $('#order-listing').DataTable({
            "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
            "iDisplayLength": 10,
            "language": { search: "" }
        });

        $('#order-listing').each(function(){
            var datatable = $(this);
            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
            search_input.attr('placeholder', 'Search');
            search_input.removeClass('form-control-sm');
            // LENGTH - Inline-Form control
            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
            length_sel.removeClass('form-control-sm');
        });
    });
})(jQuery);

</script>