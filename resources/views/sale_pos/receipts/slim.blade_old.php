<!-- business information here -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="style.css"> -->
        <title>Receipt-{{$receipt_details->invoice_no ?? ''}}</title>
    </head>
    <body>

<div class="ticket">
				<div class="text-box centered">
				<table border="0"  width="100%" cellpadding = "1" cellspacing = "1">
					<tr>
						<!-- Logo RRA à gauche -->
						<td class="left" valign="top" >
							@if(!empty($rcptsign)) 
							<img src="/public/img/rra_logo.png" height="80" width="80">
							@endif
						</td>
						<!-- Informations de l'entreprise au centre -->
						<td class="center" valign="top" width="60%">
							<table border="0" width="100%" cellpadding = "1" cellspacing = "1">
								@if(!empty($receipt_details->display_name))
								<tr>
									<td class="center" style="font-weight:bold; ">
										{{$receipt_details->display_name ?? ''}} 		
									</td>
								</tr>
								@endif
								@if(!empty($receipt_details->tax_info1))
								<tr>
									<td class="center">
										TIN:{{ $receipt_details->tax_info1 ?? '' }}		
									</td>
								@endif
								</tr>
								@if(!empty($receipt_details->contact))
								<tr>
									<td class="center" style="font-size: smaller;">
										{!! $receipt_details->contact !!}
									</td>
								</tr>
								@endif
								@if(!empty($receipt_details->location_custom_fields))
								<tr>
									<td class="center">
										MoMo Code: {!! $receipt_details->location_custom_fields !!}
									</td>
								</tr>
								@endif	
							</table>
						</td>
						<!-- Logo RRA à droite -->
						<td class="right" valign="top">
							@if(!empty($rcptsign)) 
							<img src="/public/img/ebm_logo.png" height="80" width="80">
							@endif
						</td> 	
					</tr>
				</table>
				</div>

			<div class="text-box centered">
				<table border="0"  width="100%" cellpadding = "1" cellspacing = "1">
						<!-- Title of receipt -->
						@if(!empty($receipt_details->invoice_heading))
						<tr class="title">
							<TD class="center" style="font-weight:bold">
								{!! $receipt_details->invoice_no_prefix !!} {{$receipt_details->invoice_no ?? ''}} 
							</td>
						</tr>
						@endif
						<tr><td ><hr style="margin: 0; width: 100%;"></td></tr>
				</table>
			</div>

			
			@if(!empty($receipt_details->due_date_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->due_date_label}}</strong></p>
					<p class="f-right">{{$receipt_details->due_date ?? ''}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->sales_person_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->sales_person_label ?? ''}}</strong></p>
				
					<p class="f-right">{{$receipt_details->sales_person ?? ''}}</p>
				</div>
			@endif
			@if(!empty($receipt_details->commission_agent_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->commission_agent_label ?? ''}}</strong></p>
				
					<p class="f-right">{{$receipt_details->commission_agent}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->brand_label ?? ''}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_brand}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->device_label) || !empty($receipt_details->repair_device))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->device_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_device}}</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->model_no_label) || !empty($receipt_details->repair_model_no))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->model_no_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_model_no}}</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->serial_no_label) || !empty($receipt_details->repair_serial_no))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->serial_no_label ?? ''}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_serial_no}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!! $receipt_details->repair_status_label !!}
					</strong></p>
					<p class="f-right">
						{{$receipt_details->repair_status ?? ''}}
					</p>
				</div>
        	@endif

        	@if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->repair_warranty_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->repair_warranty ?? ''}}
	        		</p>
	        	</div>
        	@endif

        	<!-- Waiter info -->
			@if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->service_staff_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->service_staff ?? ''}}
					</p>
	        	</div>
	        @endif

	        @if(!empty($receipt_details->table_label) || !empty($receipt_details->table))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			@if(!empty($receipt_details->table_label))
							<b>{!! $receipt_details->table_label !!}</b>
						@endif
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->table ?? ''}}
	        		</p>
	        	</div>
	        @endif

	        <!-- customer info -->
			@if(!empty($receipt_details->customer_info))
				<div class="textbox-info">
					<p>	        		
							<div >
							{!! $receipt_details->customer_info !!}
							</div>					
					</p>
				</div>
			@endif
				
			@if(!empty($receipt_details->customer_tax_number))
				<div class="textbox-info">
					<p style="vertical-align: top;">
						<strong>{{ $receipt_details->customer_tax_label }}</strong>
					</p>
					<p>
						<div class="bw">
						<b>TIN:</b> {{ $receipt_details->customer_tax_number ?? ''}}
						</div>
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->customer_custom_fields))
				<div class="textbox-info">
					<p class="centered">
						{!! $receipt_details->customer_custom_fields !!}
					</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->customer_rp_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->customer_rp_label ?? '' }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->customer_total_rp ?? '' }}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_1_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_1_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_1_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_2_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_2_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_2_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_3_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_3_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_3_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_4_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_4_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_4_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->shipping_custom_field_5_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!!$receipt_details->shipping_custom_field_5_label!!} 
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->shipping_custom_field_5_value ?? ''!!}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->sale_orders_invoice_no))
				<div class="textbox-info">
					<p class="f-left"><strong>
						@lang('restaurant.order_no')
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->sale_orders_invoice_no ?? ''!!}
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->sale_orders_invoice_date))
				<div class="textbox-info">
					<p class="f-left"><strong>
						@lang('lang_v1.order_dates')
					</strong></p>
					<p class="f-right">
						{!!$receipt_details->sale_orders_invoice_date ?? ''!!}
					</p>
				</div>
			@endif
			<hr style="margin: 0; width: 100%;">
			<table style="padding-top: 5px !important" class="border-bottom width-100 table-f-12 mb-10">
				<tbody>
					@forelse($receipt_details->lines as $line)
					<tr class="bb-lg">
						<td class="description">
							<div style="display:flex; color: black; font-size: 14px !important;">
								<p class="m-0 mt-5" style="white-space: nowrap; margin-bottom: 0;">#{{$loop->iteration}}.&nbsp;</p>
								<p class="text-left m-0 mt-5 pull-left" style="margin-bottom: 0;">
									{{$line['name']}}
									@if(!empty($line['brand'])), {{$line['brand']}} @endif
									@if(!empty($line['product_description']))
									<br>
									<span class="f-8">
										{!! $line['product_description'] !!}
									</span>
									@endif
									@if(!empty($line['sell_line_note']))
									<br>
									<span class="f-8">
										{!! $line['sell_line_note'] !!}
									</span>
									@endif
									@if(!empty($line['lot_number']))<br>{{$line['lot_number_label']}}: {{$line['lot_number']}} @endif
									@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}: {{$line['product_expiry']}} @endif

									@if(!empty($line['variation']))
									, {{$line['product_variation']}} {{$line['variation']}}
									@endif
									@if(!empty($line['warranty_name']))
									, <small>{{$line['warranty_name']}}</small>
									@endif
									@if(!empty($line['warranty_exp_date']))
									<small> - {{ @format_date($line['warranty_exp_date']) }}</small>
									@endif
									@if(!empty($line['warranty_description']))
									<small>{{$line['warranty_description'] ?? ''}}</small>
									@endif

									@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
									<br><small>
										1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
										{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}} <br>
										{{$line['unit_price_inc_tax']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
									</small>
									@endif
								</p>
							</div><div style="display:flex; color: black; font-size: 14px !important;">
								<p class="text-left width-60 quantity m-0 bw" style="direction: ltr; margin-top: 0; margin-bottom: 0;">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$line['quantity']}}
									@if(empty($receipt_details->hide_price)) x {{$line['unit_price_inc_tax']}}
									@if(!empty($line['total_line_discount']) && $line['total_line_discount'] != 0)
									- {{$line['total_line_discount']}}
									@endif
									@endif
								</p>
								@if(empty($receipt_details->hide_price))
								<p class="text-right width-40 price m-0 bw" style="white-space: nowrap; margin-top: 0; margin-bottom: 0;">
									{{$line['line_total']}} ({{$line['tax_name']}})
								</p>
								@endif
							</div>
						</td>
					</tr>
					@if(!empty($line['modifiers']))
					@foreach($line['modifiers'] as $modifier)
					<tr>
						<td>
							<div style="display:flex;">
								<p style="width: 28px;" class="m-0"></p>
								<p class="text-left width-60 m-0" style="margin: 0;">
									{{$modifier['name']}}
									@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif
									@if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}} @endif
									@if(!empty($modifier['sell_line_note']))({!! $modifier['sell_line_note'] !!}) @endif
								</p>
								<p class="text-right width-40 m-0">{{$modifier['variation']}}</p>
							</div>
							<div style="display:flex;">
								<p style="width: 28px;"></p>
								<p class="text-left width-50 quantity">{{$modifier['quantity']}}
									@if(empty($receipt_details->hide_price)) x {{$modifier['unit_price_inc_tax']}} @endif
								</p>
								<p class="text-right width-50 price">{{$modifier['line_total']}} ({{$line['tax_name']}})</p>
							</div>
						</td>
					</tr>
					@endforeach
					@endif
					@empty
					<tr>
						<td colspan="4" class="text-center">Aucun élément trouvé.</td>
					</tr>
					@endforelse
				</tbody>
			</table>



		<!-- totals -->
          	<table border = "0" cellpadding = "1" cellspacing = "1" width="100%">
					<tr>
						<th style="width:20%">
							
						</th>
						<td class="left">
							{!! $receipt_details->subtotal_label !!}
						</td>
						<td class="right">
							{{$receipt_details->subtotal}}
						</td>
					</tr>

				<!-- Tax -->
				@if(!empty($receipt_details->taxes))
					@foreach($receipt_details->taxes as $k => $v)
						<tr >
							<th style="width:20%">							
							</th>
							<td class="left">{{$k}}</td>
							<td class="right">{{$v}}</td>
						</tr>
					@endforeach
				@endif
					@if(!empty($receipt_details->total_exempt_uf))
					<tr>
						<th style="width:20%">
							
						</th>
						<td class="left">
							@lang('lang_v1.exempt')
						</td>
						<td class="right">
							{{$receipt_details->total_exempt ?? ''}}
						</td>
					</tr>
					@endif
					<!-- Shipping Charges -->
					@if(!empty($receipt_details->shipping_charges))
						<tr>
							<th style="width:20%">
								
							</th>
							<td class="left">
								{!! $receipt_details->shipping_charges_label !!}
							</td>
							<td class="right">
								{{$receipt_details->shipping_charges ?? ''}}
							</td>
						</tr>
					@endif

					@if(!empty($receipt_details->packing_charge))
						<tr>
							<th style="width:20%">							
							</th>
							<td class="left">
								{!! $receipt_details->packing_charge_label !!}
							</td>
							<td class="right">
								{{$receipt_details->packing_charge ?? ''}}
							</td>
						</tr>
					@endif

					<!-- Discount -->
					@if( !empty($receipt_details->discount) )
						<tr>
							<th>								
							</th>
							<td class="left">
								{!! $receipt_details->discount_label !!}
							</td>
							<td class="right">
								(-) {{$receipt_details->discount ?? ''}} 
							</td>
						</tr>
					@endif

					@if( !empty($receipt_details->total_line_discount) )
						<tr>
							<th>								
							</th>
							<td class="left">
								{!! $receipt_details->line_discount_label !!}
							</td>
							<td class="right">
								(-) {{$receipt_details->total_line_discount ?? ''}}
							</td>
						</tr>
					@endif

					@if( !empty($receipt_details->additional_expenses) )
						@foreach($receipt_details->additional_expenses as $key => $val)
							<tr>
								<th>								
								</th>
								<td class="left">
									{{$key}}:
								</td>
								<td class="right">
									(+) {{$val}}
								</td>
							</tr>
						@endforeach
					@endif

					@if( !empty($receipt_details->reward_point_label) )
						<tr>
							<th>								
							</th>
							<td class="left">
								{!! $receipt_details->reward_point_label !!}
							</td>
							<td class="right">
								(-) {{$receipt_details->reward_point_amount}}
							</td>
						</tr>
					@endif

					<!-- Tax -->
					@if( !empty($receipt_details->tax) )
						<tr>
							<th>								
							</th>
							<td class="left">
								{!! $receipt_details->tax_label !!}
							</td>
							<td class="right">
								 {{$receipt_details->tax ?? ''}}
							</td>
						</tr>
					@endif

					@if( $receipt_details->round_off_amount > 0)
						<tr>
							<th>								
							</th>
							<td class="left">
								{!! $receipt_details->round_off_label !!}
							</td>
							<td class="right">
								{{$receipt_details->round_off ?? ''}}
							</td>
						</tr>
					@endif
						<tr>
							<th>								
							</th>
							<td colspan=2>
								<hr style="margin: 0; width: 100%;">
							</td>
						</tr>
					<!-- Total -->
					<tr>
							<th>								
							</th>
						<td class="left">
							<b>{!! $receipt_details->total_label !!}</b>
						</td>
						<td class="right">
							<b>
							{{$receipt_details->total}}
							@if(!empty($receipt_details->total_in_words))
								<br>
								<small>({{$receipt_details->total_in_words ?? ''}})</small>
							@endif
							</b>
						</td>
					</tr>

						<tr>
							<th>								
							</th>
							<td colspan=2>
								<hr style="margin: 0; width: 100%;">
							</td>
						</tr>	
					<!-- IPAYMENT METHOD -->
					
					
					@if(!empty($receipt_details->payments))
						@foreach($receipt_details->payments as $payment)
							<tr>
								<th>								
								</th>
								<td class="left">{{$payment['method']}}</td>
								<td class="right">{{$payment['amount']}}</td>
							</tr>
						@endforeach
					@endif
								
					<!-- ITEMS NUMBER -->
				@if(!empty($receipt_details->total_quantity_label))
					<tr>
						<th style="width:20%">								
						</th>
						<td class="left">
							{!! $receipt_details->total_quantity_label !!}
						</td>
						<td class="right">
							{{$receipt_details->total_quantity ?? ''}}
						</td>
						</tr>
					@endif

					@if(!empty($receipt_details->total_items_label))
						<tr>
							<th style="width:20%">							
							</th>
							<td class="left">
								{!! $receipt_details->total_items_label !!}
							</td>
							<td class="right">
								{{$receipt_details->total_items}}
							</td>
						</tr>
					@endif
        	</table>

		
