<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        @page {
            margin: 0;
        }

        .receipt {
            max-width: 300px;
            margin: 0 auto;
            /* border: 1px solid black; */
            padding: 10px;
            margin-top: 100px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin: 0 0 3px 0;
        }

        p {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .header {
            text-align: center;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-top: 5px;
        }

        .item-name {
            text-align: left;
        }

        .item-quantity {
            text-align: right;
        }

        .modifier {
            padding-left: 20px;
            font-size: 12px;
        }

        .info {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="header">
            <h1>Order #{{ substr($order['invoice_no'], -4) }}</h1>
            <p>{{ @format_date($order['created_at']) }} {{ @format_time($order['created_at']) }}</p>
        </div>

        <div class="info">
            <p><span>Location:</span> {{ $order['business_location'] }}</p>
            @if(!empty($order['customer_name']))
            <p><span>Customer:</span> {{ $order['customer_name'] }}</p>
            @endif
            @if(!empty($order['table_name']) && $order['table_name'] != 'N/A')
            <p><span>Table:</span> {{ $order['table_name'] }}</p>
            @endif
            @if(!empty($order['line_service_staff']['name']) && $order['line_service_staff']['name'] != 'N/A')
            <p><span>Staff:</span> {{ $order['line_service_staff']['name'] }}</p>
            @endif
        </div>

        <div class="items">
            @foreach ($order['items'] as $item)
            <div class="item">
                <div class="item-row">
                    <span class="item-name">{{ $item['product_name'] }}</span>
                    <span class="item-quantity">{{ $item['quantity'] }} PC(s)</span>
                </div>
                @if(!empty($item['variation_name']) || !empty($item['product_variation_name']))
                <div class="modifier">
                    {{ $item['product_variation_name'] ?? '' }} {{ $item['variation_name'] ?? '' }}
                </div>
                @endif
                @if(!empty($item['modifiers']))
                @foreach($item['modifiers'] as $modifier)
                <div class="modifier">
                    + {{ $modifier['name'] }} ({{ $modifier['quantity'] }})
                </div>
                @endforeach
                @endif
            </div>
            @endforeach
        </div>

        <!-- <div class="footer">
            Thank you for your order!
        </div> -->
    </div>
</body>

</html>