<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!-- <link rel="stylesheet" href="style.css"> -->
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Open+Sans&display=swap" rel="stylesheet">
        <title>Receipt-{{$receipt_details->invoice_no}}</title>
    </head>
    @if(!empty($receipt_details->rcptsign))
	<style type="text/css">
    /* Default styles for screen and fallback printing */
    body {
      color: #000;
      margin: 0;
      padding: 0;
    }
    .ticket {
      width: 100%;
      max-width: 100%;
      margin: auto;
    }
    .centered {
      text-align: center;
    }
    img {
      max-width: inherit;
      width: auto;
    }
    .f-8 {
      font-size: 8px !important;
    }
    .headings {
      font-weight: 700;
      text-transform: uppercase;
    }
    .sub-headings {
      font-weight: 700;
    }
    .flex-box {
      display: flex;
      width: 100%;
    }
    .flex-box p {
      width: 50%;
      margin-bottom: 0;
      white-space: nowrap;
    }
    .table-f-12 th,
    .table-f-12 td {
      font-size: 12px;
      word-break: break-word;
    }
    .bb-lg {
      border-bottom: 1px solid lightgray;
    }
    .border-bottom {
      border-bottom: 1px solid #242424;
    }
    .border-top {
      border-top: 1px solid #242424;
    }
    
    /* Print styles for auto adjusting between 58mm and 80mm printers */
    @media print {
      * {
        font-family: 'Arial', sans-serif;
        word-break: break-word;
      }
      
      @page {
        size: auto;
        margin: 0;
      }
      
      /* Default print base font size */
      * {
        font-size: 9px !important;
      }
      
      /* Auto-scale for 58mm printers */
      @media print and (max-width: 58mm) {
        .ticket {
          width: 58mm !important;
          max-width: 58mm !important;
        }
        .headings {
          font-size: 10px !important;
        }
        .sub-headings {
          font-size: 9px !important;
        }
        td, th {
          font-size: 8px !important;
        }
      }
      
      /* Auto-scale for 80mm printers */
      @media print and (min-width: 59mm) {
        .ticket {
          width: 80mm !important;
          max-width: 80mm !important;
        }
        .headings {
          font-size: 12px !important;
        }
        .sub-headings {
          font-size: 11px !important;
        }
        td, th {
          font-size: 10px !important;
        }
      }
      
      .centered {
        text-align: center;
      }
      
      img {
        max-width: 100%;
        height: auto;
      }
      
      .hidden-print,
      .hidden-print * {
        display: none !important;
      }
    }
  </style>
	<body>
        <div class="ticket">
			@if(empty($receipt_details->letter_head))
				@if(!empty($receipt_details->logo))
					<div class="text-box centered">
						<img style="max-height: 100px; width: auto;" src="{{$receipt_details->logo}}" alt="Logo">
						<h4>
							@if(!empty($receipt_details->invoice_no_prefix))
								<b>{!! $receipt_details->invoice_no_prefix !!}</b>
							@endif
							{{$receipt_details->invoice_no}}</h4>
					</div>
				@endif
				<div class="text-box centered">
					<h5>
						{{-- @if(!empty($receipt_details->invoice_no_prefix))
							<b>{!! $receipt_details->invoice_no_prefix !!}</b>
						@endif --}}
						Facture n˚ {{$receipt_details->invoice_no}} du {{$receipt_details->invoice_date}} </h5>
				</div>
                <table border="0" cellpadding="1" cellspacing="1" style="margin-top: 10px">
    <tr>
        <!-- Logo RRA à gauche -->
        {{-- <td class="left" valign="top"><img src="/img/obr.png" height="50" width="80"></td> --}}

        <!-- Informations de l'entreprise au centre -->
        <td class="left" valign="top" width="60%">
            <table border="0" cellpadding="1" cellspacing="1">
                <tr>
                    <td class="left">
						<strong>A. Identification du vendeur</strong>
                    </td>
                </tr>
                <tr>
                    <td class="left">
						<strong>Nom et prénom ou Raison sociale :</strong>
                        @if(!empty($receipt_details->display_name))
                        <span class="headings">
                            {{$receipt_details->display_name}}
                        </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="left">
						<strong style="font-weight:bold;">NIF :</strong>
                        @if(!empty($receipt_details->tax_info1))
                        {{ $receipt_details->tax_info1 }}
                        @endif
                    </td>
                </tr>
				<tr>
                    <td class="left">
						<strong>Registre de Commerce N°</strong>
                        {{ $receipt_details->registre_commerce }}
                    </td>
                </tr>
                @if(!empty($receipt_details->address))
                <tr>
                    <td class="left">
						<strong>Address :</strong> 
						{!! $receipt_details->address !!}
					</td>
                </tr>
                @endif
                @if(!empty($receipt_details->contact))
                <tr>
					<td class="left">
                        {!! $receipt_details->contact !!}
                    </td>
                </tr>
                @endif
				<tr>
					<td class="left">
						<strong>Centre fiscal :</strong> 
                        {{ $receipt_details->centre_fiscal }}
					</td>
				</tr>
				<tr>
					<td class="left">
						<strong>Secteur  d’activités :</strong>
                        {{ $receipt_details->secteur }} 
					</td>
				</tr>
				<tr>
					<td class="left">
						<strong>Forme juridique:</strong> 
                        {{ $receipt_details->forme }}
					</td>
				</tr>
				<tr>
					<td class="left">
					<strong>Assujetti à la TVA : </strong>
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}> 
						<label for="TVA">Oui</label>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}> 
						<label for="HTVA">Non</label>
					</td>
				</tr>
				<tr>
					<td class="left">
						<strong>Assujetti à la TC : </strong> 
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tc_payer == '1' ? 'checked' : '' }}> 
						<label for="TVA">Oui</label>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tc_payer != '1' ? 'checked' : '' }}> 
						<label for="HTVA">Non</label>
					</td>
				</tr>
				<tr>
					<td class="left">
						<strong>Assujetti à la PFA : </strong> 
						<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->pfa_payer == '1' ? 'checked' : '' }}> 
						<label for="TVA">Oui</label>
						<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->pfa_payer != '1' ? 'checked' : '' }}> 
						<label for="HTVA">Non</label>
					</td>
				</tr>
            </table>
        </td>
        <!-- Logo RRA à droite -->
        {{-- <td class="right" valign="top"><img src="/img/obr.png" height="60" width="60"></td> --}}
    </tr>
