<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen/Bar Order</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1;
            margin: 0;
            padding: 0;
            width: 80mm;
        }

        .text-center {
            text-align: center;
        }

        h1 {
            font-size: 16px;
            margin: 0;
            padding: 1px 0;
        }

        h2 {
            font-size: 15px;
            margin: 0;
            padding: 1px 0;
        }

        h4 {
            font-size: 14px;
            margin: 0;
            padding: 1px 0;
        }

        p {
            margin: 0;
            padding: 1px 0;
            font-size: 14px;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            text-align: left;
            padding: 1px;
            border: none;
        }

        .qty {
            width: 40px;
            font-weight: bold;
        }

        .modifier {
            padding-left: 15px;
            font-style: italic;
        }

        .note {
            padding-left: 10px;
            font-style: italic;
        }

        .end-text {
            text-align: center;
            padding: 2px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1 class="text-center">{{ $printer_name }}</h1>
    <h2 class="text-center">{{ $transaction->business->name }}</h2>
    <h4 class="text-center">Category: {{ $category_name }}</h4>

    <div class="separator"></div>

    <p>#{{ $transaction->invoice_no }}</p>
    <p>{{ $transaction->contact->name }}</p>
    @if($transaction->table_name)
        <p>Table: {{ $transaction->table_name }}</p>
    @endif
    <p>{{ $transaction->transaction_date }}</p>

    <div class="separator"></div>

    <table>
        <thead>
            <tr>
                <th class="qty">QTY</th>
                <th>ITEM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($line_orders as $line)
                <tr>
                    <td class="qty">{{ $line->quantity }}</td>
                    <td>{{ $line->product->name }}</td>
                </tr>
                @if(!empty($line->sell_line_note))
                    <tr>
                        <td></td>
                        <td class="note">Note: {{ $line->sell_line_note }}</td>
                    </tr>
                @endif
                @if($line->modifiers->isNotEmpty())
                    @foreach($line->modifiers as $modifier)
                        <tr>
                            <td></td>
                            <td class="modifier">+{{ $modifier->quantity }}x
                                {{ $modifier->variations->name ?? $modifier->product->name }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>
    <p class="end-text">End of Order</p>
</body>

</html>