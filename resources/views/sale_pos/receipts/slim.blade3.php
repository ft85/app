<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt-{{$receipt_details->invoice_no ?? ''}}</title>
  <style>
    /* CSS Variables for easy customization */
    :root {
      --primary-color: #2c3e50;
      --accent-color: #e74c3c;
      --text-color: #2c3e50;
      --border-color: #bdc3c7;
      --background-light: #f8f9fa;
      --success-color: #27ae60;
      --header-bg: #34495e;
    }

    /* Base reset and common styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', 'Arial', 'Helvetica', sans-serif;
      color: var(--text-color);
      background: white;
      font-size: 14px;
      line-height: 1.4;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      width: 100%;
      min-height: 100vh;
    }

    .ticket {
      width: 100%;
      max-width: 100%;
      background: white;
      margin: 0 auto;
    }

    /* Screen display - minimal interference with parent pages */
    @media screen {
      body {
        margin: 0;
        padding: 0;
        background: white;
        overflow-x: auto;
      }
      
      .ticket {
        width: 100%;
        max-width: 100%;
        padding: 15px;
        background: white;
        margin: 0;
      }
    }

    /* Mobile responsive - only when this is the main page */
    @media screen and (max-width: 768px) {
      .ticket {
        padding: 10px;
      }
      
      .store-name {
        font-size: 18px;
      }
      
      .header {
        flex-direction: column;
        text-align: center;
        padding: 10px 0;
      }
      
      .logo {
        height: 45px;
        margin: 0 0 8px 0;
      }
    }

    /* Print styles - optimize layout for printing */
    @media print {
      body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 12px !important;
      }
      
      .ticket {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
      }
      
      /* Ensure colors print correctly */
      * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      
      /* Make headers print-friendly */
      .products-table th {
        background: #000 !important;
        color: #fff !important;
        border: 2px solid #000 !important;
        font-weight: 900 !important;
      }
      
      /* Compact layout for printing */
      .header {
        margin-bottom: 10px !important;
        padding: 5px 0 !important;
      }
      
      .info-table {
        margin-bottom: 8px !important;
      }
      
      .info-table td {
        padding: 2px 4px !important;
        font-size: 7px !important;
      }
      
      .products-table {
        margin-bottom: 15px !important;
        font-size: 9px !important;
      }
      
      .products-table td {
        padding: 4px 3px !important;
        font-size: 8px !important;
      }
    }

    /* A4 Print Detection */
    @media print and (min-width: 200mm) {
      body {
        font-size: 14px !important;
        padding: 15mm !important;
      }
      
      .ticket {
        padding: 10mm !important;
      }
      
      .header {
        margin-bottom: 8mm;
      }
      
      .products-table {
        font-size: 12px;
      }
      
      .store-name {
        font-size: 24px;
      }
    }

    /* Thermal 80mm Print Detection */
    @media print and (max-width: 85mm) {
      body {
        font-size: 10px !important;
        padding: 2mm !important;
      }
      
      .ticket {
        padding: 1mm !important;
      }
      
      .header {
        flex-direction: column;
        text-align: center;
        margin-bottom: 4mm;
      }
      
      .logo {
        height: 40px;
        margin: 0 0 2mm 0;
      }
      
      .store-name {
        font-size: 16px;
        text-align: center;
      }
      
      .products-table {
        font-size: 8px;
      }
      
      .products-table th {
        padding: 3px 2px;
        background: #000 !important;
        color: #fff !important;
        border: 2px solid #000 !important;
        font-weight: 900 !important;
        text-shadow: none !important;
      }
      
      .products-table td {
        padding: 2px 1px;
        border: 1px solid #000 !important;
      }
      
      .info-table td {
        font-size: 7px;
        padding: 1px 2px;
      }
      
      .highlight-box {
        padding: 2mm;
        margin: 2mm 0;
        border: 3px solid #000 !important;
      }
      
      .highlight-box::before {
        background: #000 !important;
        color: #fff !important;
        border: 3px solid #000 !important;
        font-size: 9px !important;
        font-weight: 900 !important;
        letter-spacing: 0.5px !important;
        text-shadow: none !important;
        box-shadow: none !important;
      }
      
      .total-row {
        font-size: 9px;
        margin-bottom: 1mm;
      }
      
      .grand-total {
        font-size: 11px !important;
        background: #000 !important;
        color: #fff !important;
        border: 2px solid #000 !important;
      }
    }

    /* Header Section - Compact professional styling */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding: 10px 0;
      border-bottom: 3px solid var(--primary-color);
      flex-wrap: wrap;
    }

    .logo-container {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      height: 70px;
      width: auto;
      margin-right: 20px;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .store-name {
      font-size: 28px;
      font-weight: 800;
      color: var(--primary-color);
      word-wrap: break-word;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .company-tagline {
      font-size: 12px;
      color: var(--border-color);
      font-style: italic;
      margin-top: 5px;
    }

    /* Information Tables - Compact layout */
    .info-table {
      width: 100%;
      margin-bottom: 12px;
      border-collapse: collapse;
    }

    .info-table td {
      padding: 3px 6px;
      font-size: 8px;
      vertical-align: top;
      line-height: 1.3;
      border-bottom: 1px solid #f0f0f0;
    }

    .info-table td:first-child {
      font-weight: 600;
      width: 45%;
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

    /* Products Table - Enhanced professional styling */
    .products-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
      font-size: 11px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      border-radius: 8px;
      overflow: hidden;
    }

    .products-table th {
      text-align: left;
      padding: 12px 8px;
      background: linear-gradient(135deg, var(--primary-color), var(--header-bg));
      color: white;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-size: 10px;
      border: none;
    }

    .products-table td {
      padding: 10px 8px;
      border-bottom: 1px solid #ecf0f1;
      word-break: break-word;
      vertical-align: top;
      background-color: white;
    }

    .products-table tbody tr:nth-child(even) {
      background-color: #f8f9fa;
    }

    .products-table tbody tr:hover {
      background-color: #e8f4f8;
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

    /* Highlight Box for Grand Total - Enhanced professional design */
    .highlight-box {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      border: 3px solid var(--primary-color);
      border-radius: 15px;
      padding: 25px 20px 20px;
      margin: 30px 0;
      position: relative;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .highlight-box::before {
      content: "RÉSUMÉ DE FACTURATION";
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, var(--primary-color), var(--header-bg));
      color: white;
      padding: 6px 20px;
      border-radius: 20px;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      box-shadow: 0 3px 12px rgba(0,0,0,0.25);
      border: 2px solid white;
      z-index: 10;
    }

    /* Print-specific styles for billing summary */
    @media print {
      .highlight-box::before {
        background: #000 !important;
        color: #fff !important;
        border: 3px solid #000 !important;
        box-shadow: none !important;
        font-weight: 900 !important;
        font-size: 10px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
      
      .highlight-box {
        border: 4px solid #000 !important;
        background: #fff !important;
        box-shadow: none !important;
        margin: 15px 0 !important;
        padding: 20px 15px 15px !important;
      }
    }

    .highlight-box .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      padding: 5px 0;
      line-height: 1.4;
      border-bottom: 1px solid #ecf0f1;
    }

    .highlight-box .total-row:last-child {
      border-bottom: none;
    }

    .highlight-box .total-label {
      font-weight: 600;
      color: var(--text-color);
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
    }

    .highlight-box .total-value {
      font-weight: 700;
      color: var(--primary-color);
      font-size: 12px;
      text-align: right;
    }

    .highlight-box .grand-total {
      font-size: 16px;
      margin-top: 15px;
      padding: 15px 10px 5px;
      border-top: 3px double var(--primary-color);
      background: linear-gradient(135deg, var(--primary-color), var(--header-bg));
      color: white;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
    }

    .highlight-box .grand-total .total-label,
    .highlight-box .grand-total .total-value {
      color: white;
      font-weight: 800;
      font-size: 18px;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    /* Enhanced Checkbox Styles - Better for printing */
    .checkbox-container {
      display: inline-flex;
      align-items: center;
      margin: 0 10px 0 0;
      font-weight: 500;
    }

    .print-checkbox {
      position: relative;
      display: inline-block;
      width: 20px;
      height: 20px;
      margin-right: 8px;
      vertical-align: middle;
    }

    .print-checkbox input[type="checkbox"] {
      opacity: 0;
      position: absolute;
      width: 100%;
      height: 100%;
      margin: 0;
      cursor: default;
    }

    .checkbox-visual {
      position: absolute;
      top: 0;
      left: 0;
      width: 20px;
      height: 20px;
      border: 3px solid #000;
      border-radius: 3px;
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .print-checkbox input[type="checkbox"]:checked + .checkbox-visual {
      background-color: #000;
      border-color: #000;
    }

    .print-checkbox input[type="checkbox"]:checked + .checkbox-visual::after {
      content: "✓";
      color: #fff;
      font-weight: bold;
      font-size: 16px;
      line-height: 1;
      text-align: center;
      display: block;
    }

    /* Alternative solid fill style for better print visibility */
    .print-checkbox.solid input[type="checkbox"]:checked + .checkbox-visual {
      background-color: #000;
      border: 3px solid #000;
    }

    .print-checkbox.solid input[type="checkbox"]:checked + .checkbox-visual::after {
      content: "■";
      color: #000;
      font-size: 12px;
      font-weight: 900;
    }

    .option-label {
      font-weight: 600;
      color: var(--text-color);
      margin-right: 15px;
      vertical-align: middle;
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

    /* Footer Section - Professional styling */
    .footer {
      text-align: center;
      margin-top: 30px;
      padding: 20px 0;
      border-top: 2px solid var(--border-color);
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      border-radius: 10px;
    }

    .footer p {
      margin: 10px 0;
      font-size: 12px;
      color: var(--primary-color);
      font-weight: 600;
    }

    .footer p:first-child {
      font-size: 14px;
      font-weight: 700;
      color: var(--primary-color);
    }

    .bank-info {
      margin-top: 20px;
      text-align: center;
      font-size: 11px;
      line-height: 1.5;
      color: var(--border-color);
      font-style: italic;
    }

    /* Professional info table styling */
    .info-table {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      overflow: hidden;
    }

    .info-table td:first-child {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      font-weight: 700;
      color: var(--primary-color);
      text-transform: uppercase;
      font-size: 10px;
      letter-spacing: 0.5px;
    }

    /* Enhanced title styling */
    .title {
      background: linear-gradient(135deg, var(--primary-color), var(--header-bg));
      color: white;
      padding: 15px;
      margin: 20px 0;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
      text-align: center;
      font-size: 16px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    /* Auto-detect print format and optimize accordingly */
    @media print {
      /* Common print styles */
      body {
        background: white !important;
        box-shadow: none !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      .no-break {
        page-break-inside: avoid;
        break-inside: avoid;
      }
    }

    /* A4 Print Styles (default for most printers) */
    @media print and (min-width: 200mm) {
      @page {
        size: A4;
        margin: 15mm;
      }
      
      body {
        font-size: 12px;
        line-height: 1.3;
        max-width: 180mm;
        margin: 0;
        padding: 0;
      }
      
      .ticket {
        padding: 10mm;
        background: white;
      }
      
      .header {
        margin-bottom: 15mm;
      }
      
      .logo {
        height: 50px;
      }
      
      .store-name {
        font-size: 20px;
      }
      
      .products-table {
        font-size: 11px;
      }
      
      .highlight-box {
        font-size: 12px;
        padding: 10mm;
      }
    }

    /* 80mm Thermal Printer Styles */
    @media print and (max-width: 85mm) {
      @page {
        size: 80mm auto;
        margin: 2mm;
      }

      body {
        width: 76mm;
        max-width: 76mm;
        padding: 2mm;
        font-size: 11px;
        line-height: 1.2;
        margin: 0;
      }
      
      .ticket {
        width: 100%;
        padding: 0;
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
        padding: 12px 8px 8px;
        margin: 15px 0;
        font-size: 12px;
      }

      .highlight-box::before {
        font-size: 8px;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        padding: 3px 12px;
        border-radius: 12px;
        letter-spacing: 1px;
        border: 1px solid white;
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
      .print-checkbox {
        width: 16px;
        height: 16px;
        margin-right: 6px;
      }

      .checkbox-visual {
        width: 16px;
        height: 16px;
        border: 2px solid #000;
        border-radius: 2px;
      }

      .print-checkbox input[type="checkbox"]:checked + .checkbox-visual::after {
        font-size: 12px;
      }

      .option-label {
        font-size: 10px;
        margin-right: 8px;
      }
      
      .checkbox-container {
        margin: 0 8px 0 0;
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
    </div>

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
          <!-- <label class="option-checkbox">
            <span class="option-label">Oui</span>
            <input type="checkbox" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}>
            <span class="checkmark checked"></span>
          </label>          <label class="option-checkbox">
            <span class="option-label">Non</span>
            <input type="checkbox" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}>
            <span class="checkmark"></span>
          </label> -->
            <label for="TVA" class="option-label">Oui</label>
            <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}> 
            <label for="HTVA" class="option-label">Non</label>
            <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}> 
        </td>
      </tr>
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
            <div class="checkbox-container">
                <div class="print-checkbox">
                    <input type="checkbox" id="TVA" name="TVA" {{  $receipt_details->customer_vat == '1' ? 'checked' : '' }}>
                    <div class="checkbox-visual"></div>
                </div>
                <label for="TVA" class="option-label">Oui</label>
            </div>
            <div class="checkbox-container">
                <div class="print-checkbox">
                    <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->customer_vat != '1' ? 'checked' : '' }}>
                    <div class="checkbox-visual"></div>
                </div>
                <label for="HTVA" class="option-label">Non</label>
            </div>
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
            foreach($receipt_details->lines as $line) {
                $lineTaxAmount = (float) str_replace(',', '', $line['tax'] ?? '0');
                $lineQuantity = (float) str_replace(',', '', $line['quantity'] ?? '0');
                $totalVat += ($lineTaxAmount * $lineQuantity);
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
        <span class="total-label">TOTAL TTC:</span>
        <span class="total-value">{{$receipt_details->total ?? '0'}}</span>
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

      {{-- @if(!empty($receipt_details->total_due) && $receipt_details->total_due != '0.00')
      <div class="total-row">
        <span class="total-label">Reste à Payer:</span>
        <span class="total-value">{{$receipt_details->total_due}}</span>
      </div>
      @endif --}}
    </div>

    @if($receipt_details->show_barcode ?? false)
    <div class="barcode">
      <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no ?? '0', 'C128', 2,30,array(39, 48, 54), true)}}" alt="Barcode">
      <div>{{$receipt_details->invoice_no ?? ''}}</div>
    </div>
    @endif

    @if(!empty($receipt_details->rcptsign))
    <div class="barcode">
      <!--<div class="text-bold">OBR ID</div>-->
      <!--<div>{!! $receipt_details->rcptsign !!}</div>-->
    </div>
    @endif

    <div class="footer">
      <p>Merci pour votre confiance et à bientôt !</p>
      <p>Powered by <span style="color: var(--primary-color); font-weight: 700;">i-Solutions</span></p>
    </div>

    @if(!empty($receipt_details->website))
    <div class="bank-info">
      Site web: {{$receipt_details->website}}
    </div>
    @endif
  </div>

  </body>
</html>