@if(!empty($rcptsign)) 
		<table border="0"  width="100%" cellpadding = "1" cellspacing = "1">    
			<tr class="title"><TD colspan=2 class="center" style="font-weight:bold">SDC INFORMATION</td></tr>
			<tr><td colspan=2><hr style="margin: 0; width: 100%;"></td></tr>
			<tr>
				<td class="left" style="white-space: nowrap;">RECEIPT NUMBER:</td>
				<td class="right" style="white-space: nowrap;">{{ ($receipt_details->rcptNo ?? '') . '/' . ($receipt_details->rcptNo ?? '') . ' NS' ?? '' }}
</td>
			</tr> 
			<tr>
				<td class="left">SDC ID:</td>
				<td class="right" style="white-space: nowrap;">{{$receipt_details->sdc_id}}</td>
			</tr> 
			<tr>
				<TD colspan=2 class="center"><b>Internal Data:</b></td>
			</tr> 
<tr>             
    <td colspan="2" class="center" style="font-size: 12px;">
        @php
        $internal_data = $receipt_details->internal_data;
        $new_rcptsign = chunk_split($internal_data, 4, '-');
        $new_rcptsign = rtrim($new_rcptsign, '-');
        echo $new_rcptsign;
        @endphp
    </td>
</tr>
			<tr>
				<TD colspan=2 class="center"><b>Receipt Signature:</b></td>
			</tr> 
			<tr>   
				<td colspan="2" class="center" style="font-size: 12px;">
						@php
							$rcptsign = $receipt_details->rcptsign;
							$new_rcptsign = chunk_split($rcptsign, 4, '-');
							$new_rcptsign = rtrim($new_rcptsign, '-');
							echo $new_rcptsign;
						@endphp
				</td>
			</tr>		
			<tr>
				<td colspan="2" class="center">
					{{-- Barcode --}}
					@if($receipt_details->show_barcode)
						<br/>
						<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
					@endif

					@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
						<img class="center-block mt-1 small-qr" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE')}}">
					@endif
					
					@if(!empty($receipt_details->footer_text))
						<p class="centered">
							{!! $receipt_details->footer_text !!}
						</p>
					@endif
				</td>
			</tr> 
			<tr>
				<td colspan="2" class="center">
				<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG( "https://myrra.rra.gov.rw/common/link/ebm/receipt/indexEbmReceiptData?Data=".$receipt_details->tax_info1.$receipt_details->branch_id.$receipt_details->rcptsign, 'QRCODE', 3, 3, [39, 48, 54])}}">
				</td>
			</tr>   
			<tr>
				<td class="left" style="white-space: nowrap;">RECEIPT NUMBER:</td>
				<td class="right">{{ $receipt_details->rcptNo ?? '' }}
