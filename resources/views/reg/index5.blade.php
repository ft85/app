@extends('layouts.app')
@section('title', 'Vendor Account Summary')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Vendor Account Summary
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
            {{-- <div class="col-sm-8">
                <div class="form-group">
                    {!! Form::label('name', 'Meter number' . ':') !!}
                    {!! Form::text('meternumber', null, ['class' => 'form-control', 'id' => 'meternumber', 'required' => 'required']); !!}
                </div>
            </div> --}}
            <div class="col-sm-4">
                <br>
                <button type="button" id="verifyButton" class="tw-dw-btn tw-dw-btn-primary tw-text-white">Get Summary</button>
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
            @component('components.widget', ['class' => 'box-primary', 'title' =>'Vendor Account Summary'])
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
        
        //let meterNumber = document.getElementById('meternumber').value;
        
        // Send POST request to the Laravel route
        fetch('{{ route("vendor.summary") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF protection
            },
            // Send meter number as parameter
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
        <td>@lang('Vendor Account')</td>
        <td>${body.p0}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>2</td>
        <td>@lang('Vendor Name')</td>
        <td>${body.p1}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>3</td>
        <td>@lang('Vendor Operator Name')</td>
        <td>${body.p2}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>4</td>
        <td>@lang('Vendor Account Balance')</td>
        <td>${parseFloat(body.p3).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>5</td>
        <td>@lang('Day')</td>
        <td>${parseFloat(body.p4).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>6</td>
        <td>@lang('Daily Opening')</td>
        <td>${parseFloat(body.p5).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>7</td>
        <td>@lang('Daily Debit')</td>
        <td>${parseFloat(body.p6).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>8</td>
        <td>@lang('Daily Credit')</td>
        <td>${parseFloat(body.p7).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>9</td>
        <td>@lang('Daily Debit')</td>
        <td>${parseFloat(body.p8).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>10</td>
        <td>@lang('Daily Credit')</td>
        <td>${parseFloat(body.p9).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>11</td>
        <td>@lang('Month')</td>
        <td>${parseFloat(body.p10).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>12</td>
        <td>@lang('Monthly Opening Balance')</td>
        <td>${parseFloat(body.p11).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>13</td>
        <td>@lang('Monthly Debit')</td>
        <td>${parseFloat(body.p12).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>14</td>
        <td>@lang('Monthly Credit')</td>
        <td>${parseFloat(body.p13).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>15</td>
        <td>@lang('Monthly Debit')</td>
        <td>${parseFloat(body.p14).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
    <tr>
        <td>16</td>
        <td>@lang('Monthly Credit')</td>
        <td>${parseFloat(body.p15).toFixed(2)}</td> <!-- Format to two decimal places -->
    </tr>
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