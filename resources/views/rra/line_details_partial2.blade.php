<div class="form-group row">
  <label for="add-to-stock-quantity" class="col-form-label col-sm-2">Quantity</label>
  <div class="col-sm-10">
    <input type="number" id="add-to-stock-quantity" step="any" class="form-control" placeholder="Quantity..." min="0" value="{{ $lineDetails2->qty ?? 0 }}" />
  </div>
</div>