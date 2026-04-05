@extends('layouts.app')
@section('title', 'Meter Purchase History')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Meter Purchase History</h1>
</section>

<!-- Main content -->
<section class="content">
    
    @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>  
        </div>     
    @endif
    
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
                            </div>
                            <div class="col-sm-4">
                                <br>
                                <button type="button" id="verifyButton" class="tw-dw-btn tw-dw-btn-primary tw-text-white">Verify</button>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <br><br>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' =>'Meter Purchase History'])
                <table id="paymentTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Meter Number</th>
                            <th>p1</th>
                            <th>UUID</th>
                            <th>Date</th>
                            <th>Receipt Number</th>
                            <th>Amount</th>
                            <th>p6</th>
                            <th>p7</th>
                        </tr>
                    </thead>
                    <tbody id="results-body">
                        <!-- Results will be inserted here by JavaScript -->
                    </tbody>
                </table>
            @endcomponent
        </div>
    </div>
    
</section>
<!-- /.content -->

@endsection

@section('javascript')

<script>
   document.addEventListener('DOMContentLoaded', function () {
        // Initialize DataTable with buttons
        const table = $('#paymentTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Download CSV',
                    title: 'Payment Copy'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Download PDF',
                    title: 'Payment Copy'
                },
                'colvis' // Column visibility button
            ]
        });

        document.getElementById('verifyButton').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            let meterNumber = document.getElementById('meternumber').value;
            
            // Send POST request to the Laravel route
            fetch('{{ route("meter.history") }}', {
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
                const responseObj = data[0]?.response;
                if (!responseObj) {
                    throw new Error('Invalid response format');
                }

                const header = responseObj.header;
                const bodyArray = responseObj.body;

                // Clear the DataTable
                table.clear();

                // Check if h5 is "0" before displaying the data
                if (header && header.h5 === '0') {
                    // Add rows to the DataTable
                    bodyArray.forEach(item => {
                        const row = [
                            item['p0'] || '',
                            item['p1'] || '',
                            item['p2'] || '',
                            item['p3'] || '',
                            item['p4'] || '',
                            item['p5'] || '',
                            item['p6'] || '',
                            item['p7'] || ''
                        ];
                        table.row.add(row).draw();
                    });
                } else {
                    // Display an error if h5 is not OK
                    table.clear();
                    table.row.add([
                        'Error verifying meter number. Please try again.',
                        '', '', '', '', '', '', ''
                    ]).draw();
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Display error in the table
                table.clear();
                table.row.add([
                    'Error verifying meter number.',
                    '', '', '', '', '', '', ''
                ]).draw();
            });
        });
    });
</script>
@endsection
