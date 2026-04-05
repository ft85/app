<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUCL Invoice | {{$payment->sdc_id}} {{ $payment->invoiceno }}</title>
 <style>
        body {
            font-family: Arial, sans-serif;
            color: #000000;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: auto;
            padding: 20px;
            background-color: #fff;
            font-size: 14px;
            max-width: 400px;
        }
		.container2 {
			text-align: center; /* Centre le contenu dans la page */
			margin: auto;
			padding: 0px;
			max-width: 150px; /* Largeur spécifique pour container2 */
			background-color: #e0f7fa; /* Arrière-plan bleu clair */
			color: #2c3584; /* Couleur du texte en bleu foncé */
		}
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px; /* No space between table and dash line */
        }
        .title {
            background-color: #b0b0b0;
            font-weight: bold;
        }
        .left {
            text-align: left;
            vertical-align: top;
        }
        .right {
            text-align: right;
            vertical-align: top;
        }
        .center {
            text-align: center;
            vertical-align: top;
        }
        .ticket .title {
            font-size: 16px;
            font-weight: bold;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        .border-dashed {
            border: 2px dashed;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 0px auto; /* No space between dashed border and content */
        }
        .dash-line {
            border-top: 1px dashed gray;
            margin: 0px; /* No margin to remove space */
            width: 100%;
        }
        hr {
            margin: 0;
            width: 100%;
        }
		.title {
			background-color: #b0b0b0 !important;
		}
    </style>
</head>
<body>

    <div class="container">
        <!-- Header with logos -->
        <table>
            <tr>
                <!-- Logo RRA à gauche -->
                <td class="left" valign="top">
                    <img src="https://pos.injonge.rw/public/img/rra_logo2.png" height="80" width="80"> 
                </td>
                <!-- EUCL logo au centre -->
                <td class="center" valign="top">
                    <img src="https://pos.injonge.rw/public/img/eucl_logo.png" height="80" width="211">
                </td>
                <!-- Logo RRA à droite -->
                <td class="right" valign="top">
                    <img src="https://pos.injonge.rw/public/img/RRA_EBMV2.png" height="80" width="80">
                </td>
            </tr>
        </table>

        <!-- EUCL Info -->
        <div class="container2">
            <!-- EUCL Info centré et aligné au milieu de la page -->
            <table>
                <tr>
                    <td class="label">TIN:</td>
                    <td class="value">103372638</td>
                </tr>
                <tr>
                    <td class="label">Phone:</td>
                    <td class="value">2727</td>
                </tr>
                <tr>
                    <td class="label">E-mail:</td>
                    <td class="value">info@eucl.reg.rw</td>
                </tr>
            </table>
        </div>

         <div class="dash-line"></div>

        <!-- Client Info -->
        <table>
            <tr>
                <td class="left">Meter No:</td>
                <td class="right">{{ $payment->meter_number }}</td>
            </tr>
            <tr>
                <td class="left">Client Name:</td>
                <td class="right">{{ $payment->metername }}</td>
            </tr>
            <tr>
                <td class="left">Client TIN:</td>
                <td class="right">{{ $payment->clienttin }}</td>
            </tr>
        </table>

         <div class="dash-line"></div>

        <!-- Service Info -->
        <table>
            <tr>
                <td colspan="2" class="left">Electricity</td>
            </tr>
            <tr>
                <td class="left">{{ number_format($payment->amount, 2, '.', ',') }} x 1</td>
                <td class="right">{{ number_format($payment->amount, 2, '.', ',') }} (B)</td>
            </tr>
        </table>

         <div class="dash-line"></div>
        <!-- Table 1: Total Amount -->
        <table>
            <tr>
                <td class="left">TOTAL:</td>
                <td class="right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="left">TOTAL A-EX:</td>
                <td class="right">0.00</td>
            </tr>
            <tr>
                <td class="left">TOTAL B-18.00%:</td>
                <td class="right">{{ $payment->tax }}</td>
            </tr>
            <tr>
                <td class="left">TOTAL TAX B:</td>
                <td class="right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="left">TOTAL TAX:</td>
                <td class="right">{{ $payment->tax }}</td>
            </tr>
        </table>

        <!-- Dash Line -->
        <div class="dash-line"></div>

        <!-- Table 2: Payment -->
        <table>
            <tr>
                <td class="left">CASH:</td>
                <td class="right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="left">ITEMS NUMBER:</td>
                <td class="right">1</td>
            </tr>
        </table>


        <!-- Electricity Credit Details -->
<?php

function formatTokenWithoutDashes($token) {
    // Diviser le token en groupes de 4 chiffres, sans les séparer par des tirets
    return implode(' ', str_split($token, 4));
}
?>


        <table>
			<tr class="title"><TD colspan=2 class="center" style="font-weight:bold">ELECTRICITY CREDIT DETAILS</td></tr>
            <tr>
                <td colspan="2" style="font-weight:bold;" class="center">Token:</td>
            </tr>
            <tr>
                <td colspan="2" class="center">
					<div class="border-dashed">
						{{ formatTokenWithoutDashes($payment->token) }}
					</div>
                </td>
            </tr>
            <tr>
                <td class="left" style="font-weight:bold;">Vendor:</td>
                <td class="right">AFRICA SMART INVESTMENT</td>
            </tr>
            <tr>
                <td class="left" style="font-weight:bold;">Meter Number:</td>
                <td class="right">{{ $payment->meter_number }}</td>
            </tr>
            <tr>
                <td class="left" style="font-weight:bold;">Total Units:</td>
                <td class="right">{{ $payment->units }}</td>
            </tr>
            <tr>
                <td class="left" style="font-weight:bold;">Total Paid:</td>
                <td class="right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td class="left" style="font-weight:bold;">Pricing:</td>
                <td class="right">{{ $payment->tokenexplanation }}</td>
            </tr>
        </table>

         <div class="dash-line"></div>

        <!-- SDC Information -->
        @if(!empty($payment->signature))
        <table border="0"  width="100%" cellpadding="1" cellspacing="1">    
			<tr class="title"><TD colspan=2 class="center" style="font-weight:bold">SDC INFORMATION</td></tr>
            <tr>
                <td class="left">RECEIPT NUMBER:</td>
                <td class="right">{{$payment->fullinvoiceno}}</td>
            </tr> 
            <tr>
                <td class="left">SDC ID:</td>
                <td class="right">{{$payment->sdc_id}}</td>
            </tr> 
            <tr>
                <td colspan="2" class="center"><b>Internal Data:</b></td>
            </tr> 
            <tr>
                <td colspan="2" class="center" style="font-size: 12px;">
                    @php
                    $internal_data = $payment->internaldata;
                    $new_rcptsign = chunk_split($internal_data, 4, '-');
                    $new_rcptsign = rtrim($new_rcptsign, '-');
                    echo $new_rcptsign;
                    @endphp
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center"><b>Receipt Signature:</b></td>
            </tr> 
            <tr>   
                <td colspan="2" class="center" style="font-size: 12px;">
                    @php
                        $rcptsign = $payment->rcptsign;
                        $new_rcptsign = chunk_split($rcptsign, 4, '-');
                        $new_rcptsign = rtrim($new_rcptsign, '-');
                        echo $new_rcptsign;
                    @endphp
                </td>
            </tr>        
            <tr>
                <td colspan="2" class="center">
                    <img class="center-block mt-5" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($payment->qrtext, 'QRCODE', 3, 3, [39, 48, 54]) }}">
                </td>
            </tr>   
            <tr>
                <td class="left">RECEIPT NUMBER:</td>
                <td class="right">{{ $payment->invoiceno }}</td>
            </tr> 
            <tr>
                <td class="left">DATE: {{ \Carbon\Carbon::createFromFormat('YmdHis', $payment->tdate)->format('Y-m-d') }}</td>
                <td class="right">TIME: {{ \Carbon\Carbon::createFromFormat('YmdHis', $payment->tdate)->format('H:i:s') }}</td>
            </tr> 
            <tr>
                <td class="left">MRC:</td>
                <td class="right">{{ $payment->mrc }}</td>
            </tr> 
        </table>
        @endif



        <!-- Footer Information -->
	<table border="0"  width="100%" cellpadding = "1" cellspacing = "1">
			<tr><td><hr style="margin: 0; width: 100%;"></td></tr>
			<tr><TD class="center">Powered by Inhills Technology</td></tr>
	</table>
    </div>

</body>
</html>
