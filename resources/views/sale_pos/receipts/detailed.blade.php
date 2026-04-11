<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Receipt-{{$receipt_details->invoice_no ?? ''}}</title>
  <style>
    @if(isset($receipt_details->paper_size) && $receipt_details->paper_size == '80mm')
      body {
        width: 80mm;
        max-width: 80mm;
        margin: 0 auto;
        font-size: 12px;
        line-height: 1.2;
      }
      .container {
        width: 100%;
        max-width: 80mm;
        padding: 5px;
      }
      .title {
        font-size: 16px;
        text-align: center;
        margin: 10px 0;
      }
      .info-table {
        width: 100%;
        font-size: 11px;
      }
      .products-table {
        width: 100%;
        font-size: 10px;
      }
      .highlight-box {
        width: 100%;
        font-size: 11px;
      }
    @elseif(isset($receipt_details->paper_size) && $receipt_details->paper_size == '58mm')
      body {
        width: 58mm;
        max-width: 58mm;
        margin: 0 auto;
        font-size: 10px;
        line-height: 1.1;
      }
      .container {
        width: 100%;
        max-width: 58mm;
        padding: 3px;
      }
      .title {
        font-size: 14px;
        text-align: center;
        margin: 8px 0;
      }
      .info-table {
        width: 100%;
        font-size: 9px;
      }
      .products-table {
        width: 100%;
        font-size: 8px;
      }
      .highlight-box {
        width: 100%;
        font-size: 9px;
      }
    @else
      body {
        width: 210mm;
        max-width: 210mm;
        margin: 0 auto;
        font-size: 14px;
        line-height: 1.4;
      }
      .container {
        width: 100%;
        max-width: 210mm;
        padding: 20px;
      }
      .title {
        font-size: 24px;
        text-align: center;
        margin: 20px 0;
      }
      .info-table {
        width: 100%;
        font-size: 14px;
      }
      .products-table {
        width: 100%;
        font-size: 12px;
      }
      .highlight-box {
        width: 100%;
        font-size: 14px;
      }
    @endif

    /* CSS Variables for easy customization */
    /* :root {
      --primary-color: #333366;
      --accent-color: #e74c3c;
      --text-color: #000;
      --border-color: #ddd;
      --background-light: #fff9f9;
    } */

    /* Base Styles - Applied to screen and default print */
    /* * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    } */

    body {
      font-family: 'Arial', 'Helvetica', sans-serif;
      color: var(--text-color);
      /* padding: 10px;
      line-height: 1.3; */
      font-size: 14px;
      background: white;
      /* Default max-width for screen viewing or larger prints if not specified */
      /* max-width: 800px; */
    }

    .ticket {
      width: 100%;
      max-width: 100%;
      background: white;
    }

    /* Header Section */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .logo-container {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      height: 60px;
      width: auto;
      margin-right: 15px;
      object-fit: contain;
    }

    .store-name {
      font-size: 24px;
      font-weight: 700;
      color: var(--primary-color);
      word-wrap: break-word;
    }

    /* Information Tables */
    .info-table {
      width: 100%;
      margin-bottom: 20px;
      border-collapse: collapse;
    }

    .info-table td {
      padding: 5px 8px;
      font-size: 9px;
      vertical-align: top;
      line-height: 1.4;
      border-bottom: 1px solid #f0f0f0;
    }

    .info-table td:first-child {
      font-weight: 600;
      width: 40%;
      color: var(--primary-color);
    }

    .info-table td:last-child {
      text-align: right;
      font-weight: 500;
      word-break: break-word;
    }

    /* Title Section */
    .title {
      text-align: center;
      font-size: 9px;
      font-weight: 700;
      color: var(--primary-color);
      text-transform: uppercase;
    }

    /* Products Table */
    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      font-size: 9px;
    }

    .products-table th {
      text-align: left;
      padding: 10px 6px;
      border-bottom: 2px solid var(--primary-color);
      font-weight: 700;
      background-color: #f8f9fa;
      color: var(--primary-color);
    }

    .products-table td {
      padding: 8px 6px;
      border-bottom: 1px solid var(--border-color);
      word-break: break-word;
      vertical-align: top;
    }

    .products-table th:first-child,
    .products-table td:first-child {
      width: 40px;
      text-align: center;
    }

    .products-table th:nth-child(3),
    .products-table td:nth-child(3) {
      width: 60px;
      text-align: center;
    }

    .products-table th:nth-child(4),
    .products-table td:nth-child(4),
    .products-table th:nth-child(5),
    .products-table td:nth-child(5) {
      text-align: right;
      width: 80px;
    }

    /* Total Section */
    .total-section {
      width: 100%;
      margin-bottom: 20px;
      font-size: 9px;
    }

    .total-section td {
      padding: 4px 0;
      line-height: 1.3;
    }

    .total-section td:first-child {
      text-align: right;
      width: 70%;
      font-weight: 600;
      color: var(--primary-color);
    }

    .total-section td:last-child {
      text-align: right;
      width: 30%;
      font-weight: 500;
    }

    /* Highlight Box for Grand Total */
    .highlight-box {
      border: 2px solid var(--accent-color);
      border-radius: 10px;
      padding: 15px;
      margin: 20px 0;
      background-color: var(--background-light);
      position: relative;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .highlight-box::before {
      content: "TOTAL";
      position: absolute;
      top: -15px;
      left: 20px;
      background-color: var(--accent-color);
      color: white;
      padding: 4px 12px;
      border-radius: 6px;
      font-size: 8px;
      font-weight: 700;
      text-transform: uppercase;
    }

    .highlight-box .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      line-height: 1.3;
    }

    .highlight-box .total-label {
      font-weight: 600;
      color: #333;
    }

    .highlight-box .total-value {
      font-weight: 700;
      color: var(--accent-color);
    }

    .highlight-box .grand-total {
      font-size: 18px;
      margin-top: 12px;
      padding-top: 10px;
      border-top: 2px dashed var(--accent-color);
    }

    /* Checkbox Styles */
    .option-checkbox {
      position: relative;
      display: inline-block;
      margin: 0 8px;
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
      width: 18px;
      height: 18px;
      background-color: #fff;
      border: 2px solid #555;
      border-radius: 4px;
      vertical-align: middle;
      margin-right: 6px;
      transition: all 0.2s ease;
    }

    .option-checkbox input:checked ~ .checkmark {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
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
      left: 6px;
      top: 2px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }

    .option-label {
      vertical-align: middle;
      margin-right: 8px;
      font-weight: 500;
    }

    /* Barcode Section */
    .barcode {
      text-align: center;
      margin: 20px 0;
    }

    .barcode img {
      height: 60px;
      max-width: 90%;
      object-fit: contain;
    }

    .barcode div {
      margin-top: 8px;
      font-weight: 600;
      color: var(--primary-color);
    }

    /* Footer Section */
    .footer {
      text-align: center;
      margin-top: 25px;
      font-weight: 600;
      color: var(--primary-color);
      font-size: 8px;
      line-height: 1.5;
    }

    .footer p {
      margin: 8px 0;
    }

    .bank-info {
      margin-top: 15px;
      text-align: center;
      font-size: 12px;
      line-height: 1.4;
      color: #666;
    }

    /* --- 80mm Thermal Printer Styles --- */
    @media print {
      .ticket {
        width: 100%;
        max-width: 100%;
      }
      @page {
        size: 80mm auto; /* Sets paper size for 80mm thermal receipt */
        margin: 0; /* No margins on thermal prints */
      }

      body {
        width: 80mm;
        max-width: 80mm;
        padding: 5mm; /* Small padding for thermal */
        font-size: 12px;
        line-height: 1.2;
        margin: 0;
      }
      /* Header adjustments for thermal */
      .header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 10px;
      }

      .logo-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .logo {
        height: 40px;
        margin: 0 0 8px 0;
      }

      .store-name {
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 5px;
      }

      /* Info table adjustments */
      .info-table td {
        padding: 2px 4px;
        font-size: 11.5px;
        line-height: 1.1;
      }

      .info-table td:first-child {
        width: 45%;
        text-align: left;
      }

      .info-table td:last-child {
        width: 55%;
        text-align: right;
      }

      /* Title adjustments */
      .title {
        font-size: 12px;
        font-weight: 700;
        border-width: 1px;
      }

      /* Products table for thermal */
      .products-table {
        font-size: 13px;
        margin-bottom: 10px;
      }

      .products-table th,
      .products-table td {
        padding: 3px 2px;
        line-height: 1.1;
      }

      .products-table th:first-child,
      .products-table td:first-child {
        width: 25px;
      }

      .products-table th:nth-child(2),
      .products-table td:nth-child(2) {
        width: auto;
        word-break: break-word;
      }

      .products-table th:nth-child(3),
      .products-table td:nth-child(3) {
        width: 35px;
        text-align: center;
      }

      .products-table th:nth-child(4),
      .products-table td:nth-child(4),
      .products-table th:nth-child(5),
      .products-table td:nth-child(5) {
        width: 50px;
        text-align: right;
        font-size: 12px;
      }

      /* Total section for thermal */
      .total-section {
        font-size: 13px;
        margin-bottom: 8px;
      }

      .total-section td {
        padding: 1px 0;
        line-height: 1.1;
      }

      /* Highlight box for thermal */
      .highlight-box {
        border-width: 1px;
        border-radius: 6px;
        padding: 5px;
        margin: 10px 0;
        font-size: 12px;
      }

      .highlight-box::before {
        font-size: 11px;
        top: -10px;
        left: 10px;
        padding: 2px 8px;
        border-radius: 4px;
      }

      .highlight-box .total-row {
        margin-bottom: 4px;
        font-size: 12px;
      }

      .highlight-box .grand-total {
        font-size: 12px;
        margin-top: 0px;
        padding-top: 0px;
        border-top-width: 1px;
      }

      /* Checkbox adjustments for thermal */
      .checkmark {
        width: 12px;
        height: 12px;
        border-width: 1px;
        margin-right: 4px;
      }

      .checkmark:after {
        left: 3px;
        top: 1px;
        width: 3px;
        height: 6px;
        border-width: 0 1px 1px 0;
      }

      .option-label {
        font-size: 12px;
        margin-right: 6px;
      }

      /* Barcode for thermal */
      .barcode {
        margin: 8px 0;
      }

      .barcode img {
        height: 40px;
        max-width: 95%;
      }

      .barcode div {
        font-size: 12px;
        margin-top: 4px;
      }

      /* Footer for thermal */
      .footer {
        font-size: 12px;
        margin-top: 10px;
        line-height: 1.3;
      }

      .footer p {
        margin: 4px 0;
      }

      .bank-info {
        font-size: 12px;
        margin-top: 8px;
      }

      /* Hide elements that don't print well on thermal */
      .highlight-box {
        box-shadow: none;
        background-color: transparent !important;
        border-style: dashed;
      }

      /* Ensure proper color printing */
      * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
    }

    /* --- A4 Print Styles --- */
    @media print and (min-width: 210mm) { /* A4 is 210mm wide */
      @page {
        size: A4;
        margin: 15mm; /* Standard A4 margins */
      }

      body {
        width: auto; /* Allow body to take full width of A4 */
        max-width: 100%;
        padding: 0; /* Remove padding as margin is set by @page */
        font-size: 12px; /* Slightly smaller than screen for A4 */
      }

      .ticket {
        max-width: 100%; /* Ensure ticket expands for A4 */
      }

      /* Adjust font sizes for A4 */
      .store-name {
        font-size: 20px;
      }

      .title {
        font-size: 10px;
      }

      .products-table {
        font-size: 9px;
      }

      .highlight-box .grand-total {
        font-size: 16px;
      }

      /* Reset thermal-specific styles for A4 */
      .header {
        flex-direction: row; /* Revert to row for A4 */
        align-items: center;
        text-align: left;
        margin-bottom: 20px;
      }

      .logo-container {
        flex-direction: row;
        align-items: center;
        text-align: left;
      }

      .logo {
        height: 60px; /* Revert to original size */
        margin-right: 15px;
      }

      .store-name {
        text-align: left;
        margin-top: 0;
      }

      .info-table td {
        padding: 5px 8px; /* Revert to original padding */
        font-size: 13px; /* Revert to original font size */
      }

      .info-table td:first-child {
        width: 40%;
        text-align: left;
      }

      .info-table td:last-child {
        width: 60%; /* Adjust if needed for better alignment */
        text-align: right;
      }

      .products-table th,
      .products-table td {
        padding: 8px 6px; /* Revert to original padding */
      }

      .products-table th:first-child,
      .products-table td:first-child {
        width: 40px;
      }

      .products-table th:nth-child(3),
      .products-table td:nth-child(3) {
        width: 60px;
      }

      .products-table th:nth-child(4),
      .products-table td:nth-child(4),
      .products-table th:nth-child(5),
      .products-table td:nth-child(5) {
        width: 80px;
      }

      .total-section {
        font-size: 14px; /* Revert to original font size */
      }

      .highlight-box {
        border-width: 2px;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Re-add shadow for A4 */
        background-color: var(--background-light); /* Re-add background color */
        border-style: solid; /* Revert to solid border */
      }

      .highlight-box::before {
        font-size: 12px;
        top: -15px;
        left: 20px;
        padding: 4px 12px;
        border-radius: 6px;
      }

      .highlight-box .total-row {
        margin-bottom: 8px;
        font-size: 9px;
      }

      .highlight-box .grand-total {
        font-size: 8px;
        margin-top: 12px;
        padding-top: 10px;
        border-top-width: 2px;
      }

      .checkmark {
        width: 18px;
        height: 18px;
        border-width: 2px;
      }

      .checkmark:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border-width: 0 2px 2px 0;
      }

      .option-label {
        font-size: 13px;
      }

      .barcode {
        margin: 20px 0;
      }

      .barcode img {
        height: 60px;
        max-width: 90%;
      }

      .barcode div {
        font-size: 14px;
        margin-top: 8px;
      }

      .footer {
        font-size: 14px;
        margin-top: 25px;
      }

      .footer p {
        margin: 8px 0;
      }

      .bank-info {
        font-size: 8px;
        margin-top: 15px;
      }
    }

    /* Screen media queries for responsive design */
    @media screen and (max-width: 480px) {
      body {
        padding: 10px;
        font-size: 12px;
      }

      .header {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .logo-container {
        flex-direction: column;
        align-items: center;
        margin-bottom: 10px;
      }

      .store-name {
        font-size: 18px;
        text-align: center;
        margin-top: 8px;
      }

      .info-table td {
        padding: 3px 4px;
        font-size: 11px;
      }

      .products-table {
        font-size: 10px;
      }

      .products-table th,
      .products-table td {
        padding: 4px 2px;
      }

      .highlight-box {
        padding: 10px;
        font-size: 11px;
      }

      .highlight-box .grand-total {
        font-size: 10px;
      }
    }

    /* Very small screens (mobile portrait) */
    @media screen and (max-width: 320px) {
      body {
        padding: 8px;
        font-size: 11px;
      }

      .store-name {
        font-size: 16px;
      }

      .title {
        font-size: 10px;
      }

      .products-table {
        font-size: 9px;
      }

      .highlight-box {
        padding: 8px;
        font-size: 8px;
      }
    }

    /* Utility classes */
    .text-small {
      font-size: 0.8em;
      color: #666;
    }

    .text-bold {
      font-weight: 700;
    }

    .text-center {
      text-align: center;
    }

    .mb-small {
      margin-bottom: 8px;
    }

    .no-break {
      page-break-inside: avoid;
    }
  </style>
</head>
<body>
  <div class="Top">
    @if(!empty($receipt_details->letter_head))
      <img src="{{ $receipt_details->letter_head }}" alt="Letterhead" style="max-width: 100%; height: auto; margin-bottom: 10px;" />
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

      @if(empty($receipt_details->rcptsign))
      <div class="quotation-info" style="text-align: center; margin-top: 10px; font-size: 11px; color: #666;">
        @if(!empty($receipt_details->address))
        <div style="margin-bottom: 5px;">{{$receipt_details->address}}</div>
        @endif

        @if(!empty($receipt_details->service_staff) || !empty($receipt_details->table))
        <div style="margin-bottom: 5px;">
          @if(!empty($receipt_details->service_staff))
            <span>Waiter: {{$receipt_details->service_staff}}</span>
          @endif
          @if(!empty($receipt_details->service_staff) && !empty($receipt_details->table))
            <span> | </span>
          @endif
          @if(!empty($receipt_details->table))
            <span>Table: {{$receipt_details->table}}</span>
          @endif
        </div>
        @endif
      </div>
      @endif
    </div>

    @if(!empty($receipt_details->rcptsign))
    <table class="info-table">
      @if(!empty($receipt_details->tax_info1) || !empty($receipt_details->registre_commerce))
      <tr>
        <td><strong>NIF/RC:</strong></td>
        <td>
          {{$receipt_details->tax_info1 ?? ''}}
          @if(!empty($receipt_details->tax_info1) && !empty($receipt_details->registre_commerce)) / @endif
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
    <label for="TVA" class="option-label {{ $receipt_details->tva_payer == '1' ? 'font-bold' : '' }}">Oui</label>
    <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}>
    
    <label for="HTVA" class="option-label {{ $receipt_details->tva_payer != '1' ? 'font-bold' : '' }}">Non</label>
    <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}>