</table>
<table border = "0" cellpadding = "1" cellspacing = "1">
	<!-- customer info -->
	@if(!empty($receipt_details->customer_info))
	<tr>   
		<td class="left">
			<strong>B. Le client</strong>
		</td>	
	</tr>
	<tr>   
		<td class="left">
			<strong>Nom et prénom ou raison sociale :</strong>
			{{-- {{$receipt_details->customer_label ?? ''}}: --}}
			{!! $receipt_details->customer_name !!}
		</td>		
		{{-- <td class="left">
			{!! $receipt_details->customer_info !!}
		</td> --}}
	</tr>
	@endif
	{{-- @if(!empty($receipt_details->customer_tax_number)) --}}
	<tr>      
		<td class="left">
			<strong>NIF :</strong>
			{{ $receipt_details->customer_tax_number }}
		</td>
		{{-- <td class="left">{{ $receipt_details->customer_tax_number }}
		</td> --}}
	</tr>
	<tr>      
		<td class="left">
			<strong>Résident à :</strong>
			{{-- {{ $receipt_details->customer_info_address }} --}}
		</td>
	</tr>
	<tr>      
    <td class="left">
			<strong>Assujetti à la TVA : </strong>
			<input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->customer_vat == '1' ? 'checked' : '' }}> 
			<label for="TVA">Oui</label>
			<input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->customer_vat != '1' ? 'checked' : '' }}> 
			<label for="HTVA">Non</label>
		</td>
	</tr>
	{{-- @endif	 --}}
