@extends('layouts.app')
@section('title', 'Customer Balances')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Customer Credit List</h1>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => 'Customer Balance'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="cust-table">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section> 

<!-- Load Credit Modal -->
<div class="modal fade" id="loadCreditModal" tabindex="-1" role="dialog" aria-labelledby="loadCreditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loadCreditModalLabel">Load Credit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loadCreditForm">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="0" max="99999999999" step="1" 
                               oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);" 
                               placeholder="Enter up to 11 digits">
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <label for="description">Description (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description here..."></textarea>
                    </div>
                    
                    <input type="hidden" id="contactId" name="contact_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection
@section('javascript')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    $('#cust-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reg.data2') }}', // Ensure this route matches your controller route
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name', defaultContent: '' },  // Fallback in case name is null
            { 
                data: 'balance', 
                name: 'balance',
                render: function(data) {
                    return new Intl.NumberFormat('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data);
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
            emptyTable: "No Customers available"
        }
    });
});

</script>

<script>
$(document).ready(function() {
    // Handle opening of modal and setting contact ID
    $(document).on('click', '.edit', function() {
        const contactId = $(this).data('id'); // Assuming you set data-id in your action buttons
        $('#contactId').val(contactId);
        $('#loadCreditModal').modal('show');
    });

    $('#loadCreditForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Serialize the form data

        $.ajax({
            url: '{{ route('reg.savecredit') }}', // Use the named route for saving credit
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success
                $('#loadCreditModal').modal('hide'); // Hide the modal
                $('#cust-table').DataTable().ajax.reload(); // Reload the DataTable
                toastr.success('Credit loaded successfully!'); // Show success notification

                // Clear the modal fields
                $('#loadCreditForm')[0].reset(); // Reset the form fields
                $('#contactId').val(''); // Clear the hidden contact ID field
            },
            error: function(xhr) {
                // Handle error
                toastr.error('An error occurred. Please try again.'); // Show error notification
            }
        });
    });
});





</script>


@endsection