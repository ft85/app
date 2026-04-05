@extends('layouts.restaurant')
@section('title', __( 'restaurant.orders' ))

@section('content')

<!-- Main content -->
<section class="content min-height-90hv no-print">
    <div class="row">
        <div class="col-md-12 text-center">
            <h3>@lang( 'restaurant.all_orders' ) @show_tooltip(__('lang_v1.tooltip_serviceorder'))</h3>
        </div>
        <div class="col-sm-12">
            <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right" id="refresh_orders">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                </svg>@lang('restaurant.refresh')
            </button>
        </div>
    </div>
    <br>
    <div class="row">
        @component('components.widget')
        <div class="col-sm-6">
            {!! Form::open(['url' => action([\App\Http\Controllers\Restaurant\OrderController::class, 'index']), 'method' => 'get', 'id' => 'select_printer_form']) !!}
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-print"></i>
                    </span>
                    {!! Form::select('printer_id', $printers, request()->printer_id, ['class' => 'form-control select2', 'placeholder' => __('All Order Lines '), 'id' => 'printer_id']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        @endcomponent

        @component('components.widget', ['title' => __( 'lang_v1.line_orders' )])
        <div class="row" id="line_orders_div">
            @include('restaurant.partials.line_orders', ['orders_for' => request()->printer_id ?? 'all'])
        </div>
        <div class="overlay hide">
            <i class="fas fa-sync fa-spin"></i>
        </div>
        @endcomponent

        @component('components.widget', ['title' => __( 'restaurant.all_your_orders' )])
        <div class="row" id="orders_div">
            @include('restaurant.partials.show_orders', ['orders_for' => request()->printer_id ?? 'all'])
        </div>
        <div class="overlay hide">
            <i class="fas fa-sync fa-spin"></i>
        </div>
        @endcomponent
    </div>
</section>
<!-- /.content -->

<!-- Hidden iframe for printing -->
<iframe id="print-frame" style="display:none;"></iframe>

@endsection

@section('javascript')

<script>
    $(document).ready(function() {
        setInterval(function() {
            location.reload(); // This will reload the page
        }, 40000);

        $('#refresh_orders').click(function() {
            location.reload(); // This will reload the page
        });

        $('select#printer_id').change(function() {
            $('form#select_printer_form').submit();
        });

        // Handle Print Button Click
        $('.print-btn').on('click', function() {
            var ids = $(this).data('ids');
            console.log("Printing Orders: ", ids); // Log the IDs for print action

            var printUrl = "{{ action([\App\Http\Controllers\Restaurant\OrderController::class, 'printLineOrder'], ':ids') }}";
            printUrl = printUrl.replace(':ids', ids);

            $.ajax({
                method: "GET",
                url: printUrl,
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                        toastr.success(result.msg);

                        // Find the iframe and write the print content to it
                        var iframe = document.getElementById('print-frame');
                        var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

                        iframeDoc.open();
                        iframeDoc.write(result.html_content);
                        iframeDoc.close();

                        // Wait for the iframe to fully load before printing
                        iframe.onload = function() {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        };
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred: " + error);
                }
            });
        });

        // Handle Serve Button Click
        $('.serve-btn').on('click', function() {
            var ids = $(this).data('ids');
            console.log("Marking as Served: ", ids); // Log the IDs for served action

            var servedUrl = "{{ action([\App\Http\Controllers\Restaurant\OrderController::class, 'markLineOrderAsServed'], ':ids') }}";
            servedUrl = servedUrl.replace(':ids', ids);

            $.ajax({
                method: "GET",
                url: servedUrl,
                dataType: "json",
                success: function(result) {
                    if (result.success) {
                        toastr.success(result.msg);
                        location.reload(); // Reload the page after success
                    } else {
                        toastr.error(result.msg);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error("An error occurred: " + error);
                }
            });
        });
    });
</script>
@endsection
