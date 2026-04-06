@if($tables_enabled || $waiters_enabled)
<div class="col-md-4">
    <div class="form-group">
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   id="table_waiter_display"
                   placeholder="Not selected"
                   readonly
                   style="background: white; cursor: pointer;"
                   title="Click to select table and waiter">

            {{-- Hidden fields used by existing POS JS --}}
            <input type="hidden" name="res_table_id" id="res_table_id"
                   value="{{ $view_data['res_table_id'] ?? '' }}">
            <input type="hidden" name="res_waiter_id" id="res_waiter_id"
                   value="{{ $view_data['res_waiter_id'] ?? '' }}">

            <div class="input-group-btn">
                <button type="button"
                        class="btn btn-info"
                        id="view_all_tables_btn"
                        title="View All Tables">
                    <i class="fa fa-th"></i> Tables
                </button>
            </div>
        </div>
        <small class="text-muted" id="table_hint">
            <i class="fa fa-info-circle"></i> Click to select table and waiter
        </small>
    </div>
</div>
@endif
