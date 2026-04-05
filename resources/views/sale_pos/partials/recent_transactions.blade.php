@php
    $subtype = '';
@endphp
@if(!empty($transaction_sub_type))
    @php
        $subtype = '?sub_type='.$transaction_sub_type;
    @endphp
@endif

@if(!empty($transactions))
    <table class="table">
        @foreach ($transactions as $transaction)
            <tr class="cursor-pointer"
                title="Customer: {{$transaction->contact?->name}}
                    @if(!empty($transaction->contact->mobile) && $transaction->contact->is_default == 0)
                        <br/>Mobile: {{$transaction->contact->mobile}}
                    @endif
                ">
                <td>
                    {{ $loop->iteration }}.
                </td>
                <td class="col-md-4">
                    {{ $transaction->invoice_no }} ({{$transaction->contact?->name}})
                    @if(!empty($transaction->table))
                        - {{$transaction->table->name}}
                    @endif
                </td>
                <td class="display_currency col-md-2">
                    {{ $transaction->final_total }}
                </td>
                <td class="col-md-6 tw-flex tw-flex-col md:tw-flex-row tw-space-x-0 md:tw-space-x-2 tw-space-y-1 md:tw-space-y-0">
                    @if($transaction->status != 'final') <!-- Hide edit and delete if status is 'final' -->
                    @if(auth()->user()->can('sell.update') || auth()->user()->can('direct_sell.update'))
                        <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'edit'], [$transaction->id]) . $subtype }}"
                           class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-info">
                            <i class="fas fa-pen text-muted" aria-hidden="true"></i>
                            @lang('messages.edit')
                        </a>
                    @endif

                    @if(auth()->user()->can('sell.delete') || auth()->user()->can('direct_sell.delete'))
                        <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'destroy'], [$transaction->id]) }}"
                           class="delete-sale tw-dw-btn tw-dw-btn-outline tw-dw-btn-error">
                            <i class="fa fa-trash text-danger"></i>
                            @lang('messages.delete')
                        </a>
                    @endif
                    @endif

                    @if(!auth()->user()->can('sell.update') && auth()->user()->can('edit_pos_payment'))
                        <a href="{{ route('edit-pos-payment', ['id' => $transaction->id]) }}"
                           title="@lang('lang_v1.add_edit_payment')" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-info">
                            <i class="fas fa-money-bill-alt text-muted"></i>
                            @lang('messages.pay')
                        </a>
                    @endif

                    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'printInvoice'], [$transaction->id]) }}"
                       class="print-invoice-link tw-dw-btn tw-dw-btn-outline tw-dw-btn-primary">
                        <i class="fa fa-print text-muted" aria-hidden="true"></i>
                        @lang('messages.print')
                    </a>

                    <!-- WhatsApp invoice button -->
                    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'showInvoiceUrl'], [$transaction->id]) }}"
                       class="view_invoice_url tw-dw-btn tw-dw-btn-outline tw-dw-btn-success">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
@else
    <p>@lang('sale.no_recent_transactions')</p>
@endif
    