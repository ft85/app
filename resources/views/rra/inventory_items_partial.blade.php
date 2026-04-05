<option value="">Select Item...</option>
@foreach ($inventoryItems as $item)
    <option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach



