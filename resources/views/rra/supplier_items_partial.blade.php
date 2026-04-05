<option value="">Select Supplier...</option>
@foreach ($customer_groups as $contact)
    <option value="{{ $contact->id }}">{{ $contact->supplier_business_name }}</option>
@endforeach



