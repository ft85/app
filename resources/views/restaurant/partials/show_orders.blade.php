<style>
	.order-table {
		width: 100%;
		border-collapse: collapse;
	}

	.order-table th,
	.order-table td {
		padding: 10px;
		border: 1px solid #ddd;
	}

	.order-table th {
		background-color: #f2f2f2;
		font-weight: bold;
		text-align: left;
	}

	.table-column {
		background-color: #e6f7ff;
	}

	.btn {
		padding: 5px 10px;
		margin: 2px;
		border: none;
		border-radius: 3px;
		cursor: pointer;
	}

	.btn-sm {
		font-size: 12px;
	}

	.btn-warning {
		background-color: #ffc107;
		color: #000;
	}

	.btn-info {
		background-color: #17a2b8;
		color: #fff;
	}

	.btn-success {
		background-color: #28a745;
		color: #fff;
		/*  */
	}

	.btn-primary {
		background-color: #007bff;
		color: #fff;
	}

	.label {
		padding: 3px 6px;
		border-radius: 3px;
		font-size: 12px;
	}

	.bg-red {
		background-color: #dc3545;
		color: #fff;
	}

	.bg-green {
		background-color: #28a745;
		color: #fff;
	}

	.bg-orange {
		background-color: #fd7e14;
		color: #fff;
	}

	.bg-light-blue {
		background-color: #17a2b8;
		color: #fff;
	}
</style>

@if(empty($orders))
<div class="col-md-12">
	<h4 class="text-center">@lang('restaurant.no_orders_found')</h4>
</div>
@else
<table class="order-table">
	<thead>
		<tr>
			<th>Invoice No.</th>
			<th class="table-column">Table</th>
			<th>Staff</th>
			<th>Customer</th>
			<th>Location</th>
			<th>Total Amount</th>
			<th>Placed At</th>
			<th>Order Status</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		@forelse($orders as $order)
		<tr>
			<td>#{{$order->invoice_no}}</td>
			<td class="table-column">{{$order->table_name}}</td>
			<td>{{$order->service_staff_first_name}} {{$order->service_staff_last_name}} </td>
			<td>{{$order->customer_name}}</td>
			<td>{{$order->business_location}}</td>
			<td>{{ number_format($order->final_total, 0) }} BIF</td>
			<td>{{$order->created_at->format('d/m/Y')}} {{ $order->created_at->format('H:i')}}</td>
			<td>
				@php
				$count_sell_line = count($order->sell_lines);
				$count_cooked = count($order->sell_lines->where('res_line_order_status', 'cooked'));
				$count_served = count($order->sell_lines->where('res_line_order_status', 'served'));
				$order_status = 'received';
				if($count_cooked == $count_sell_line) {
				$order_status = 'cooked';
				} else if($count_served == $count_sell_line) {
				$order_status = 'served';
				} else if ($count_served > 0 && $count_served < $count_sell_line) {
					$order_status='partial_served' ;
					} else if ($count_cooked> 0 && $count_cooked < $count_sell_line) {
						$order_status='partial_cooked' ;
						}
						@endphp
						<span class="label @if($order_status == 'cooked') bg-red @elseif($order_status == 'served') bg-green @elseif($order_status == 'partial_cooked') bg-orange @else bg-light-blue @endif">
						@lang('restaurant.order_statuses.' . $order_status)
						</span>
			</td>
			<td>
				@if($orders_for == 'kitchen')
				<a href="#" class="btn btn-warning btn-sm mark_as_cooked_btn" data-href="{{action([\App\Http\Controllers\Restaurant\KitchenController::class, 'markAsCooked'], [$order->id])}}">
					<i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_cooked')
				</a>
				@elseif($orders_for == 'waiter' && $order->res_order_status != 'served')
				<a href="#" class="btn btn-warning btn-sm mark_as_served_btn" data-href="{{action([\App\Http\Controllers\Restaurant\OrderController::class, 'markAsServed'], [$order->id])}}">
					<i class="fa fa-check-square-o"></i> @lang('restaurant.mark_as_served')
				</a>
				@endif
				<a href="#" class="btn btn-info btn-sm btn-modal" data-href="{{ action([\App\Http\Controllers\SellController::class, 'show'], [$order->id])}}" data-container=".view_modal">
					@lang('restaurant.order_details') <i class="fa fa-arrow-circle-right"></i>
				</a>
				<a href="#" class="btn btn-success btn-sm print-invoice" data-id="{{$order->id}}" data-href="{{route('sell.printInvoice', [$order->id])}}?kitchen_order=true">
					<i class="fa fa-print"></i> @lang("messages.print")
				</a>
				<a href="{{action([\App\Http\Controllers\SellPosController::class, 'edit'], [$order->id])}}" class="btn btn-primary btn-sm" target="_blank">
					<i class="fas fa-edit"></i> @lang('messages.edit')
				</a>

				<!-- <a href="{{ action([\App\Http\Controllers\NotificationController::class, 'getTemplate'], [$order->id, 'new_sale']) }}" class="btn btn-primary btn-sm">
					<i class="fa fa-envelope" aria-hidden="true"></i> @lang('messages.new_sale_notification')
				</a> -->
				<a href="{{ action([\App\Http\Controllers\SellPosController::class, 'showInvoiceUrl'], [$order->id]) }}" class="btn btn-primary btn-sm view_invoice_url" target="_blank">
					<i class="fas fa-link"></i> @lang('Invoice URL')
				</a>

			</td>
		</tr>
		@empty
		<tr>
			<td colspan="7" class="text-center">@lang('restaurant.no_orders_found')</td>
		</tr>
		@endforelse
	</tbody>
</table>
@endif