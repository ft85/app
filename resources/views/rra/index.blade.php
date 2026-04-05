<style>
    .large-select {
        width: 300px;
        height: 50px;
        font-size: 18px;
        padding: 10px;
    }
</style>

@extends('layouts.app')
@section('title', 'RRA IMPORTS')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">RRA IMPORTS</h1>
</section>

<!-- Main content -->
<section class="content">

    @if (session('notification') || !empty($notification))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-{{ session('notification.type', 'danger') }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                @if(!empty($notification['msg']))
                    {{ $notification['msg'] }}
                @elseif(session('notification.msg'))
                    {{ session('notification.msg') }}
                @endif
            </div>
        </div>

        
    </div>
    @endif

    
    <div id="sync-spinner" style="display:none; text-align:center; margin-top:20px;">
        <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i> Fetching new data...
    </div>

    {{-- <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary'])
                {!! Form::open(['id' => 'meterForm']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            
                            <div class="col-sm-8">
                                <br>
                                <a class="btn btn-primary margin-left-10" id="sync-button">
                                    <i class="fa fa-sync"></i> Sync Products
                                 </a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <br><br>
            @endcomponent
        </div>
    </div> --}}

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => 'Payment History'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rra-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Hs Code</th>
                                <th>Task code</th>
                                <th>Pkg Qty</th>
                                <th>Dcl No</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Dcl Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section>

<div class="modal" tabindex="-1" role="dialog" id="add-to-stock-modal">
    <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add to Stock</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="add-to-supplier" class="col-form-label col-sm-6">Select Supplier</label>
                    <div class="col-sm-6">
                      <select class="form-control selectpicker" data-live-search="true" id="add-to-supplier">
                        <div id="modal-supplier"></div> 
                      </select>
                    </div>
                  </div>
                <!-- Line details will be loaded here dynamically -->
                <div id="modal-line-details"></div>
                <!-- Item dropdown will be loaded here dynamically -->
                

                <div class="form-group row">
                    <label for="add-to-stock-item" class="col-form-label col-sm-6">Select Item</label>
                    <div class="col-sm-6">
                      <select class="form-control selectpicker" data-live-search="true" id="add-to-stock-item">
                        <div id="modal-line-inventoryitems"></div> 
                      </select>
                    </div>
                </div>

                <div id="modal-line-details2"></div>
                
                
            </div>
            <div class="modal-footer">
                <button id="add-to-stock-btn" type="button" class="btn btn-danger">Add to Stock</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



 
 

  

@endsection

@section('javascript')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>


<script>


function openDetailsModal(rowId) {
    $.ajax({
        url: '/rra/line-details/' + rowId,
        method: 'GET',
        success: function(response) {
            // Populate the line details
            $('#modal-line-details').html(response.lineDetails);
            $('#modal-line-details2').html(response.lineDetails2);
            $('#add-to-supplier').html(response.customer_groups);
            $('#add-to-stock-item').html(response.inventoryItems);
            $('#add-to-stock-item').selectpicker('refresh');
            $('#add-to-supplier').selectpicker('refresh');
            $('#add-to-stock-modal').modal('show');
        },
        error: function() {
            toastr.error('Failed to load line details.');
        }
    });

    $('#add-to-stock-btn').off('click').on('click', function() {
        // Gather the data from the modal
        var itemId = $('#add-to-stock-item').val();
        var quantity = $('#add-to-stock-quantity').val();
        var contact_id = $('#add-to-supplier').val();
        var importamount = $('#modal-line-details #invc_fcur_amt-value').text();

        // Validate input
        if (!itemId) {
            toastr.warning('Please select an item to map to');
            return;
        }

        if (!quantity) {
            toastr.warning('Please input the quantity.');
            return;
        }

        // Disable the button to prevent multiple submissions
        $(this).prop('disabled', true);

        // Prepare the data to send
        var data = {
            item_id: itemId,
            quantity: quantity,
            contact_id: contact_id,
            importamount: importamount,
            rowId: rowId,
            _token: '{{ csrf_token() }}' // Include CSRF token for security
        };

        // Send the AJAX request to add stock
        $.ajax({
            url: '/rra/add-stock',
            method: 'POST',
            data: data,
            success: function(response) {
                toastr.success('Import added to Stock added successfully!'); // Use toastr for success notification
                $('#add-to-stock-modal').modal('hide');
                $('#rra-table').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                toastr.error('Failed to add stock: ' + error);
            },
            complete: function() {
                // Re-enable the button after the request is complete
                $('#add-to-stock-btn').prop('disabled', false);
            }
        });
    });
}






</script>

<script>

$(document).ready(function() {
    var table = $('#rra-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('rra.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'item_nm', name: 'item_nm' },
            { data: 'qty', name: 'qty' },
            { data: 'hs_cd', name: 'hs_cd' },
            { data: 'task_cd', name: 'task_cd' },
            { data: 'pkg_qty', name: 'pkg_qty' },
            { data: 'dcl_no', name: 'dcl_no' },
            { data: 'invc_fcur_amt', name: 'invc_fcur_amt' },
            { data: 'invc_fcur_cd', name: 'invc_fcur_cd' },
            { data: 'dcl_de', name: 'dcl_de' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
            emptyTable: "No imports data available"
        }
    });

    // Automatically sync products when the page loads
    syncProducts();

    // Function to sync products
    function syncProducts() {
        // Show a loading indicator (optional)
        $('#sync-button').html('<i class="fa fa-sync fa-spin"></i> Syncing...');

        $.ajax({
            url: '{{ action([\App\Http\Controllers\RRAController::class, "syncimports"]) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token for security
            },
            success: function(response) {
                // On success, reload DataTable and reset button
                table.ajax.reload(null, false); // false: Don't reset pagination
                $('#sync-button').html('<i class="fa fa-sync"></i> Sync Products');
            },
            error: function(xhr, status, error) {
                // Handle error
                toastr.error('Failed to Sync imports try again Later!'); // Use toastr for success notification
                
                $('#sync-button').html('<i class="fa fa-sync"></i> Sync Products');
            }
        });
    }
});
</script>

<script>
    $(document).ready(function() {
    // Initialize bootstrap-select
    $('.selectpicker').selectpicker();
     });
</script>






@endsection