</td>
      </tr>
    </table>
    @endif

    <div class="title">
        @php
            $is_quotation = (isset($receipt_details->is_quotation) && ($receipt_details->is_quotation == 1 || $receipt_details->is_quotation === '1'))
                || (isset($receipt_details->sub_status) && $receipt_details->sub_status === 'quotation')
                || (!empty($receipt_details->invoice_heading) && stripos($receipt_details->invoice_heading, 'quotation') !== false);

            $is_credit = (isset($receipt_details->payment_status) && ($receipt_details->payment_status == 'due' || $receipt_details->payment_status == 'partial'))
                || (isset($receipt_details->is_credit_sale) && ($receipt_details->is_credit_sale == 1 || $receipt_details->is_credit_sale === '1'));
        @endphp

        @if($is_quotation)
            QUOTATION
        @elseif($is_credit)
            FACTURE à Credit
        @else
            FACTURE {{!empty($receipt_details->is_paid) ? 'PAYÉE' : ''}}
        @endif
    </div>

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
            <label for="TVA" class="option-label">Oui</label>
            <input type="checkbox" id="TVA" name="TVA" {{  $receipt_details->customer_vat == '1' ? 'checked' : '' }}> 
            <label for="HTVA" class="option-label">Non</label>
            <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->customer_vat != '1' ? 'checked' : '' }}> 
        </td>
      </tr>
      @endif
      @if(!empty($receipt_details->sales_person))
            <tr>
         <td>
          Cashier: 
        </td>
        <td>{{$receipt_details->sales_person}}</td>
      </tr>
      @endif
    </table>

    <table class="products-table no-break">
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
          <td>
            {{$line['name'] ?? ''}}
            <!-- @if(!empty($line['tax_name']))
              <br>
              <small class="text-small">
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
            @endif -->
          </td>
          <td>{{$line['quantity'] ?? '0'}}</td>
          <td>{{$line['base_unit_price'] ?? '0'}}</td>
          <td>{{$line['line_total'] ?? '0'}}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @php
        $totalVat = 0;
        $displayVat = '0.00';

        if (($receipt_details->tva_payer ?? '') == '1') {
            if (!empty($receipt_details->lines)) {
                foreach($receipt_details->lines as $line) {
                    $lineTaxAmount = $line['tax'] ?? '0';
                    $lineTaxAmount = preg_replace('/[^\d.,]/', '', $lineTaxAmount);
                    $lineTaxAmount = str_replace(',', '', $lineTaxAmount);
                    $totalVat += (float)$lineTaxAmount;
                }
            }

            if ($totalVat == 0 && !empty($receipt_details->taxes)) {
                foreach($receipt_details->taxes as $taxCode => $taxAmount) {
                    $taxAmount = preg_replace('/[^\d.,]/', '', $taxAmount);
                    $taxAmount = str_replace(',', '', $taxAmount);
                    $totalVat += (float)$taxAmount;
                }
            }

            if ($totalVat == 0) {
                $subtotal = $receipt_details->subtotal_exc_tax ?? '0';
                $subtotal = preg_replace('/[^\d.,]/', '', $subtotal);
                $subtotal = str_replace(',', '', $subtotal);
                $subtotal = (float)$subtotal;

                $total = $receipt_details->total ?? '0';
                $total = preg_replace('/[^\d.,]/', '', $total);
                $total = str_replace(',', '', $total);
                $total = (float)$total;

                $totalVat = $total - $subtotal;
            }

            $displayVat = number_format($totalVat, 2);
        }
    @endphp

    @if(!empty($receipt_details->taxes))
      <!-- <table class="total-section">
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
      </table> -->
    @endif

    <div class="highlight-box no-break">
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
        <span class="total-label">TVA
          @foreach($receipt_details->taxes as $k => $v)
          <tr>
            <td>
              @if($k == 'B')
              18%
              @elseif($k == 'C')
               10%
              @elseif($k == 'A')
               0%
              @endif
            </td>
          </tr>
        </span>
        @endforeach
        <span class="total-value">{{$displayVat}}</span>
        <!-- <span class="total-value"></span> -->
      </div>

      @if(!empty($receipt_details->shipping_charges) && $receipt_details->shipping_charges != '0.00')
      <div class="total-row">
        <span class="total-label">Frais de Livraison:</span>
        <span class="total-value">{{$receipt_details->shipping_charges}}</span>
      </div>
      @endif

      <div class="total-row grand-total">
        <span class="total-value">{{ str_replace(['→', '-->', '->'], '', $receipt_details->total ?? '0') }}</span>
      </div>

      @if(!empty($receipt_details->total_in_words))
      <div class="total-row" style="text-align: center; font-style: italic; font-size: 0.9em; margin-top: 8px;">
        ({{stripslashes($receipt_details->total_in_words)}})
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

      <!-- @if(!empty($receipt_details->total_due) && $receipt_details->total_due != '0.00')
      <div class="total-row">
        <span class="total-label">Reste à Payer:</span>
        <span class="total-value">{{$receipt_details->total_due}}</span>
      </div>
      @endif 
    </div>-->

    <!--@if($receipt_details->show_barcode ?? false)
    <div class="barcode">
      <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no ?? '0', 'C128', 2,30,array(39, 48, 54), true)}}" alt="Barcode">
      <div>{{$receipt_details->invoice_no ?? ''}}</div>
    </div>
    @endif

    @if(!empty($receipt_details->rcptsign))
    <div class="barcode">
      <div class="text-bold">OBR ID</div>
      <div>{!! $receipt_details->rcptsign !!}</div>
    </div>
    @endif-->

    @if(!empty($receipt_details->current_exchange_rate) && $receipt_details->current_exchange_rate > 1)
    @php
        $totalBIF = $receipt_details->calculated_total_from_lines ?? 0;
        $exchangeRate = $receipt_details->current_exchange_rate ?? 1;
        $totalUSD = $receipt_details->usd_equivalent ?? 0;
    @endphp
    <div class="currency-info" style="margin-top: 15px; padding: 10px; border-top: 1px solid #ddd; font-size: 11px;">
      <div style="text-align: center; font-weight: bold; margin-bottom: 8px;">CURRENCY INFORMATION</div>

      <div style="margin-bottom: 5px;">
        <strong>Exchange Rate:</strong> 1 USD = {{number_format($exchangeRate, 2)}} BIF
      </div>

      <div style="margin-bottom: 5px;">
        <strong>Amount in USD:</strong> ${{number_format($totalUSD, 2)}}
      </div>

      <div style="font-size: 10px; color: #666; text-align: center; margin-top: 8px;">
        Rate applied on: {{date('d/m/Y H:i')}}
      </div>
    </div>
    @endif

    @if(empty($receipt_details->rcptsign))
    <div class="footer" style="margin-top: 15px; text-align: center; padding: 15px; border-top: 1px solid #e0e0e0;">
      <p style="color: #7f8c8d; font-style: italic; margin-bottom: 8px;">{{ str_replace(['→', '-->', '->'], '', 'Thank you for choosing our services!') }}</p>
      <p style="color: #95a5a6; font-size: 12px;">{{ str_replace(['→', '-->', '->'], '', 'Powered by') }} <span style="color: #3498db; font-weight: 700;">{{ str_replace(['→', '-->', '->'], '', 'i-Solutions') }}</span></p>
    </div>
    @else
    <div class="footer">
      <p>{{ str_replace(['→', '-->', '->'], '', 'Merci pour votre confiance et à bientôt !') }}</p>
      <p>{{ str_replace(['→', '-->', '->'], '', 'Powered by') }} <span style="color: var(--primary-color); font-weight: 700;">{{ str_replace(['→', '-->', '->'], '', 'i-Solutions') }}</span></p>
    </div>
    @endif

    @if(!empty($receipt_details->website))
    <div class="bank-info">
      Site web: {{$receipt_details->website}}
    </div>
    @endif
  </div>

  </body>
</html>