</td>
			</tr> 
			<tr>
				<td class="left">DATE:{{ ' '.$receipt_details->tdate ?? '' }}</td>
				<td class="right"style="white-space: nowrap;">TIME:{{ ' '.$receipt_details->ttime ?? ''}}</td>
			</tr> 
			<tr>
				<td class="left">MRC:</td>
				<td class="right">{{ $receipt_details->mrc ?? ''}}</td>
			</tr> 
			<tr>
				<td colspan=2><hr style="margin: 0; width: 100%;"></td>
			</tr> 										
			<tr>
				<td class="left">NOTE:</td>
				<td class="right">
				{!! nl2br($receipt_details->additional_notes) !!}
				</td>
			</tr>
			<tr>
				<td class="left">Served by:</td>
				<td class="right">{{ $receipt_details->sales_person ?? ''}}</td>
			</tr>
	</table>
 @endif
	<table border="0"  width="100%" cellpadding = "1" cellspacing = "1">
			<tr><td><hr style="margin: 0; width: 100%;"></td></tr>
			<tr class="title"><TD style="font-weight:bold">INJONGE EBM</td></tr>
			<tr><TD class="center">Powered by I-Solutions</td></tr>
			<tr><TD class="center">Tel: +257 76999990</td></tr>
	</table>

 </div>
        <!-- <button id="btnPrint" class="hidden-print">Print</button>
        <script src="script.js"></script> -->