</table>

				<!-- <div class="text-box"> -->
				<!-- <p class="centered"> -->
					<!-- Header text -->
					<!-- @if(!empty($receipt_details->header_text))
						<span class="headings">{!! $receipt_details->header_text !!}</span>
						<br/>
					@endif -->

					<!-- business information here -->
					<!-- @if(!empty($receipt_details->display_name))
						<span class="headings">
							{{$receipt_details->display_name}}
						</span>
						<br/>
					@endif -->
					
					<!-- @if(!empty($receipt_details->address))
						{!! $receipt_details->address !!}
						<br/>
					@endif

					@if(!empty($receipt_details->contact))
						<br/>{!! $receipt_details->contact !!}
					@endif
					@if(!empty($receipt_details->contact) && !empty($receipt_details->website))
						, 
					@endif
					@if(!empty($receipt_details->website))
						{{ $receipt_details->website }}
					@endif
					@if(!empty($receipt_details->location_custom_fields))
						<br>{{ $receipt_details->location_custom_fields }}
					@endif

					@if(!empty($receipt_details->sub_heading_line1))
						{{ $receipt_details->sub_heading_line1 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line2))
						{{ $receipt_details->sub_heading_line2 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line3))
						{{ $receipt_details->sub_heading_line3 }}<br/>
					@endif
					@if(!empty($receipt_details->sub_heading_line4))
						{{ $receipt_details->sub_heading_line4 }}<br/>
					@endif		
					@if(!empty($receipt_details->sub_heading_line5))
						{{ $receipt_details->sub_heading_line5 }}<br/>
					@endif

					@if(!empty($receipt_details->tax_info1))
						<br><b>{{ $receipt_details->tax_label1 }}</b> {{ $receipt_details->tax_info1 }}
					@endif

					@if(!empty($receipt_details->tax_info2))
						<b>{{ $receipt_details->tax_label2 }}</b> {{ $receipt_details->tax_info2 }}
					@endif			
				</p> -->
				<!-- </div> -->
			<!-- @else
				<div class="text-box">
					<img style="width: 100%;margin-bottom: 10px;" src="{{$receipt_details->letter_head}}">
				</div>
			@endif
			<div class="border-top textbox-info">
				<p class="f-left"><strong>{!! $receipt_details->invoice_no_prefix !!}</strong></p>
				<p class="f-right">
					{{$receipt_details->invoice_no}}
				</p>
			</div>
			<div class="textbox-info">
				<p class="f-left"><strong>{!! $receipt_details->date_label !!}</strong></p>
				<p class="f-right">
					{{$receipt_details->invoice_date}}
				</p>
			</div> -->
			
			<!-- @if(!empty($receipt_details->due_date_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->due_date_label}}</strong></p>
					<p class="f-right">{{$receipt_details->due_date ?? ''}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->sales_person_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->sales_person_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->sales_person}}</p>
				</div>
			@endif
			@if(!empty($receipt_details->commission_agent_label))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->commission_agent_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->commission_agent}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->brand_label) || !empty($receipt_details->repair_brand))
				<div class="textbox-info">
					<p class="f-left"><strong>{{$receipt_details->brand_label}}</strong></p>
				
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
					<p class="f-left"><strong>{{$receipt_details->serial_no_label}}</strong></p>
				
					<p class="f-right">{{$receipt_details->repair_serial_no}}</p>
				</div>
			@endif

			@if(!empty($receipt_details->repair_status_label) || !empty($receipt_details->repair_status))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{!! $receipt_details->repair_status_label !!}
					</strong></p>
					<p class="f-right">
						{{$receipt_details->repair_status}}
					</p>
				</div>
        	@endif

        	@if(!empty($receipt_details->repair_warranty_label) || !empty($receipt_details->repair_warranty))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->repair_warranty_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->repair_warranty}}
	        		</p>
	        	</div>
        	@endif -->

        	<!-- Waiter info -->
			<!-- @if(!empty($receipt_details->service_staff_label) || !empty($receipt_details->service_staff))
	        	<div class="textbox-info">
	        		<p class="f-left"><strong>
	        			{!! $receipt_details->service_staff_label !!}
	        		</strong></p>
	        		<p class="f-right">
	        			{{$receipt_details->service_staff}}
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
	        			{{$receipt_details->table}}
	        		</p>
	        	</div>
	        @endif -->

	        <!-- customer info -->
	        <!-- <div class="textbox-info">
	        	<p style="vertical-align: top;"><strong>
	        		{{$receipt_details->customer_label ?? ''}}
	        	</strong></p>

	        	<p>
	        		@if(!empty($receipt_details->customer_info))
	        			<div class="bw">
						{!! $receipt_details->customer_info !!}
						</div>
					@endif
	        	</p>
	        </div>
			
			@if(!empty($receipt_details->client_id_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->client_id_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->client_id }}
					</p>
				</div>
			@endif
			
			@if(!empty($receipt_details->customer_tax_label))
				<div class="textbox-info">
					<p class="f-left"><strong>
						{{ $receipt_details->customer_tax_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->customer_tax_number }}
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
						{{ $receipt_details->customer_rp_label }}
					</strong></p>
					<p class="f-right">
						{{ $receipt_details->customer_total_rp }}
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
			@endif -->
			@php
			$totalTax = 0;
			@endphp	
			<div class="mt-15 mb-10"></div>
            <table style="padding-top: 5px !important" class="border-bottom width-100 table-f-12 mb-10">
                <tbody>
                	@forelse($receipt_details->lines as $line)
	                    <tr class="bb-lg">
							@php
							// Initialize total tax if not already initialized
							if (!isset($totalTax)) {
								$totalTax = 0;
							}
						
							// Remove commas from the tax and quantity values and convert to float
							$tax = (float) str_replace(',', '', $line['tax']);
							$quantity = (float) str_replace(',', '', $line['quantity']);
						
							// Multiply the tax by the quantity
							$lineTaxTotal = $tax * $quantity;
						
							// Add the line tax total to the total tax
							$totalTax += $lineTaxTotal;
						    @endphp
	                        <td class="description">
	                        	<div style="display:flex; width: 100%;">
	                        		<p class="m-0 mt-5" style="white-space: nowrap;">#{{$loop->iteration}}.&nbsp;</p>
	                        		<p class="text-left m-0 mt-5 pull-left">{{$line['name']}}  
			                        	<!-- @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
			                        	@if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
			                        	@if(!empty($line['product_description']))
			                        		<br>
			                            	<span class="f-8">
			                            		{!!$line['product_description']!!}
			                            	</span>
			                            @endif
			                        	@if(!empty($line['sell_line_note']))
			                        	<br>
	                        			<span class="f-8">
			                        	{!!$line['sell_line_note']!!}
			                        	</span>
			                        	@endif 
			                        	@if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
			                        	@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

			                        	@if(!empty($line['variation']))
			                        		,
			                        		{{$line['product_variation']}} {{$line['variation']}}
			                        	@endif
			                        	@if(!empty($line['warranty_name']))
			                            	, 
			                            	<small>
			                            		{{$line['warranty_name']}}
			                            	</small>
			                            @endif
			                            @if(!empty($line['warranty_exp_date']))
			                            	<small>
			                            		- {{@format_date($line['warranty_exp_date'])}}
			                            	</small>
			                            @endif
			                            @if(!empty($line['warranty_description']))
			                            	<small> {{$line['warranty_description'] ?? ''}}</small>
			                            @endif

			                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
				                            <br><small>
				                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br> {{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}} <br>
                            					{{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
				                            </small>
				                            @endif -->
	                        		</p>
	                        	</div>
	                        	<div style="display:flex; width: 100%;">
	                        		<p class="text-left width-60 quantity m-0 bw" style="direction: ltr;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                        			{{$line['quantity']}} 
	                        			@if(empty($receipt_details->hide_price))
	                        			x {{$line['unit_price_before_discount']}}
	                        			
	                        			@if(!empty($line['total_line_discount']) && $line['total_line_discount'] != 0)
	                        				- {{$line['total_line_discount']}}
	                        			@endif
	                        			@endif
	                        		</p>
	                        		@if(empty($receipt_details->hide_price))
	                        		<p class="text-right width-40 price m-0 bw">{{$line['line_total_exc_tax']}}</p>
	                        		@endif
	                        	</div>
	                        </td>
	                    </tr>
	                    @if(!empty($line['modifiers']))
							@foreach($line['modifiers'] as $modifier)
								<tr>
									<td>
										<div style="display:flex;">
	                        				<p style="width: 28px;" class="m-0">
	                        				</p>
	                        				<p class="text-left width-60 m-0" style="margin:0;">
	                        					{{$modifier['name']}} 
	                        					@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
			                            		@if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif
	                        				</p>
	                        				<p class="text-right width-40 m-0">
	                        					{{$modifier['variation']}}
	                        				</p>
	                        			</div>	
	                        			<div style="display:flex;">
	                        				<p style="width: 28px;"></p>
	                        				<p class="text-left width-50 quantity">
	                        					{{$modifier['quantity']}}
	                        					@if(empty($receipt_details->hide_price))
	                        					x {{$modifier['unit_price_inc_tax']}}
	                        					@endif
	                        				</p>
	                        				<p class="text-right width-50 price">
	                        					{{$modifier['line_total']}}
	                        				</p>
	                        			</div>		                             
			                        </td>
			                    </tr>
							@endforeach
						@endif
                    @endforeach
                </tbody>
            </table>
            @if(!empty($receipt_details->total_quantity_label))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->total_quantity_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_quantity}}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->total_items_label))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->total_items_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_items}}
					</p>
				</div>
			@endif
			@if(empty($receipt_details->hide_price))
            <div class="flex-box">
                <p class="left text-left">
                	<strong>
						{{-- {!! $receipt_details->subtotal_label !!} --}}
						Sous-total :
					</strong>
                </p>
                <p class="width-50 text-right">
                	<strong>{{$receipt_details->subtotal_exc_tax}}</strong>
                </p>
            </div>
            <div class="flex-box">
                <p class="left text-left text">
                	<strong>TVA :</strong>
                </p>
                <p class="width-50 text-right">
                	<strong>
					{{$receipt_details->tax}}
                </strong>
                </p>
            </div>

            <!-- Shipping Charges -->
			@if(!empty($receipt_details->shipping_charges))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->shipping_charges_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->shipping_charges}}
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->packing_charge))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->packing_charge_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->packing_charge}}
					</p>
				</div>
			@endif

			<!-- Discount -->
			@if( !empty($receipt_details->discount) )
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->discount_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->discount}}
					</p>
				</div>
			@endif
			
			@if( !empty($receipt_details->total_line_discount) )
				<div class="flex-box">
					<p class="width-50 text-right">
						{!! $receipt_details->line_discount_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->total_line_discount}}
					</p>
				</div>
			@endif

			@if( !empty($receipt_details->additional_expenses) )
				@foreach($receipt_details->additional_expenses as $key => $val)
					<div class="flex-box">
						<p class="width-50 text-right">
							{{$key}}:
						</p>

						<p class="width-50 text-right">
							(+) {{$val}}
						</p>
					</div>
				@endforeach
			@endif

			@if(!empty($receipt_details->reward_point_label) )
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->reward_point_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->reward_point_amount}}
					</p>
				</div>
			@endif

			@if( !empty($receipt_details->tax) )
				<div class="flex-box">
					<strong class=" width-50 text-left">
						{{-- {!! $receipt_details->tax_label !!} --}}
						TC:
					</strong>
					<strong class="width-50 text-right">
					{{ number_format($totalTax,2) }} BIF
					</strong>
				</div>
			@endif

			@if( $receipt_details->round_off_amount > 0)
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->round_off_label !!} 
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->round_off}}
					</p>
				</div>
			@endif

			<div class="flex-box">
				<p class="width-50 text-left">
					<strong>
						{{-- {!! $receipt_details->total_label !!} --}}
						Total payé :
					</strong>
				</p>
				<p class="width-50 text-right">
					<strong>{{$receipt_details->total}}</strong>
				</p>
			</div>
			@if(!empty($receipt_details->total_in_words))
				<p colspan="2" class="text-right mb-0">
					<small>
					({{$receipt_details->total_in_words}})
					</small>
				</p>
			@endif
			<!-- @if(!empty($receipt_details->payments))
				@foreach($receipt_details->payments as $payment)
					<div class="flex-box">
						<p class="width-50 text-left">{{$payment['method']}} ({{$payment['date']}}) </p>
						<p class="width-50 text-right">{{$payment['amount']}}</p>
					</div>
				@endforeach
			@endif -->
            <!-- Total Paid-->
				@if(!empty($receipt_details->total_paid))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->total_paid_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_paid}}
						</p>
					</div>
				@endif

				<!-- Total Due-->
				@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->total_due_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_due}}
						</p>
					</div>
				@endif

				@if(!empty($receipt_details->all_due))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->all_bal_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->all_due}}
						</p>
					</div>
				@endif
			@endif
            <div class="border-bottom width-100">&nbsp;</div>
            @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
	            <!-- tax -->
	            @if(!empty($receipt_details->taxes))
	            	<table class="border-bottom width-100 table-f-12">
	            		<tr>
	            			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
	            		</tr>
	            		@foreach($receipt_details->taxes as $key => $val)
	            			<tr>
	            				<td class="left">{{$key}}</td>
	            				<td class="right">{{$val}}</td>
	            			</tr>
	            		@endforeach
	            	</table>
	            @endif
            @endif

            @if(!empty($receipt_details->additional_notes))
	            <p class="centered" >
	            	{!! nl2br($receipt_details->additional_notes) !!}
	            </p>
            @endif

            {{-- Barcode --}}
			@if($receipt_details->show_barcode)
				<br/>
				<img class="center-block" src="data:image/png;base64,{{DNS1D::getBarcodePNG($receipt_details->invoice_no, 'C128', 2,30,array(39, 48, 54), true)}}">
			@endif

			@if($receipt_details->show_qr_code && !empty($receipt_details->qr_code_text))
				<img class="center-block mt-5" src="data:image/png;base64,{{DNS2D::getBarcodePNG($receipt_details->qr_code_text, 'QRCODE')}}">
			@endif

			<table border = "0" cellpadding = "1" cellspacing = "1">   
				<tr><td class="left"><strong>OBR ID</strong></td></tr>
				<td class="left">
					{!! $receipt_details->rcptsign !!}
				</td></tr>
			</table>
			<div class="py-2">
				@if(!empty($receipt_details->website))
					<h5>Site: {{ $receipt_details->website }}</h5>
				@endif
			</div>
			@if(!empty($receipt_details->footer_text))
				<p class="centered">
					{!! $receipt_details->footer_text !!}
				</p>
			@endif
			
			<table style="justify-content: end; margin-top: 30px; align-content: center;" border = "0" cellpadding = "1" cellspacing = "1"> 
				{{-- <tr><td><hr style="margin: 0; width: 100%;"></td></tr> --}}
				{{-- <tr class="title"><td class="injonge">INJONGE EBMS</td></tr> --}}
				<tr><td class="center">Powered by I-solutions</td></tr>
				{{-- <tr><TD class="center">Tel: +257 76 999 990</td></tr> --}}
			</table>
        </div>
        <!-- <button id="btnPrint" class="hidden-print">Print</button>
        <script src="script.js"></script> -->
    </body>
	@else
	<style type="text/css">
    /* Default styles for screen and fallback printing */
    body {
		font-family: 'Roboto', 'Open Sans', system-ui, -apple-system, sans-serif;
      color: #000;
      margin: 0;
      padding: 0;
    }
    .ticket {
      width: 100%;
      max-width: 100%;
      margin: auto;
    }
    .centered {
      text-align: center;
    }
    img {
      max-width: inherit;
      width: auto;
    }
    .f-8 {
      font-size: 8px !important;
    }
    .headings {
      font-weight: 700;
      text-transform: uppercase;
    }
    .sub-headings {
      font-weight: 700;
    }
    .flex-box {
      display: flex;
      width: 100%;
    }
    .flex-box p {
      width: 50%;
      margin-bottom: 0;
      white-space: nowrap;
    }
    .table-f-12 th,
    .table-f-12 td {
      font-size: 12px;
      word-break: break-word;
    }
    .bb-lg {
      border-bottom: 0.5px solid lightgray;
    }
    .border-bottom {
      border-bottom: 0.5px solid #242424;
    }
    .border-top {
      border-top: 0.5px solid #242424;
    }
    
    /* Print styles for auto adjusting between 58mm and 80mm printers */
    @media print {
      * {
        /* font-family: 'Arial', sans-serif; */
		font-family:'Open Sans', system-ui, -apple-system, sans-serif;
        word-break: break-word;
      }
      
      @page {
        size: auto;
        margin: 0;
      }
      
      /* Default print base font size */
      * {
        font-size: 9px !important;
      }
      
      /* Auto-scale for 58mm printers */
      @media print and (max-width: 58mm) {
        .ticket {
          width: 58mm !important;
          max-width: 58mm !important;
        }
        .headings {
          font-size: 10px !important;
        }
        .sub-headings {
          font-size: 9px !important;
        }
        td, th {
          font-size: 8px !important;
        }
      }
      
      /* Auto-scale for 80mm printers */
      @media print and (min-width: 59mm) {
        .ticket {
          width: 80mm !important;
          max-width: 80mm !important;
        }
        .headings {
          font-size: 12px !important;
        }
        .sub-headings {
          font-size: 11px !important;
        }
        td, th {
          font-size: 10px !important;
        }
      }
      
      .centered {
        text-align: center;
      }
      
      img {
        max-width: 100%;
        height: auto;
      }
      
      .hidden-print,
      .hidden-print * {
        display: none !important;
      }
    }
  </style>
	<body class="bg-gray-50">
	<div class="ticket">
    <div class="max-w-2xl mx-auto p-4 bg-white shadow-lg rounded-lg">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="text-center">
			@if(!empty($receipt_details->logo))
					<div class="text-box centered">
						<img style="max-height: 100px; width: auto;" src="{{$receipt_details->logo}}" alt="Logo">
					</div>
				@endif
                <span class="" style="font-size:18px !important;">
				<strong>{{ $receipt_details->nameBusiness }}</strong>
				</span>
				<br><span style="margin: 0 !important;">
				{!! $receipt_details->address !!}</span>
                <!-- <p class="text-gray-600">Monte Carlo, MC 98000</p> -->
				<br>
                <span class="text-gray-600"> {!! $receipt_details->contact !!}</span>
				<br>
				<span><strong>NIF:</strong> {{ $receipt_details->tax_info1 }}</span>
				<br>
				<span><strong>Bill Number: </strong></span><span class="font-normal">#{{$receipt_details->invoice_no}}</span>
            </div>
        </div>

        <!-- Bill Details -->
        <div class="grid grid-cols-2 gap-4 mb-8 bg-indigo-50 p-8 rounded-lg">
            <div>
             <span class="font-normal"><strong>Date:</strong> {{$receipt_details->invoice_date}}</span>
			 <br>
			 <span class="font-normal"> <strong>Guest Name:</strong> {!! $receipt_details->customer_name !!}</span>
			 <br>
			 @if(!empty($receipt_details->service_staff))
			 <span class="font-normal"><strong>Waiter:</strong> {{ $receipt_details->service_staff  }}</span>
			 @endif
            </div>
			<br>
        </div>

        <!-- Items Table -->
		@php
			$totalTax = 0;
			@endphp	
            <table style="padding-top: 2px !important" class="width-100 table-f-12">
                <tbody>
                	@forelse($receipt_details->lines as $line)
	                    <tr class=" border-bottom border-top">
							@php
							// Initialize total tax if not already initialized
							if (!isset($totalTax)) {
								$totalTax = 0;
							}
						
							// Remove commas from the tax and quantity values and convert to float
							$tax = (float) str_replace(',', '', $line['tax']);
							$quantity = (float) str_replace(',', '', $line['quantity']);
						
							// Multiply the tax by the quantity
							$lineTaxTotal = $tax * $quantity;
						
							// Add the line tax total to the total tax
							$totalTax += $lineTaxTotal;
						    @endphp
	                        <td class="description" style="font-size:  smaller;">
	                        	<div style="display:flex; width: 100%;">
	                        		<p class="m-0 mt-5" style="white-space: nowrap;">#{{$loop->iteration}}.&nbsp;</p>
	                        		<p class="text-left m-0 mt-5 pull-left w-50 break-words text-bold" >{{$line['name']}}  
			                        	<!-- @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
			                        	@if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
			                        	@if(!empty($line['product_description']))
			                        		<br>
			                            	<span class="f-8">
			                            		{!!$line['product_description']!!}
			                            	</span>
			                            @endif
			                        	@if(!empty($line['sell_line_note']))
			                        	<br>
	                        			<span class="f-8">
			                        	{!!$line['sell_line_note']!!}
			                        	</span>
			                        	@endif 
			                        	@if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
			                        	@if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

			                        	@if(!empty($line['variation']))
			                        		,
			                        		{{$line['product_variation']}} {{$line['variation']}}
			                        	@endif
			                        	@if(!empty($line['warranty_name']))
			                            	, 
			                            	<small>
			                            		{{$line['warranty_name']}}
			                            	</small>
			                            @endif
			                            @if(!empty($line['warranty_exp_date']))
			                            	<small>
			                            		- {{@format_date($line['warranty_exp_date'])}}
			                            	</small>
			                            @endif
			                            @if(!empty($line['warranty_description']))
			                            	<small> {{$line['warranty_description'] ?? ''}}</small>
			                            @endif

			                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
				                            <br><small>
				                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br> {{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}} <br>
                            					{{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
				                            </small>
				                            @endif -->
	                        		</p>
									<p class="text-left width-60 quantity m-0 bw" style="direction: ltr;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                        			{{$line['quantity']}} 
	                        			@if(empty($receipt_details->hide_price))
	                        			x {{$line['unit_price_before_discount']}}
	                        			
	                        			@if(!empty($line['total_line_discount']) && $line['total_line_discount'] != 0)
	                        				- {{$line['total_line_discount']}}
	                        			@endif
	                        			@endif
	                        		</p>
	                        		@if(empty($receipt_details->hide_price))
	                        		<p class="text-right width-40 price m-0 bw text-bold">{{$line['line_total_exc_tax']}}</p>
	                        		@endif
	                        	</div>
	                        	</div>
	                        </td>
	                    </tr>
	                    @if(!empty($line['modifiers']))
							@foreach($line['modifiers'] as $modifier)
								<tr>
									<td>
										<div style="display:flex;">
	                        				<p style="width: 28px;" class="m-0">
	                        				</p>
	                        				<p class="text-left width-60 m-0" style="margin:0;">
	                        					{{$modifier['name']}} 
	                        					@if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
			                            		@if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif
	                        				</p>
	                        				<p class="text-right width-40 m-0">
	                        					{{$modifier['variation']}}
	                        				</p>
	                        			</div>	
	                        			<div style="display:flex;">
	                        				<p style="width: 28px;"></p>
	                        				<p class="text-left width-50 quantity">
	                        					{{$modifier['quantity']}}
	                        					@if(empty($receipt_details->hide_price))
	                        					x {{$modifier['unit_price_inc_tax']}}
	                        					@endif
	                        				</p>
	                        				<p class="text-right width-50 price">
	                        					{{$modifier['line_total']}}
	                        				</p>
	                        			</div>		                             
			                        </td>
			                    </tr>
							@endforeach
						@endif
                    @endforeach
                </tbody>
            </table>
            @if(!empty($receipt_details->total_quantity_label))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->total_quantity_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_quantity}}
					</p>
				</div>
			@endif
			@if(!empty($receipt_details->total_items_label))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->total_items_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->total_items}}
					</p>
				</div>
			@endif
			@if(empty($receipt_details->hide_price))
            <div class="flex-box w-100 border border-bottom">
				<div class="py-10">
					</div>
					<span class="left width-100 text-left">
						<strong>
							{!! $receipt_details->subtotal_label !!} 
						</strong>
					</span>
					<span class="width-100 text-right">
						<strong>{{$receipt_details->subtotal_exc_tax}}</strong>
					</span>
					
            </div>
            <div class="flex-box width-100 border border-bottom">
                <span class="left width-100 text-left text">
                	<strong>VAT</strong></span>
                <span class="width-100 text-right">
                	<strong>
					{{$receipt_details->tax}}
                </strong>
                </span>
            </div>

            <!-- Shipping Charges -->
			@if(!empty($receipt_details->shipping_charges))
				<div class="flex-box border border-bottom">
					<p class="left text-left">
						{!! $receipt_details->shipping_charges_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->shipping_charges}}
					</p>
				</div>
			@endif

			@if(!empty($receipt_details->packing_charge))
				<div class="flex-box">
					<p class="left text-left">
						{!! $receipt_details->packing_charge_label !!}
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->packing_charge}}
					</p>
				</div>
			@endif

			<!-- Discount -->
			@if( !empty($receipt_details->discount) )
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->discount_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->discount}}
					</p>
				</div>
			@endif
			
			@if( !empty($receipt_details->total_line_discount) )
				<div class="flex-box">
					<p class="width-50 text-right">
						{!! $receipt_details->line_discount_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->total_line_discount}}
					</p>
				</div>
			@endif

			@if( !empty($receipt_details->additional_expenses) )
				@foreach($receipt_details->additional_expenses as $key => $val)
					<div class="flex-box">
						<p class="width-50 text-right">
							{{$key}}:
						</p>

						<p class="width-50 text-right">
							(+) {{$val}}
						</p>
					</div>
				@endforeach
			@endif

			@if(!empty($receipt_details->reward_point_label) )
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->reward_point_label !!}
					</p>

					<p class="width-50 text-right">
						(-) {{$receipt_details->reward_point_amount}}
					</p>
				</div>
			@endif

			@if( !empty($receipt_details->tax) )
				<div class="flex-box width-100 border border-bottom">
					<strong class=" width-100 text-left">
						{{-- {!! $receipt_details->tax_label !!} --}}
						TC:
					</strong>
					<strong class="width-100 text-right">
					{{ number_format($totalTax,2) }} BIF
					</strong>
				</div>
			@endif

			@if( $receipt_details->round_off_amount > 0)
				<div class="flex-box">
					<p class="width-50 text-left">
						{!! $receipt_details->round_off_label !!} 
					</p>
					<p class="width-50 text-right">
						{{$receipt_details->round_off}}
					</p>
				</div>
			@endif

			<div class="flex-box width-100 border border-bottom">
				<span class="width-100 text-left">
					<strong>
						{{-- {!! $receipt_details->total_label !!} --}}
						Total Paid :
					</strong>
				</span>
				<span class="width-100 text-right">
					<strong>{{$receipt_details->total}}</strong>
				</span>
			</div>
			@if(!empty($receipt_details->total_in_words))
				<p colspan="2" class="text-right mb-0">
					<small>
					({{$receipt_details->total_in_words}})
					</small>
				</p>
			@endif
			<!-- @if(!empty($receipt_details->payments))
				@foreach($receipt_details->payments as $payment)
					<div class="flex-box">
						<p class="width-50 text-left">{{$payment['method']}} ({{$payment['date']}}) </p>
						<p class="width-50 text-right">{{$payment['amount']}}</p>
					</div>
				@endforeach
			@endif -->
            <!-- Total Paid-->
				@if(!empty($receipt_details->total_paid))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->total_paid_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_paid}}
						</p>
					</div>
				@endif

				<!-- Total Due-->
				@if(!empty($receipt_details->total_due) && !empty($receipt_details->total_due_label))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->total_due_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->total_due}}
						</p>
					</div>
				@endif

				@if(!empty($receipt_details->all_due))
					<div class="flex-box">
						<p class="width-50 text-left">
							{!! $receipt_details->all_bal_label !!}
						</p>
						<p class="width-50 text-right">
							{{$receipt_details->all_due}}
						</p>
					</div>
				@endif
			@endif
            @if(empty($receipt_details->hide_price) && !empty($receipt_details->tax_summary_label) )
	            <!-- tax -->
	            @if(!empty($receipt_details->taxes))
	            	<table class="border-bottom width-100 table-f-12">
	            		<tr>
	            			<th colspan="2" class="text-center">{{$receipt_details->tax_summary_label}}</th>
	            		</tr>
	            		@foreach($receipt_details->taxes as $key => $val)
	            			<tr>
	            				<td class="left">{{$key}}</td>
	            				<td class="right">{{$val}}</td>
	            			</tr>
	            		@endforeach
	            	</table>
	            @endif
            @endif


        <!-- Signature Section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex justify-between space-x-8">
                <div class="w-1/3">
                    <p class="mb-2 text-gray-600">Name:..............................................................................................................</p>
                    <div class="h-12 border-b border-gray-300"></div>
                </div>
                <div class="w-1/3">
                    <p class="mb-2 text-gray-600">Room Number:..............................................................................................</p>
                    <div class="h-12 border-b border-gray-300"></div>
                </div>
                <div class="w-1/3">
                    <p class="mb-2 text-gray-600">Signature:.........................................................................................................</p>
                    <div class="h-12 border-b border-gray-300"></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-md text-gray-500">
            <p>Thank you for dining with us!</p>
            <p class="mt-2">Powered by <span class="text-indigo-600">i-Solutions</span></p>
        </div>
    </div>
	</div>
</body>
	@endif

</html>