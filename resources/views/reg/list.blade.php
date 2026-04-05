@extends('layouts.app')
@section('title', 'Payment History')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Payment Confirmation</h1>
</section>

<!-- Main content -->
<section class="content">

    {{-- @if (session('notification') || !empty($notification))
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
    @endif --}}

    <div id="successMessage" style="color: green;"></div>

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary'])
                {!! Form::open(['id' => 'meterForm']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    {!! Form::label('name', 'Meter Number:') !!}
                                    {!! Form::text('meternumber', null, ['class' => 'form-control', 'id' => 'meternumber', 'required' => 'required']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('name', 'Amount:') !!}
                                    {!! Form::text('tamount', null, ['class' => 'form-control', 'id' => 'tamount', 'required' => 'required']) !!}
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <br>
                                <button type="button" id="payButton" class="tw-dw-btn tw-dw-btn-primary tw-text-white" data-toggle="modal" data-target="#verifyModal">Verify Payment</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <br><br>
            @endcomponent
        </div>
    </div>
    <div>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" />
        
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" />
        
        <button id="filter">Filter</button>
    </div>
    

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => 'Payment History'])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rra-table">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>UUID</th>
                                <th>Meter Number</th>
                                <th>Amount</th>
                                <th>Token</th>
                                <th>Units</th>
                                <th>Details</th>
                                <th>Receipt Number</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section>

<!-- Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Confirm Payment Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to Get Token for this Meter?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmVerify">Yes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />



<script>
   
   document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('confirmVerify').addEventListener('click', function(e) {
        e.preventDefault();

        let meterNumber = document.getElementById('meternumber').value;
        let tamount = document.getElementById('tamount').value;

        fetch('{{ route("payment.confirm") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ meternumber: meterNumber, tamount: tamount })
        })
        .then(response => response.json())
        .then(data => {
            const responseObj = data[0]?.response;
            if (!responseObj) {
                throw new Error('Invalid response format');
            }

            const header = responseObj.header;
            const bodyArray = responseObj.body;

            if (header && header.h5 === '0') {
                const p30 = bodyArray.p30 || bodyArray[0]?.p30;
                const p66 = bodyArray.p66 || bodyArray[0]?.p66;
                const p25 = bodyArray.p25 || bodyArray[0]?.p25;

                // Fetch the HTML template from the server
                fetch('{{ route("pdf.template") }}')
                    .then(response => response.text())
                    .then(html => {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;

                        tempDiv.querySelector('#param1').textContent = p30 || 'N/A';
                        tempDiv.querySelector('#param2').textContent = p66 || 'N/A';
                        tempDiv.querySelector('#param3').textContent = p25 || 'N/A';

                        // Use html2pdf to generate PDF
                        //html2pdf().from(tempDiv).save('payment_confirmation.pdf');

                        html2pdf().from(tempDiv).save('payment_confirmation.pdf').then(() => {
                            //table.ajax.reload(); // Reload data in DataTable
                            // Or use window.location.reload() if you want to reload the entire page
                             window.location.reload();
                        });

                        
                    })
                    .catch(error => console.error('Error fetching HTML template:', error));
            } else {
                table.clear();
                table.row.add([
                    'Error verifying meter number. Please try again.',
                    '', '', '', '', '', '', ''
                ]).draw();
                window.location.reload();
            }


        })
        .catch(error => {
            console.error('Error:', error);

            table.clear();
            table.row.add([
                'Error verifying meter number.',
                '', '', '', '', '', '', ''
            ]).draw();
            window.location.reload();
        });

        $('#verifyModal').modal('hide');

        //window.location.reload();

        
    });
});


</script>

<script>
    @if (session('notification'))
        toastr.{{ session('notification')['type'] }}('{{ session('notification')['msg'] }}');
    @endif
</script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#rra-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reg.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'uuid', name: 'UUID' },
            { data: 'meter_number', name: 'meter_number' },
            { 
                data: 'amount', 
                name: 'amount',
                render: function(data) {
                    return new Intl.NumberFormat('en-US', { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data);
                }
            },
            { data: 'token', name: 'token' },
            { data: 'units', name: 'units' },
            { data: 'tokenexplanation', name: 'tokenexplanation' },
            { data: 'reciept_number', name: 'reciept_number' },
            {
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                const date = new Date(data);
                return date.toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            hour12: false,  // Set to `true` for 12-hour format, `false` for 24-hour format
        });
    }
},
            { data: 'created_by', name: 'created_by' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
            emptyTable: "No payment data available"
        }
    });

    // Add the date filter logic
    $('#filter').on('click', function() {
        table.draw(); // Redraw the table to apply the filter
    });

    // Custom filtering function for the date range
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var createdAt = data[8]; // Use the correct index for created_at
        var createdAtDate = new Date(createdAt); // Parse the date from the data
        
        var startDate = $('#start_date').val() ? new Date($('#start_date').val()) : null;
        var endDate = $('#end_date').val() ? new Date($('#end_date').val()) : null;

        // If both dates are not selected, don't apply the filter
        if (!startDate && !endDate) {
            return true;
        }

        // If only startDate is selected
        if (startDate && !endDate) {
            return createdAtDate >= startDate;
        }

        // If only endDate is selected
        if (!startDate && endDate) {
            return createdAtDate <= endDate;
        }

        // If both startDate and endDate are selected
        if (startDate && endDate) {
            return createdAtDate >= startDate && createdAtDate <= endDate;
        }

        return true; // By default, show all rows
    });
});


</script>
<script>
function retryFunction(uuid) {
    // Display a confirmation dialog (optional)
    if (!confirm('Are you sure you want to retry this payment?')) {
        return; // Exit if the user cancels
    }

    // Perform the AJAX request
    $.ajax({
        url: '{{ route("verify.retry") }}', // Route to your verify retry endpoint
        method: 'POST', // Use POST method as specified in your route
        data: {
            meternumber: uuid, // Pass the UUID to the server
            _token: '{{ csrf_token() }}' // Include CSRF token for security
        },
        success: function(response) {
            // Handle the success response (you can customize this)
            toastr.success('Retry successful for UUID: ' + uuid); 
            window.location.reload();


        },
        error: function(xhr, status, error) {
            // Handle the error response
            alert('Retry failed. Please try again.');
            console.error('Error:', error); // Log the error for debugging

            window.location.reload();
        }
    });
}


</script>

<script>
    document.getElementById('verifyButton').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        let meterNumber = document.getElementById('meternumber').value;
        
        // Send POST request to the Laravel route
        fetch('{{ route("verify.retry") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF protection
            },
            body: JSON.stringify({ meternumber: meterNumber }) // Send meter number as parameter
        })
        .then(response => response.json())
        .then(data => {
    console.log(data); // Log the entire response for debugging
    
    // Access the response object
    const responseObj = data[0].response;
    
    // Access header and body
    const header = responseObj.header;
    const body = responseObj.body[0]; // Assuming body is an array with one object

    const resultsBody = document.getElementById('results-body');
    resultsBody.innerHTML = ''; // Clear previous results

    // Check if h5 is "0" before displaying the data
    if (header && header.h5 === '0') {
        // Create rows for the table

        window.location.reload();
        
    } else {
        // Display an error if h5 is not OK

        window.location.reload();
        
    }
}).catch(error => {
            console.error('Error:', error);
            document.getElementById('verification-result').innerText = 'Error verifying meter number.';
        });
    });
    </script>
@endsection