</body>
</html>

<style type="text/css">

body {
	color: #000000;
}
@media print {
	* {
    	font-size: 12px;
    	font-family: 'Times New Roman';
    	word-break: break-all;
	}
	.f-8 {
		font-size: 8px !important; 
	}

.border-top{
    border-top: 1px solid #000000;
}
.border-bottom{
	border-bottom: 1px solid #000000;
}

.border-bottom-dotted{
	border-bottom: 1px dotted darkgray;
}

td.serial_number, th.serial_number{
	width: 5%;
    max-width: 5%;
}

td.description,
th.description {
    width: 35%;
    max-width: 35%;
}

td.quantity,
th.quantity {
    width: 15%;
    max-width: 15%;
    word-break: break-all;
}
td.unit_price, th.unit_price{
	width: 25%;
    max-width: 25%;
    word-break: break-all;
}

td.price,
th.price {
    width: 20%;
    max-width: 20%;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

img {
    max-width: inherit;
    width: auto;
}

    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
.table-info {
	width: 100%;
}
.table-info tr:first-child td, .table-info tr:first-child th {
	padding-top: 8px;
}
.table-info th {
	text-align: left;
}
.table-info td {
	text-align: right;
}
.logo {
	float: left;
	width:35%;
	padding: 10px;
}

.text-with-image {
	float: left;
	width:65%;
}
.text-box {
	width: 100%;
	height: auto;
}
.m-0 {
	margin:0;
}
.textbox-info {
	clear: both;
}
.textbox-info p {
	margin-bottom: 0px
}
.flex-box {
	display: flex;
	width: 100%;
}
.flex-box p {
	width: 50%;
	margin-bottom: 0px;
	white-space: nowrap;
}

.table-f-12 th, .table-f-12 td {
	font-size: 12px;
	word-break: break-word;
}

.bw {
	word-break: break-word;
}
.bb-lg {
	border-bottom: 1px solid lightgray;
}
		td.injonge {
			text-align: center; !important;
			font-weight: bold; !important;
			font-size: 16px; !important;
			color: #FFFFFF; !important;
			}
		th.right,
		td.right {
			text-align: right !important;
			vertical-align: top !important; /* Ajout de l'alignement vertical */
		}
		th.left,
		td.left {
			text-align: left !important;
			vertical-align: top !important; /* Ajout de l'alignement vertical */
		}
		td.center {
			text-align: center !important;
			vertical-align: top !important; /* Ajout de l'alignement vertical */
		}
		.title {
			background-color: #b0b0b0 !important;
		}
        div.ticket { 
			margin: auto; 
			padding: 5px 5px; !important;
			background-color: #fff; 
			text-align: center;
			text-justify: inter-word;
			font-family: Arial;
			font-weight: normal;
			font-size: 14px;
			}
    .small-qr {
        width: 50px;
        height: 50px;
    }

</style>