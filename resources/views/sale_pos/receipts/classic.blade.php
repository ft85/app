<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Receipt-{{$receipt_details->invoice_no}}</title>
  </head>
  <body>
    <header class="clearfix">
		@if(!empty($receipt_details->logo))
      <div id="logo">
			<img src="{{$receipt_details->logo}}">
      {{-- Facture n˚ {{$receipt_details->invoice_no}} du {{$receipt_details->invoice_date}} --}}
		</div>
		@endif
    <!-- @if(!empty($receipt_details->logo))
					<div class="text-box centered">
						<img style="max-height: 100px; width: auto;" src="{{$receipt_details->logo}}" alt="Logo">
					</div>
				@endif -->
      <h1>
        @if(!empty($receipt_details->invoice_no_prefix))
            {{-- <b>{!! $receipt_details->invoice_no_prefix !!}</b> --}}
        @endif
      
        Facture n˚ {{$receipt_details->invoice_no}} du {{$receipt_details->invoice_date}}</h1>
      <div id="project">
        <div><strong>A. Identification du vendeur</strong></div>
        <div>Nom et prénom ou Raison sociale :
     
          {{$receipt_details->nameBusiness}}

        </div>
        <div>NIF : 
        @if(!empty($receipt_details->tax_info1))
        {{ $receipt_details->tax_info1 }}
        @endif
        </div>
        <div>Registre de Commerce N°{{ $receipt_details->registre_commerce }}</div>
        <div>Address :
        {!! $receipt_details->address !!}
        </div> 
        <div>Centre fiscal : {{ $receipt_details->centre_fiscal }}</div> 
        <div>Secteur  d’activités : {{ $receipt_details->secteur }} </div>
        <div>Forme juridique: {{ $receipt_details->forme }}</div> 
<div>Assujetti à la TVA :
    <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tva_payer == '1' ? 'checked' : '' }}> 
    <label for="TVA">Oui</label>
    <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tva_payer != '1' ? 'checked' : '' }}> 
    <label for="HTVA">Non</label>
</div>
<div> Assujetti à la TC :  
    <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->tc_payer == '1' ? 'checked' : '' }}> 
    <label for="TVA">Oui</label>
    <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->tc_payer != '1' ? 'checked' : '' }}> 
    <label for="HTVA">Non</label>
</div>
<div>Assujetti à la PFA :
    <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->pfa_payer == '1' ? 'checked' : '' }}> 
    <label for="TVA">Oui</label>
    <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->pfa_payer != '1' ? 'checked' : '' }}> 
    <label for="HTVA">Non</label>
