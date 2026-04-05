<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
  <title>Receipt-{{$receipt_details->invoice_no ?? ''}}</title>
  @if(!empty($receipt_details->rcptsign))
  <style>
    /* Base Styles */
    body {
      font-family: Arial, sans-serif;
      color: #000;
      margin: 0;
      padding: 20px;
      line-height: 1.2; /* Reduced from 1.5 */
      max-width: 800px;
      margin: 0 auto;
    }

    .ticket {
      width: 100%;
      max-width: 100%;
      margin: auto;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px; /* Reduced from 20px */
    }

    .logo-container {
      display: flex;
      align-items: center;
    }

    .logo {
      height: 50px;
      width: auto;
      margin-right: 10px;
    }

    .store-name {
      font-size: 24px;
      font-weight: 600; /* Reduced from bold */
    }

    .info-table {
      width: 100%;
      margin-bottom: 15px; /* Reduced from 20px */
      border-collapse: collapse;
    }

    .info-table td {
      padding: 2px 0; /* Reduced from 4px */
      font-size: 12px;
      vertical-align: middle;
      line-height: 1.1; /* Added for tighter spacing */
    }

    .info-table td:first-child {
      font-weight: 500; /* Reduced from bold */
      width: 40%;
    }

    .info-table td:last-child {
      text-align: right;
      font-weight: 500; /* Reduced from bold */
      font-size: 13px;
    }

    .title {
      text-align: center;
      font-size: 20px;
      font-weight: 600; /* Reduced from bold */
      margin: 15px 0; /* Reduced from 20px */
      color: #333366;
      border-top: 1px solid #333366;
      border-bottom: 1px solid #333366;
      padding: 3px 0; /* Reduced from 5px */
    }

    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px; /* Reduced from 20px */
      font-size: 12px;
    }

    .products-table th {
      text-align: left;
      padding: 6px 4px; /* Reduced from 8px */
      border-bottom: 2px solid #333366;
      font-weight: 600; /* Reduced from bold */
      line-height: 1.1;
    }

    .products-table td {
      padding: 6px 4px; /* Reduced from 8px */
      border-bottom: 1px solid #ddd;
      word-break: break-word;
      line-height: 1.1;
    }

    .products-table th:first-child,
    .products-table td:first-child {
      width: 30px;
      text-align: center;
    }

    .total-section {
      width: 100%;
      margin-bottom: 15px; /* Reduced from 20px */
      font-size: 13px;
    }

    .total-section td {
      padding: 2px 0; /* Reduced from 4px */
      line-height: 1.1;
    }

    .total-section td:first-child {
      text-align: right;
      width: 70%;
      font-weight: 500; /* Reduced from bold */
    }

    .total-section td:last-child {
      text-align: right;
      width: 30%;
    }

    .big-total {
      font-weight: 600; /* Reduced from bold */
      font-size: 14px;
      border-top: 2px solid #333366;
      border-bottom: 2px solid #333366;
      padding: 3px 0; /* Reduced from 5px */
    }

    .barcode {
      text-align: center;
      margin: 15px 0; /* Reduced from 20px */
    }

    .barcode img {
      height: 50px;
      max-width: 80%;
    }

    .footer {
      text-align: center;
      margin-top: 15px; /* Reduced from 20px */
      font-weight: 500; /* Reduced from bold */
      color: #333366;
      font-size: 12px;
      line-height: 1.2;
    }

    .bank-info {
      margin-top: 8px; /* Reduced from 10px */
      text-align: center;
      font-size: 11px;
      line-height: 1.1;
    }

    /* Checkbox Styles */
    .option-checkbox {
      position: relative;
      display: inline-block;
      margin-left: 8px;
      cursor: default;
    }

    .option-checkbox input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkmark {
      position: relative;
      display: inline-block;
      width: 16px;
      height: 16px;
      background-color: #fff;
      border: 1px solid #555;
      border-radius: 3px;
      vertical-align: middle;
      margin-right: 5px;
      transition: all 0.2s ease;
    }

    .option-checkbox input:checked ~ .checkmark {
      background-color: #e74c3c;
      border-color: #e74c3c;
    }

    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    .option-checkbox input:checked ~ .checkmark:after {
      display: block;
    }

    .option-checkbox .checkmark:after {
      left: 5px;
      top: 1px;
      width: 4px;
      height: 9px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }

    .option-label {
      vertical-align: middle;
      margin-right: 5px;
      font-weight: normal;
    }

    /* Highlight Box Styles */
    .highlight-box {
      border: 2px solid #e74c3c;
      border-radius: 8px;
      padding: 12px; /* Reduced from 15px */
      margin: 15px 0; /* Reduced from 20px */
      background-color: #fff9f9;
      position: relative;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .highlight-box::before {
      content: "TOTAL";
      position: absolute;
      top: -12px;
      left: 15px;
      background-color: #e74c3c;
      color: white;
      padding: 2px 10px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600; /* Reduced from bold */
    }

    .highlight-box .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 6px; /* Reduced from 8px */
      line-height: 1.1;
    }

    .highlight-box .total-label {
      font-weight: 500; /* Reduced from bold */
      color: #333;
    }

    .highlight-box .total-value {
      font-weight: 600; /* Reduced from bold */
      color: #e74c3c;
    }

    .highlight-box .grand-total {
      font-size: 16px;
      margin-top: 8px; /* Reduced from 10px */
      padding-top: 6px; /* Reduced from 8px */
      border-top: 1px dashed #e74c3c;
    }

    /* Print Styles */
    @media print {
      * {
        font-family: 'Arial', sans-serif;
        word-break: break-word;
      }

      @page {
        size: auto;
        margin: 0;
      }

      body {
        padding: 10px;
        font-size: 9px !important;
        line-height: 1.1 !important;
      }

      .store-name {
        font-size: 18px !important;
        font-weight: 600 !important;
      }

      .title {
        font-size: 16px !important;
        font-weight: 600 !important;
      }

      .checkmark {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .highlight-box {
        border-width: 1px;
        padding: 8px; /* Reduced from 10px */
      }

      .highlight-box::before {
        font-size: 10px;
        top: -10px;
        padding: 1px 8px;
        font-weight: 600;
      }

      @media print and (max-width: 58mm) {
        .ticket {
          width: 58mm !important;
          max-width: 58mm !important;
        }

        .store-name {
          font-size: 14px !important;
          font-weight: 600 !important;
        }

        .title {
          font-size: 12px !important;
          font-weight: 600 !important;
        }

        .info-table td,
        .products-table th,
        .products-table td,
        .total-section td {
          font-size: 8px !important;
          padding: 1px 0; /* Further reduced for print */
          line-height: 1.0 !important;
        }

        .logo {
          height: 30px !important;
        }

        .checkmark {
          width: 12px !important;
          height: 12px !important;
        }

        .checkmark:after {
          left: 3px !important;
          top: 0 !important;
          width: 3px !important;
          height: 7px !important;
        }

        .highlight-box {
          padding: 6px; /* Further reduced */
          margin: 10px 0; /* Reduced */
        }

        .highlight-box::before {
          font-size: 8px;
          top: -8px;
          padding: 1px 5px;
        }

        .highlight-box .grand-total {
          font-size: 12px;
        }

        .highlight-box .total-row {
          margin-bottom: 4px; /* Further reduced */
        }
      }

      img {
        max-width: 100%;
        height: auto;
      }
    }

    /* Additional spacing reductions for strong/bold elements */
    strong, b {
      font-weight: 500; /* Make bold text less bold */
    }

    .footer p {
      margin: 4px 0; /* Reduced paragraph spacing */
    }
  </style>
  @endif
</head>
<body>
  <div class="Top">
      @if(!empty($receipt_details->letter_head))
    <img src="{{ $receipt_details->letter_head }}" alt="Letterhead" />
@endif
  </div>
  <div class="ticket">
    <div class="header">
      <div class="logo-container">
        @if(!empty($receipt_details->logo))
          <img class="logo" src="{{$receipt_details->logo}}" alt="Logo">
        @endif
        <span class="store-name">
          {{$receipt_details->display_name ?? $receipt_details->nameBusiness ?? $receipt_details->business_name ?? 'Votre Entreprise'}}
        </span>
      </div>
    </div>

    <table class="info-table">
      @if(!empty($receipt_details->tax_info1) || !empty($receipt_details->registre_commerce))
      <tr>
        <td><strong>NIF/RC:</strong></td>
        <td>
          {{$receipt_details->tax_info1 ?? ''}}
          @if(!empty($receipt_details->tax_info1) && !empty($receipt_details->registre_commerce))/@endif
          {{$receipt_details->registre_commerce ?? ''}}
        </td>
      </tr>
      @endif

      @if(!empty($receipt_details->contact))
      <tr>
        <td><strong>Téléphone:</strong></td>
        <td>{{ strip_tags($receipt_details->contact) }}</td>
      </tr>
      @endif

      @if(!empty($receipt_details->address))
      <tr>
        <td><strong>Adresse:</strong></td>
        <td>{{$receipt_details->address}}</td>
      </tr>
      @endif

      @if(!empty($receipt_details->centre_fiscal))
      <tr>
        <td><strong>Centre Fiscal:</strong></td>
        <td>{{$receipt_details->centre_fiscal}}</td>
      </tr>
      @endif

      @if(!empty($receipt_details->forme))
      <tr>
        <td><strong>Forme Juridique:</strong></td>
        <td>{{$receipt_details->forme}}</td>
      </tr>
      @endif

      @if(!empty($receipt_details->secteur))
      <tr>
        <td><strong>Secteur d'Activité:</strong></td>
        <td>{{$receipt_details->secteur}}</td>
      </tr>
      @endif

      @if(!empty($receipt_details->email))
      <tr>
        <td><strong>Email:</strong></td>
        <td>{{$receipt_details->email}}</td>
      </tr>
      @endif

      <tr>
        <td><strong>Assujetti à la TVA:</strong></td>
        <td>
          <label for="TVA">Oui</label>
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}>
            <span class="option-label">Non</span>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}>
            <!-- <span class="checkmark"></span> -->
            <!-- <label class="option-checkbox"> -->
          </label>
        </td>
      </tr>
       <!-- <tr>
        <td><strong>Assujetti à la TC:</strong></td>
        <td>
          <label class="option-checkbox">
            <span class="option-label">Oui</span>
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tc_payer == '1' ? 'checked' : '' }}>
            <span class="checkmark"></span>
          </label>
          <label class="option-checkbox">
            <span class="option-label">Non</span>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tc_payer != '1' ? 'checked' : '' }}>
            <span class="checkmark"></span>
          </label>
        </td>
      </tr> -->
          <!-- <tr>
        <td><strong>Assujetti à la PFA:</strong></td>
        <td>
          <label class="option-checkbox">
            <span class="option-label">Oui</span>
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->pfa_payer == '1' ? 'checked' : '' }}>
            <span class="checkmark"></span>
          </label>
          <label class="option-checkbox">
            <span class="option-label">Non</span>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->pfa_payer != '1' ? 'checked' : '' }}>
            <span class="checkmark"></span>
          </label>
        </td>
      </tr>   -->
    </table>

    <div class="title">FACTURE {{!empty($receipt_details->is_paid) ? 'PAYÉE' : ''}}</div>

    <table class="info-table">
      <tr>
        <td>N° Facture:</td>
        <td>{{$receipt_details->invoice_no ?? ''}}</td>
      </tr>

      <tr>
        <td>Date:</td>
        <td>{{$receipt_details->invoice_date ?? ''}}</td>
      </tr>

      @if(!empty($receipt_details->service_staff))
      <tr>
        <td>Caissier:</td>
        <td>{{$receipt_details->service_staff}}</td>
      </tr>
      @endif

      <!-- @if(!empty($receipt_details->customer_name))
        <tr>
            <td>Nom du client:</td>
            <td>{!! $receipt_details->customer_name !!}</td>
        </tr>
      @endif -->
      @if(!empty($receipt_details->table))
        <tr>
            <td>Table:</td>
            <td>{{ $receipt_details->table }}</td>
        </tr>
      @endif

      <tr>
        <td>Client:</td>
        <td>{{$receipt_details->customer_name ?? 'Client Général'}}</td>
      </tr>

      @if(!empty($receipt_details->customer_tax_number))
      <tr>
        <td>NIF Client:</td>
        <td>{{$receipt_details->customer_tax_number}}</td>
      </tr>

      <tr>
        <td>Assujetti à la TVA:</td>
        <td>
          <label class="option-checkbox">
            <span class="option-label">Oui</span>
            <input type="checkbox" {{ ($receipt_details->customer_tva_payer ?? $receipt_details->tva_payer ?? '') == '1' ? 'checked' : '' }} disabled>
            <span class="checkmark"></span>
          </label>
          <label class="option-checkbox">
            <span class="option-label">Non</span>
            <input type="checkbox" {{ ($receipt_details->customer_tva_payer ?? $receipt_details->tva_payer ?? '') != '1' ? 'checked' : '' }} disabled>
            <span class="checkmark"></span>
          </label>
        </td>
      </tr>
      @endif
    </table>

    <table class="products-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Désignation</th>
          <th>Qté</th>
          <th>P.U</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($receipt_details->lines as $line)
        <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$line['name'] ?? ''}}</td>
          <td>{{$line['quantity'] ?? '0'}}</td>
          <td>{{$line['unit_price_before_discount'] ?? '0'}}</td>
          <td>
            {{$line['line_total_exc_tax'] ?? '0'}}
            @if(!empty($line['tax_name']))
              <br>
              <small style="color: #666;">
                @if($line['tax_name'] == 'B')
                  (TVA 18%)
                @elseif($line['tax_name'] == 'C')
                  (TVA 10%)
                @elseif($line['tax_name'] == 'A')
                  (TVA 0%)
                @else
                  ({{$line['tax_name']}})
                @endif
              </small>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @php
        $totalVat = 0;
        $displayVat = '0.00';

        if (($receipt_details->tva_payer ?? '') == '1') {
            foreach($receipt_details->lines as $line) {
                $lineTaxAmount = (float) str_replace(',', '', $line['tax'] ?? '0');
                $lineQuantity = (float) str_replace(',', '', $line['quantity'] ?? '0');
                $totalVat += ($lineTaxAmount * $lineQuantity);
            }
            $displayVat = number_format($totalVat, 2);
        }
    @endphp

    @if(!empty($receipt_details->taxes))
      <table class="total-section">
        @foreach($receipt_details->taxes as $k => $v)
          <tr>
            <td>
              @if($k == 'B')
                TVA 18%
              @elseif($k == 'C')
                TVA 10%
              @elseif($k == 'A')
                TVA 0%
              @else
                {{$k}}
              @endif
            </td>
            <td>{{$v}}</td>
          </tr>
        @endforeach
      </table>
    @endif

    <div class="highlight-box">
      <!-- <div class="total-row">
        <span class="total-label">Total Articles:</span>
        <span class="total-value">{{$receipt_details->total_items ?? '0'}}</span>
      </div> -->

      <!-- <div class="total-row">
        <span class="total-label">Quantité Totale:</span>
        <span class="total-value">{{$receipt_details->total_quantity ?? '0'}}</span>
      </div> -->

      <div class="total-row">
        <span class="total-label">Sous-Total:</span>
        <span class="total-value">{{$receipt_details->subtotal_exc_tax ?? '0'}}</span>
      </div>

      @if(!empty($receipt_details->discount) && $receipt_details->discount != '0.00')
      <div class="total-row">
        <span class="total-label">Remise:</span>
        <span class="total-value">- {{$receipt_details->discount}}</span>
      </div>
      @endif

      <div class="total-row">
        <span class="total-label">TVA:</span>
        <span class="total-value">{{$displayVat}}</span>
      </div>

      @if(!empty($receipt_details->shipping_charges) && $receipt_details->shipping_charges != '0.00')
      <div class="total-row">
        <span class="total-label">Frais de Livraison:</span>
        <span class="total-value">{{$receipt_details->shipping_charges}}</span>
      </div>
      @endif

      <div class="total-row grand-total">
        <span class="total-label">TOTAL TTC:</span>
        <span class="total-value">{{$receipt_details->total ?? '0'}}</span>
      </div>

      @if(!empty($receipt_details->total_in_words))
      <div class="total-row" style="text-align: right; font-style: italic; font-size: 0.9em;">
        ({{$receipt_details->total_in_words}})
      </div>
      @endif

      @if(!empty($receipt_details->payments))
        @foreach($receipt_details->payments as $payment)
        <div class="total-row">
          <span class="total-label">Paiement ({{$payment['method'] ?? ''}}):</span>
          <span class="total-value">{{$payment['amount'] ?? '0'}}</span>
        </div>
        @endforeach
      @endif

      @if(!empty($receipt_details->total_due) && $receipt_details->total_due != '0.00')
      <div class="total-row">
        <span class="total-label">Reste à Payer:</span>
        <span class="total-value">{{$receipt_details->total_due}}</span>
      </div>
      @endif
    </div>

    @if($receipt_details->show_barcode ?? false)
    <div class="barcode">
      <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no ?? '0', 'C128', 2,30,array(39, 48, 54), true)}}">
      <div>{{$receipt_details->invoice_no ?? ''}}</div>
    </div>
    @endif

    @if(!empty($receipt_details->rcptsign))
    <div class="barcode">
      <div><strong>OBR ID</strong></div>
      <div>{!! $receipt_details->rcptsign !!}</div>
    </div>
    @endif

    @if(empty($receipt_details->is_invoice))
    <!-- <div class="mt-12 pt-8 border-t border-gray-200" style="padding-top:15px;">
        <div class="flex justify-between space-x-8">
            <div class="w-1/3" style="padding-top:10px;padding-bottom:10px;">
                <p class="mb-2 text-gray-600">Name:..................................................................</p>
                <div class="h-12 border-b border-gray-300"></div>
            </div>
            <div class="w-1/3" style="padding-top:10px;padding-bottom:10px;">
                <p class="mb-2 text-gray-600">Signature:..........................................................</p>
                <div class="h-12 border-b border-gray-300"></div>
            </div>
        </div>
    </div> -->
    @endif

    <div class="footer">
        <p>Merci pour votre confiance et à bientôt !</p>
        <p>Powered by <span class="text-indigo-600">i-Solutions</span></p>
    </div>

    @if(!empty($receipt_details->website))
    <div class="bank-info">
      Site web: {{$receipt_details->website}}
    </div>
    @endif

    <!-- <div class="bank-info">
      @if(!empty($receipt_details->bank_accounts))
        Comptes Bancaires: {{$receipt_details->bank_accounts}}
      @else
        Powered by I-Solutions
      @endif
    </div> -->
  </div>
</body>
</html>
