<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Order Table</title>
    <style>
        .table {
            background-color: white;
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            border: none;
            padding: 10px;
        }

        .table th {
            text-align: left;
        }

        .table td {
            border-bottom: 1px solid #ddd;
        }

        .table td .btn {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            margin-right: 5px;
            text-align: center;
        }

        .table td .btn.bg-yellow {
            background-color: #f39c12;
            color: white;
        }

        .table td .btn.bg-yellow:hover {
            background-color: #e08e0b;
        }

        .table td .btn.bg-green {
            background-color: #00a65a;
            color: white;
        }

        .table td .btn.bg-green:hover {
            background-color: #008d4c;
        }

        .table td .btn.bg-gray {
            background-color: #d2d6de;
            color: white;
        }

        .table td .btn.bg-gray:hover {
            background-color: #b5b9bf;
        }

        .printed {
            background-color: #85FFBD;
            color: black;
        }

        .not-printed {
            background-color: white;
            color: black;
        }

        .modal-body .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .modal-body .action-buttons .btn {
            width: 30%;
        }

        /* Updated styles for responsive table and buttons */
        .table td .btn-container {
            display: flex;
            justify-content: flex-start;
        }

        .table td .btn {
            flex: 0 0 auto;
            margin: 2px;
        }

        /* Custom modal width */
        #ordersModal .modal-dialog {
            max-width: 90%;
            width: 90%;
            margin: 20px auto;
        }

        @media (max-width: 768px) {
            #ordersModal .table {
                width: 100%;
            }

            #ordersModal .table,
            #ordersModal .table thead,
            #ordersModal .table tbody,
            #ordersModal .table th,
            #ordersModal .table td,
            #ordersModal .table tr {
                display: block;
            }

            #ordersModal .table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            #ordersModal .table tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
            }

            #ordersModal .table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: left;
                min-height: 30px;
            }

            #ordersModal .table td:before {
                content: attr(data-label);
                position: absolute;
                top: 6px;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }

            #ordersModal .table td .btn {
                padding: 4px 8px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    @if(empty($groupedOrders))
    <div class="col-md-12">
        <h4 class="text-center">@lang('restaurant.no_orders_found')</h4>
    </div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice No</th>
                <!-- <th>Printer</th> -->
                <th>@lang('sale.product')</th>
                <th>@lang('lang_v1.quantity')</th>
                <th>@lang('restaurant.table')</th>
                <th>@lang('contact.customer')</th>
                <th>@lang('restaurant.placed_at')</th>
                <th>Printer</th>
                <th>@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedOrders as $transactionGroup)
            @foreach($transactionGroup as $printerGroup)
            @foreach($printerGroup['items'] as $index => $item)
            <tr data-item-id="{{ $item['id'] }}" class="{{ $item['is_printed'] ? 'printed' : 'not-printed' }}">
                @if($index === 0)
                <td data-label="Invoice No" class="text-center" rowspan="{{ count($printerGroup['items']) }}">
                    {{ $printerGroup['invoice_no'] }}
                </td>
                @endif
                <td data-label="@lang('sale.product')">
                    {{ $item['product_name'] }}
                    @if(!empty($item['modifiers']))
                    <ul class="modifier-list">
                        @foreach($item['modifiers'] as $modifier)
                        <li>+ {{ $modifier['name'] }} ({{ $modifier['quantity'] }}) - {{ $modifier['variation_name'] }}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td data-label="@lang('lang_v1.quantity')">{{ $item['quantity'] }}{{ $item['unit'] }}</td>
                @if($index === 0)
                <td data-label="@lang('restaurant.table')" rowspan="{{ count($printerGroup['items']) }}">{{ $printerGroup['table_name'] }}</td>
                <td data-label="@lang('contact.customer')" rowspan="{{ count($printerGroup['items']) }}">{{ $printerGroup['customer_name'] }}</td>
                <td data-label="@lang('restaurant.placed_at')" rowspan="{{ count($printerGroup['items']) }}">
                    {{ \Carbon\Carbon::parse($printerGroup['created_at'])->format('d/m/Y H:i') }}
                </td>
                <td data-label="Printer" rowspan="{{ count($printerGroup['items']) }}">
                    {{ $printerGroup['printer_name'] }}
                </td>
                <td data-label="@lang('Action')" rowspan="{{ count($printerGroup['items']) }}">
                    <div class="btn-container">
                        <button type="button" class="btn btn-flat bg-green print-btn"
                            data-ids="{{ implode(',', array_column($printerGroup['items'], 'id')) }}" data-href="">
                            <i class="fa fa-print"></i> @lang('send')
                        </button>
                        <button type="button" class="btn btn-flat bg-yellow serve-btn"
                            data-ids="{{ implode(',', array_column($printerGroup['items'], 'id')) }}" data-href="">
                            <i class="fa fa-check"></i> @lang('serve')
                        </button>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>
    @endif
</body>

</html>