</div>
	  </div>
      <div id="company" class="clearfix">
        <div><strong>B. Client</strong></div>
        <div>Nom et prénom ou raison sociale : {!! $receipt_details->customer_name !!}</div>
        <div>NIF:{{ $receipt_details->customer_tax_number }}</div>
        <div>Assujetti à la TVA :
        <input type="checkbox" id="TVA" name="TVA" {{ $receipt_details->customer_vat == '1' ? 'checked' : '' }}> 
        <label for="TVA">Oui</label>
        <input type="checkbox" id="HTVA" name="HTVA" {{ $receipt_details->customer_vat != '1' ? 'checked' : '' }}> 
        <label for="HTVA">Non</label>
        </div>
      </div>
    </header>
    <main class="row" style="margin-left: 1px; margin-right: 1px;">
      <table>
        <thead>
                <tr>
                    <th >#</th>

                    <th class="service">
                      
                        NATURE DE L'ARTICLE OU SERVICE
                    </th>                    
                    <th>
                      
                        Qté
                    </th>
					<th>
						
           PU
					</th>
                    <th>
                 
                        TVA
                    </th>
                    @if( !empty($receipt_details->tax) )
                      <th>
                       TC
                      </th>
                  @endif
                    <th>
                      PVT HTVA
                    </th>
                    <th>
                       PVT TVAC
                    </th>
                </tr>
            </thead>
        <tbody>
      @php
        $totalTax = 0;
      @endphp
        @foreach($receipt_details->lines as $line)
                    <tr>
                        <td>
                            {{$loop->iteration}}
                        </td>
                        <td>
                            <!-- @if(!empty($line['image']))
                                <img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
                            @endif -->
                            {{$line['name']}} 
                            <!-- {{$line['product_variation']}} {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif
                            @if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
                            @if(!empty($line['product_description']))
                                <small>
                                    {!!$line['product_description']!!}
                                </small>
                            @endif -->
                            @if(!empty($line['sell_line_note']))
                                <br>
                             <small class="text-muted">{!!$line['sell_line_note']!!}</small>
                             @endif
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif 

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif

                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                                1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
                                {{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
                            </small>
                            @endif
                        </td>

                        <td>
                            {{$line['quantity']}} {{$line['units']}}

                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                                {{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}
                            </small>
                            @endif
                        </td>
						<td >
							<!-- {{$line['unit_price_inc_tax']}} -->
              {{$line['unit_price_before_discount']}}

						</td>
                        <td>
                        @php
                          
                          $tax = isset($line['tax']) ? str_replace(',', '', $line['tax']) : '0';
                          $tax = is_numeric($tax) ? (float) $tax : 0;
                          
                           // Conversion and validation for quantity
                          $quantity = isset($line['quantity']) ? str_replace(',', '', $line['quantity']) : '0';
                          $quantity = is_numeric($quantity) ? (float) $quantity : 0;
                          
                          $lineTaxTotal = $tax * $quantity;
                          $totalTax += $lineTaxTotal;
                        @endphp
                        @if( !empty($receipt_details->tc_amount) )
                            {{ $receipt_details->tc_amount }}
                            @else
                            {{ number_format($lineTaxTotal,2) }}
                        @endif                          
                            
                          </td>
                          @if( !empty($receipt_details->tax) )
                          <td>
                          {{ number_format($lineTaxTotal,2) }}
                        </td>
                        @endif
                        <td>
                       {{ $line['line_total_exc_tax_uf']}}
							
                        </td>
                        <td class="total">
                            {{$line['line_total']}}
                        </td>
                    </tr>
                    @if(!empty($line['modifiers']))
                        @foreach($line['modifiers'] as $modifier)
                            <tr>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    {{$modifier['name']}} {{$modifier['variation']}} 
                                    @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif 
                                    @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif 
                                </td>

                                @if($receipt_details->show_cat_code == 1)
                                    <td>
                                        @if(!empty($modifier['cat_code']))
                                            {{$modifier['cat_code']}}
                                        @endif
                                    </td>
                                @endif

                                <td>
                                    {{$modifier['quantity']}} {{$modifier['units']}}
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                                <td>
                                    {{$modifier['unit_price_exc_tax']}}
                                </td>
                                <td class="total">
                                    {{$modifier['line_total']}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
              <table style="float:right; width:40%; padding:5px;">
						  @if(!empty($receipt_details->total_quantity_label))
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->total_quantity_label !!}
								  </td>
								  <td class="total">
									  {{$receipt_details->total_quantity}}
								  </td>
							  </tr>
						  @endif
		  
						  @if(!empty($receipt_details->total_items_label))
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->total_items_label !!}
								  </td>
								  <td class="total">
									  {{$receipt_details->total_items}}
								  </td>
							  </tr>
						  @endif
						  <tr >
							  <td colspan="6">
								  <!-- {!! $receipt_details->subtotal_label !!}
                 -->
							  PVT HTVA
                </td>
							  <td class="total">
								  {{$receipt_details->subtotal_exc_tax}}
                  
							  </td>
						  </tr>
						  
						  <!-- Shipping Charges -->
						  @if(!empty($receipt_details->shipping_charges))
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->shipping_charges_label !!}
								  </td>
								  <td class="total">
									  {{$receipt_details->shipping_charges}}
								  </td>
							  </tr>
						  @endif
		  
						  @if(!empty($receipt_details->packing_charge))
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->packing_charge_label !!}
								  </td>
								  <td class="total">
									  {{$receipt_details->packing_charge}}
								  </td>
							  </tr>
						  @endif
		  
						  <!-- Discount -->
						  @if( !empty($receipt_details->discount) )
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->discount_label !!}
								  </td>
		  
								  <td class="total">
									  (-) {{$receipt_details->discount}}
								  </td>
							  </tr>
						  @endif
		  
						  @if( !empty($receipt_details->total_line_discount) )
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->line_discount_label !!}
								  </td>
		  
								  <td class="total">
									  (-) {{$receipt_details->total_line_discount}}
								  </td>
							  </tr>
						  @endif
		  
						  @if( !empty($receipt_details->additional_expenses) )
							  @foreach($receipt_details->additional_expenses as $key => $val)
								  <tr >
									  <td colspan="6">
										  {{$key}}:
									  </td>
		  
									  <td class="total">
										  (+) {{$val}}
									  </td>
								  </tr>
							  @endforeach
						  @endif
		  
						  @if( !empty($receipt_details->reward_point_label) )
							  <tr >
								  <td colspan="6">
									  {!! $receipt_details->reward_point_label !!}
								  </td>
		  
								  <td class="total">
									  (-) {{$receipt_details->reward_point_amount}}
								  </td>
							  </tr>
						  @endif
              @if(!empty($receipt_details->tc_amount))       
              <tr >
                <td colspan="6" class="total">
                  <!-- {!! $receipt_details->subtotal_label !!}
                  -->
                  TVA
                </td>
							  <td class="total">
                  {{$receipt_details->tax}}    
							  </td>
                @else
                <td colspan="6" class="total">
                  <!-- {!! $receipt_details->subtotal_label !!}
                  -->
                  TVA
                </td>
							  <td class="total">
                {{ number_format($totalTax,2) }} BIF
							  </td>
						  </tr>
              @endif

						  @if(!empty($receipt_details->group_tax_details))
							  @foreach($receipt_details->group_tax_details as $key => $value)
								  <tr >
									  <td colspan="6">
										  {!! $key !!}
									  </td>
									  <td class="total">
										(+){{$value}}
									  </td>
								  </tr>
							  @endforeach
						  @else
							  @if( !empty($receipt_details->tc_amount) )
							  <tr >
								  <td colspan="6" class="total">
									  {{-- {!! $receipt_details->tax_label !!} --}}
                    TC
								  </td>
								  <td class="total">
                  {{ number_format($totalTax,2) }} BIF
								  </td>
							  </tr>
                @else
                {{ number_format($totalTax,2) }} BIF
							  @endif
							  @endif
		  
						  @if( $receipt_details->round_off_amount > 0)
							  <tr >
								  <td colspan="6" class="total">
									  {!! $receipt_details->round_off_label !!}
								  </td>
								  <td class="text-right">
									  {{$receipt_details->round_off}}
								  </td>
							  </tr>
						  @endif
						  
						  <!-- Total -->
						  <tr>
							  <td colspan="6" class="grand total">
								  <!-- {!! $receipt_details->total_label !!} -->
                  PVT TVAC
							  </td>
							  <td class="grand total">
								  {{$receipt_details->total}}
							  </td>
						  </tr>
						  @if(!empty($receipt_details->total_in_words))
						  <tr>
							  <td colspan="5">
								  <small>({{$receipt_details->total_in_words}})</small>
							  </td>
						  </tr>
						  @endif
              </table>
        </tbody>
       
      </table>
      @if(!empty($receipt_details->additional_notes))
      <div id="notices">
          <div class="notice">
          {!! nl2br($receipt_details->additional_notes) !!}
          </div>
      </div>
      @endif
    <div class="obr">
    OBR ID </br>
    {!! $receipt_details->rcptsign !!}
    </div>
    </main>
    @if(!empty($receipt_details->signature))
    <main class="row" style="margin:10px;">
    <div class="signature">
    Electronic signature </br>
    {!! $receipt_details->signature !!}
    </div>
    </main>
    @endif
    <footer>
      <!-- Powered By I-solutions -->
      @if(!empty($receipt_details->footer_text))
    {!! $receipt_details->footer_text !!}
    @endif
    </footer>
  </body>
