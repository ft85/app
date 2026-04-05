@extends('layouts.app')
@section('title', 'RRA IMPORTS')

@section('content')

<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black"> RRA Imports
        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">Manage you Imports</small>
    </h1>
    
</section>


<div class="row">
    {{-- <div class="col-sm-2 text-center">
        <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm" 
            onclick="window.location.href='{{ action([\App\Http\Controllers\RRAController::class, 'syncimports']) }}'">
            Sync RRA Imports
        </button>
    </div> --}}

    <a class="btn btn-primary margin-left-10" 
   href="{{ action([\App\Http\Controllers\RRAController::class, 'syncimports']) }}">
   <i class="fa fa-sync"></i> Sync Products
</a>
    
    
</div>





<div class="table-responsive">
    <table class="table table-bordered table-striped" id="rra-table">
       <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>    
@stop   
@section('javascript')
    <script>
        $(function() {
            $('#rra-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('rra.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'item_nm', name: 'item_nm' },
                    { data: 'qty', name: 'qty' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>


@endsection
