@extends('layouts.app')
@section('title', 'Payment Copy')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Check Payment Copy
    </h1>
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
                    {!! Form::label('name', 'Receipt number' . ':') !!}
                    {!! Form::text('meternumber', null, ['class' => 'form-control', 'id' => 'meternumber', 'required' => 'required']); !!}
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
            @component('components.widget', ['class' => 'box-primary', 'title' => __('Payment Copy Details')])
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>@lang('No')</th>
                            <th>@lang('Parameter')</th>
                            <th>@lang('Value')</th>
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
    document.getElementById('verifyButton').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        let meterNumber = document.getElementById('meternumber').value;
        
        // Send POST request to the Laravel route
        fetch('{{ route("verify.copy") }}', {
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
        let rowsHTML = `
            <tr>
                <td>1</td>
                <td>@lang('Meter Number')</td>
                <td>${body.p0}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>@lang('Token')</td>
                <td>${body.p30}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>@lang('Unit Type')</td>
                <td>${body.p65}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>@lang('Units')</td>
                <td>${body.p66}</td>
            </tr>
            <tr>
                <td>2</td>
                <td>@lang('Regulatory fees')</td>
                <td>${body.p90}</td>
            </tr>
            <!-- Add more rows as needed -->
        `;
        resultsBody.innerHTML = rowsHTML;
    } else {
        // Display an error if h5 is not OK
        resultsBody.innerHTML = `
            <tr>
                <td colspan="3">
                    <p>Error verifying meter number. Please try again.</p>
                </td>
            </tr>
        `;
    }
}).catch(error => {
            console.error('Error:', error);
            document.getElementById('verification-result').innerText = 'Error verifying meter number.';
        });
    });
    </script>

@endsection