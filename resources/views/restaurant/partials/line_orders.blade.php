<style>
      .table {
            background-color: white;
            border-collapse: collapse;
            /* Remove inner borders */
            width: 100%;
      }

      .table th,
      .table td {
            border: none;
            /* Remove all borders */
            padding: 10px;
      }

      .table th {
            text-align: left;
      }

      .table td {
            border-bottom: 1px solid #ddd;
            /* Only horizontal lines */
      }

      .table td .btn {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            border: none;
            margin-right: 5px;
            text-align: center;
      }

      .table td .btn.bg-yellow {
            background-color: #f39c12;
            color: white;
      }

      .table td .btn.bg-yellow:hover {
            background-color: #e08e0b;
      }

      .table td .btn.bg-green {
            background-color: #00a65a;
            color: white;
      }

      .table td .btn.bg-green:hover {
            background-color: #008d4c;
      }

      .table td .btn.bg-gray {
            background-color: #d2d6de;
            color: white;
      }

      .table td .btn.bg-gray:hover {
            background-color: #b5b9bf;
      }

      .printed {
            background-color: #f0f0f0;
            color: #999;
      }

      .modal-body .action-buttons {
            display: flex;
            justify-content: space-between;
      }

      .modal-body .action-buttons .btn {
            width: 30%;
      }

      .printed {
            background-color: #85FFBD;
            color: black;
      }

      .not-printed {
            background-color: white;
            color: black;
      }
</style>

@if(empty($groupedOrders))
<div class="col-md-12">
      <h4 class="text-center">@lang('restaurant.no_orders_found')</h4>
</div>
@else
<table class="table table-bordered">
      <thead>
            <tr>
                  <th>Invoice No</th>
                  <!-- <th>Order Details</th> -->
                  <th>@lang('sale.product')</th>
                  <th>@lang('lang_v1.quantity')</th>
                  <th>@lang('restaurant.table')</th>
                  <th>@lang('contact.customer')</th>
                  <th>@lang('restaurant.placed_at')</th>
                  <th># @lang('Action')</th>
            </tr>
      </thead>
      <tbody>
            @foreach($groupedOrders as $transactionGroup)
            @foreach($transactionGroup as $printerGroup)
            @foreach($printerGroup['items'] as $index => $item)
            <tr data-item-id="{{ $item['id'] }}"
                  @if($item['is_printed'])
                  class="printed"
                  @else
                  class="not-printed"
                  @endif>
                  @if($index === 0)
                  <td class="text-center" rowspan="{{ count($printerGroup['items']) }}">
                        {{ $printerGroup['invoice_no'] }}
                  </td>
                  <!-- <td rowspan="{{ count($printerGroup['items']) }}">
                        <a href="#" class="btn-modal text-info"
                              data-href="{{ action([\App\Http\Controllers\SellController::class, 'show'], [$printerGroup['transaction_id']]) }}"
                              data-container=".view_modal">
                              @lang('restaurant.order_details') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                  </td> -->
                  @endif
                  <td>
                        {{ $item['product_name'] }}
                        @if(!empty($item['modifiers']))
                        <ul class="modifier-list">
                              @foreach($item['modifiers'] as $modifier)
                              <li>+ {{ $modifier['name'] }} ({{ $modifier['quantity'] }}) - {{ $modifier['variation_name'] }}</li>
                              @endforeach
                        </ul>
                        @endif
                  </td>
                  <td>{{ $item['quantity'] }}{{ $item['unit'] }}</td>
                  @if($index === 0)
                  <td rowspan="{{ count($printerGroup['items']) }}">{{ $printerGroup['table_name'] }}</td>
                  <td rowspan="{{ count($printerGroup['items']) }}">{{ $printerGroup['customer_name'] }}</td>
                  @endif
                  @if($index === 0)
                  <td rowspan="{{ count($printerGroup['items']) }}">
                        {{ \Carbon\Carbon::parse($printerGroup['created_at'])->format('d/m/Y H:i') }}
                  </td>
                  @endif
                  @if($index === 0)
                  <td rowspan="{{ count($printerGroup['items']) }}">
                        <div class="text-center">
                              <button type="button" class="btn btn-flat bg-green print-btn"
                                    data-ids="{{ implode(',', array_column($printerGroup['items'], 'id')) }}" data-href="">
                                    <i class="fa fa-print"></i> @lang('messages.print')
                              </button>
                              <button type="button" class="btn btn-flat bg-yellow serve-btn"
                                    data-ids="{{ implode(',', array_column($printerGroup['items'], 'id')) }}" data-href="">
                                    <i class="fa fa-check"></i> @lang('Mark As Served')
                              </button>
                        </div>
                  </td>
                  @endif
            </tr>
            @endforeach
            @endforeach
            @endforeach
      </tbody>
</table>
@endif