</html>
<style>
.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  /* position: relative;
  width: 21cm;  
  height: 29.7cm;  */
  margin: 0 auto; 
  color: #001028;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  width: 100px;
}

h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: black;
  font-size: 1.5em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: right;
  text-align: left;
}

#project div,
#company div {
  white-space: nowrap;        
}

table {
  width: 100%;
  /* border-collapse: collapse; */
  border-spacing: 0;
  margin-bottom: 20px;
  border: 1px solid black;
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;

}

table th,
table td {
  text-align: left;
  border: 1px solid black;
}

table th {
  padding: 5px 20px;
  color: black;
  /* border-bottom: 1px solid #C1CED9; */
  white-space: nowrap;        
  font-weight: normal;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 10px;
  text-align: left;
}

table td.service,
table td.desc {
  vertical-align: top;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}

table td.grand {
  border-top: 1px solid #5D6975;;
}

#notices .notice {
  color: black;
  font-size: 1.2em;
}
.obr{
  border-bottom: 1px dashed;
  width: 50%;
}
.signature{
  justify-content: flex-end;
}

footer {
  color: black;
  width: 100%;
  height: 30px;
  position: relative;
  /* bottom: -30px; */
  /* border-top: 1px solid #C1CED9; */
  padding: 8px 0;
  text-align: center;
}

</style>