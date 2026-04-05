<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">
                @lang('lang_v1.view_invoice_url') - @lang('sale.invoice_no'): {{$transaction->invoice_no}}
            </h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <input type="text" class="form-control" value="{{$url}}" id="invoice_url">
                <p class="help-block">@lang('lang_v1.invoice_url_help')</p>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" value="{{ $phone_number ?? '' }}"
                       placeholder="Enter phone number">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">
                @lang('messages.close')
            </button>

            <a href="{{$url}}" id="view_invoice_url" target="_blank" rel="noopener"
               class="tw-dw-btn tw-dw-btn-primary tw-text-white">
                @lang('messages.view')
            </a>

            <a href="#" id="whatsapp_button" class="tw-dw-btn tw-dw-btn-success tw-text-white">
                <i class="fab fa-whatsapp"></i> @lang('Send')
            </a>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('input#invoice_url').click(function() {
        $(this).select().focus();
    });

    $('#whatsapp_button').click(function(e) {
        e.preventDefault();
        var phoneNumber = $('#phone_number').val().replace(/\s+/g, '');
        if (!phoneNumber) {
            alert('Please enter a phone number');
            return;
        }
        var message = @json($message);
        var whatsappUrl = 'https://wa.me/' + phoneNumber + '?text=' + encodeURIComponent(message);
        window.open(whatsappUrl, '_blank');
    });
</script>