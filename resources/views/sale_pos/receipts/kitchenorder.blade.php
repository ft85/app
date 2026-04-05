<style>
    /* Styles pour le reçu compact */
    .receipt-header {
        text-align: center;
        font-family: Arial, sans-serif;
    }

    .company-name {
        font-size: 14px; /* Taille ajustée pour le nom de la société */
        font-weight: bold;
    }

    .branch-name {
        font-size: 12px; /* Taille réduite pour la branche de la société */
        color: #555; /* Couleur douce */
    }

    .invoice-number {
        font-size: 12px; /* Taille ajustée pour le numéro du ticket */
        font-weight: bold;
        color: #000; /* Couleur noire pour le numéro du ticket */
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px;
    }

    .info-table td {
        font-size: 8px; /* Taille réduite pour le texte */
        padding: 0; /* Espacement supprimé */
    }

    .receipt-table {
        width: 100%;
        border-collapse: collapse;
    }

    .receipt-table thead {
        background-color: #357ca5;
        color: white;
        font-size: 10px; /* Taille réduite pour les en-têtes */
    }

    .receipt-table th, .receipt-table td {
        padding: 0; /* Espacement supprimé */
        font-size: 8px; /* Taille réduite pour le texte */
    }

    .receipt-table th {
        border-bottom: 1px dotted #ddd; /* Ligne en pointillé pour les titres */
    }

    .receipt-table td {
        text-align: left; /* Alignement des noms de produits à gauche */
    }

    .receipt-table .text-right {
        text-align: right; /* Alignement des quantités à droite */
    }

    .receipt-table tfoot td {
        border-top: 1px dotted #ddd; /* Ligne en pointillé pour la fin du tableau */
    }

    .no-border {
        border: none;
    }
</style>

<div class="receipt-header">
    <div class="company-name">{{$receipt_details->display_name}}</div>
    <div class="branch-name">{{ $receipt_details->location_custom_fields }}</div>
    <div class="invoice-number">#{{$receipt_details->invoice_no}}</div>
</div>

<table class="info-table no-border">
    <tr>
        <td><b>@lang('restaurant.placed_at')</b>: {{$receipt_details->invoice_date}}</td>
    </tr>
    <tr>
        <td><b>@lang('restaurant.table')</b>: {{$sell->table->name ?? ''}}</td>
    </tr>
    <tr>
        <td><b>@lang('restaurant.service_staff')</b>: {{$order->service_staff_name ?? ''}}</td>
    </tr>
</table>

<table class="receipt-table no-border">
    <thead>
        <tr>
            <th style="width: 10%;">#</th>
            <th style="width: 60%;">{{$receipt_details->table_product_label}}</th>
            <th style="width: 30%; text-align: right;">{{$receipt_details->table_qty_label}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receipt_details->lines as $line)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td style="word-break: break-word;">
                    {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}}
                </td>
                <td class="text-right">{{$line['quantity']}}</td>
            </tr>
            @if(!empty($line['modifiers']))
                @foreach($line['modifiers'] as $modifier)
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            {{$modifier['name']}} {{$modifier['variation']}} 
                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif 
                            @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif 
                        </td>
                        <td class="text-right">{{$modifier['quantity']}} {{$modifier['units']}}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="no-border"></td><hr style="margin: 0; width: 100%;">
        </tr>
    </tfoot>
</table>

