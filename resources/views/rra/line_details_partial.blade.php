<div>
  <p><strong>Item:</strong> {{ $lineDetails->item_nm }}</p>
  <p><strong>Quantity:</strong> <span id="qty-value">{{ $lineDetails->qty }}</span></p>
  <p><strong>Amount:</strong> <span id="invc_fcur_amt-value">{{ $lineDetails->invc_fcur_amt }}</span></p>
  <p><strong>Currency:</strong> {{ $lineDetails->invc_fcur_cd }}</p>
  <p><strong>Exchange Rate:</strong> {{ $lineDetails->exchange_rate }}</p>

  
  <!-- Add more details as needed -->
</div>
