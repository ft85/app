    {{-- resources/views/sale_pos/partials/order_lines_button.blade.php --}}

    @php
   // \Log::info("order_lines_button partial view rendered. showOrderLinesButton: " . (isset($showOrderLinesButton) ? ($showOrderLinesButton ? 'true' : 'false') : 'not set'));
    @endphp

    @if(isset($showOrderLinesButton) && $showOrderLinesButton)
    <button id="openOrdersModal" type="button"
        class="tw-font-bold tw-text-white tw-cursor-pointer tw-text-xs md:tw-text-sm tw-bg-blue-500 hover:tw-bg-blue-700 tw-p-2 tw-rounded-md tw-w-[12rem] tw-flex tw-items-center tw-justify-center tw-gap-1 no-print"
        title="Open Order Lines"
        aria-label="Open Order Lines">
        <i class="fas fa-list" aria-hidden="true"></i>
        <span>Order Lines</span>
    </button>
    @else
    <!-- Button not shown -->
    @endif