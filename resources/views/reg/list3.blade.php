@extends('layouts.app')
@section('title', 'Customer Balances')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Customer Statement</h1>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => 'Customer Statement'])
                <div class="table-responsive">
                    <!-- Date Range Filter -->
                    <div class="row">
                        <div class="col-md-3">
                            <label for="min-date">From Date:</label>
                            <input type="date" id="min-date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="max-date">To Date:</label>
                            <input type="date" id="max-date" class="form-control">
                        </div>
                    </div>
                    <br>

                    <!-- DataTable -->
                    <table class="table table-bordered table-striped" id="cust-statement-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Money In</th>
                                <th>Money Out</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balances as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ number_format($transaction->credit, 2) }}</td>
                                    <td>{{ number_format($transaction->debit, 2) }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th id="total-money-in">0.00</th>
                                <th id="total-money-out">0.00</th>
                                <th colspan="2" style="text-align: center;">Balance:&nbsp;<span id="total-balance">0.00</span></th>

                            </tr>
                        </tfoot>
                        
                        
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section> 

@endsection

@section('javascript')

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
   $(document).ready(function() {
    // Initialize DataTable
    var table = $('#cust-statement-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            // Your buttons configuration
        ],
        order: [[4, 'desc']],  // Order by Date column
        language: {
            emptyTable: "No transactions available"
        },
        footerCallback: function(row, data, start, end, display) {
            // Your footer calculations here
        }
    });

    // Date filter event listeners
    $('#min-date, #max-date').on('change', function() {
        table.draw();  // Redraw table after date change
    });

    // Custom date range filter for DataTables
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var minDate = $('#min-date').val();
        var maxDate = $('#max-date').val();
        var createdAtDate = new Date(data[4]); // Use the correct column index

        var min = minDate ? new Date(minDate) : null;
        var max = maxDate ? new Date(maxDate) : null;

        if (isNaN(createdAtDate.getTime())) {
            return false; // Skip this entry if the date is invalid
        }

        if (
            (min === null || createdAtDate >= min) &&
            (max === null || createdAtDate <= max)
        ) {
            return true;
        }
        return false;
    });
});

    </script>
    

@endsection
