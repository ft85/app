@extends('layouts.app')
@section('title', 'Change Password')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Change Password
    </h1>
</section>

<!-- Main content -->
<section class="content">
    
    @if (session('notification') || !empty($notification))
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{-- Display the notification message --}}
                @if(!empty($notification['msg']))
                    {{$notification['msg']}}
                @elseif(session('notification.msg'))
                    {{ session('notification.msg') }}
                @else
                    {{-- Default message if neither is set --}}
                    An error occurred. Please try again.
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
                            {!! Form::label('newpassword1', 'New Password' . ':') !!}
                            {!! Form::text('newpassword1', null, ['class' => 'form-control', 'id' => 'newpassword1', 'required' => 'required']); !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('newpassword2', 'New Password Confirm' . ':') !!}
                            {!! Form::text('newpassword2', null, ['class' => 'form-control', 'id' => 'newpassword2', 'required' => 'required']); !!}
                        </div>
                        <div class="col-sm-8">
                            <br>
                            <button type="button" id="verifyButton" class="tw-dw-btn tw-dw-btn-primary tw-text-white">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <br><br>
                
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
        
        // Fetching the password values
        var newPassword1 = document.getElementById('newpassword1').value;
        var newPassword2 = document.getElementById('newpassword2').value;

        // Check if the passwords match
        if (newPassword1 === newPassword2) {
            alert('Passwords match! Proceeding with the password change.');
            // Proceed with form submission or other logic
        } else {
            alert('Passwords do not match. Please re-enter.');
        }

        // Optionally: Send POST request to verify the password change logic in Laravel
        fetch('{{ route("change.pass") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF protection
            },
            body: JSON.stringify({ newpassword1: newPassword1 }) // Send new password as parameter
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Log the response for debugging
            
            // Handle the response object
            const responseObj = data[0].response;
            const header = responseObj.header;
            const body = responseObj.body[0]; // Assuming body is an array with one object

            
            // Example logic based on response
            if (header && header.h5 === '0') {
                // Do something when response is successful

                alert('Password changed successfully');


            } else {
                // Handle other cases

                alert('Error while trying to change password');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('verification-result').innerText = 'Error verifying the action.';
        });
    });
</script>
@endsection
