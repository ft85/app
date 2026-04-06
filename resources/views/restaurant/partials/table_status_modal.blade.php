{{-- Table Dashboard Modal - Exact Design Match --}}

<div class="modal fade" id="table_status_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document" style="max-width: 1200px;">
        <div class="modal-content" style="border: none; border-radius: 12px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); background: #F9FAFB;">
            
            {{-- Header --}}
            <div class="modal-header" style="padding: 24px; border-bottom: 1px solid #E5E7EB; background: #FFFFFF; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center;">
                {{-- Left: Waiter Selection --}}
                <div style="flex: 1; display: flex; justify-content: flex-start;">
                    <div style="position: relative;">
                        <select class="form-control" id="modal_waiter_select" required style="appearance: none; background: #F9FAFB; border: 1px solid #E5E7EB; color: #374151; border-radius: 8px; padding: 8px 32px 8px 12px; font-size: 16px; height: auto; min-width: 150px;">
                            <option value="">-- Select Waiter --</option>
                            @php
                                $business_id = session('user.business_id');
                                $service_staff_roles = \Spatie\Permission\Models\Role::where('business_id', $business_id)
                                    ->where('is_service_staff', 1)
                                    ->pluck('name')
                                    ->toArray();
                                
                                $waiters = [];
                                if (!empty($service_staff_roles)) {
                                    $waiters = \App\User::where('business_id', $business_id)
                                        ->role($service_staff_roles)
                                        ->select('id', 'first_name', 'last_name', 'is_enable_service_staff_pin', 'service_staff_pin')
                                        ->get();
                                }
                            @endphp
                            @foreach($waiters as $waiter)
                                <option value="{{ $waiter->id }}" 
                                        data-is-enable-pin="{{ $waiter->is_enable_service_staff_pin ? '1' : '0' }}"
                                        data-pin="{{ $waiter->service_staff_pin ?? '' }}">
                                    {{ $waiter->first_name }} {{ $waiter->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fa fa-chevron-down" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: #6B7280; pointer-events: none; font-size: 14px;"></i>
                    </div>
                </div>
                
                {{-- Center: Filter Buttons --}}
                <div style="flex: 1; display: flex; justify-content: center;">
                    <div style="display: flex; align-items: center; background: #F9FAFB; padding: 4px; border-radius: 8px; border: 1px solid #E5E7EB;">
                        <button type="button" class="filter-btn active" data-filter="all" style="padding: 6px 16px; font-size: 14px; font-weight: 500; border-radius: 6px; background: #059669; color: white; border: none; margin: 0 2px;">All</button>
                        <button type="button" class="filter-btn" data-filter="available" style="padding: 6px 16px; font-size: 14px; font-weight: 500; border-radius: 6px; color: #6B7280; background: transparent; border: none; margin: 0 2px; transition: all 0.2s;">Available</button>
                        <button type="button" class="filter-btn" data-filter="occupied" style="padding: 6px 16px; font-size: 14px; font-weight: 500; border-radius: 6px; color: #6B7280; background: transparent; border: none; margin: 0 2px; transition: all 0.2s;">Occupied</button>
                    </div>
                </div>
                
                {{-- Right: Close Button --}}
                <div style="flex: 1; display: flex; justify-content: flex-end;">
                    <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close" style="padding: 8px; border-radius: 50%; background: transparent; border: none; color: #6B7280; transition: all 0.2s;" onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">
                        <i class="fa fa-times" style="font-size: 18px;"></i>
                    </button>
                </div>
            </div>
            
            {{-- PIN Input Field (conditional) - Below Header --}}
            <div id="pin_input_row" style="display: none; padding: 16px 24px; background: #FFFFFF; border-bottom: 1px solid #E5E7EB; text-align: center;">
                <div style="max-width: 500px; margin: 0 auto;">
                    {{-- Compact Header --}}
                    <h4 style="font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-lock" style="font-size: 18px; color: #059669; margin-right: 6px;"></i>
                        Enter PIN for &nbsp;<span id="pin_waiter_name"></span>
                    </h4>
                    <p style="margin-bottom: 12px; font-size: 12px; color: #6B7280; text-align: center;">
                        This waiter requires PIN verification to access their tables.
                    </p>
                    
                    {{-- Compact Input with Buttons --}}
                    <div style="position: relative; max-width: 400px; margin: 0 auto;">
                        <div style="position: relative; border-radius: 6px; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">
                            {{-- Key Icon --}}
                            <i class="fa fa-key" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; z-index: 2;"></i>
                            
                            {{-- Input Field --}}
                            <input type="password" 
                                   id="waiter_pin_input" 
                                   class="form-control" 
                                   placeholder="Enter PIN here"
                                   style="padding-left: 32px; padding-right: 120px; height: 36px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; background: #ffffff; color: #374151; box-shadow: none; transition: all 0.2s ease; text-align: center;">
                            
                            {{-- Button Group --}}
                            <div style="position: absolute; right: 3px; top: 50%; transform: translateY(-50%); display: flex; gap: 3px;">
                                <button type="button" 
                                        id="verify_pin_btn" 
                                        style="padding: 6px 12px; font-size: 12px; font-weight: 500; color: white; background: #059669; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"
                                        onmouseover="this.style.background='#047857'" 
                                        onmouseout="this.style.background='#059669'">
                                    Verify
                                </button>
                                <button type="button" 
                                        id="cancel_pin_btn" 
                                        style="padding: 6px 12px; font-size: 12px; font-weight: 500; color: #6b7280; background: #f3f4f6; border: none; border-radius: 4px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"
                                        onmouseover="this.style.background='#e5e7eb'; this.style.color='#374151'" 
                                        onmouseout="this.style.background='#f3f4f6'; this.style.color='#6b7280'">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Body --}}
            <div class="modal-body" style="padding: 0; background: #F9FAFB;">

                {{-- Main Content Area --}}
                <div style="padding: 24px; min-height: 400px; overflow-y: auto; flex-grow: 1;">
                    <div id="tables_grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px; width: 100%;">
                        {{-- Tables will be loaded here via AJAX --}}
                    </div>
                </div>

                {{-- Footer --}}
                <div style="padding: 24px; border-top: 1px solid #E5E7EB; background: #FFFFFF; border-radius: 0 0 12px 12px; flex-shrink: 0;">
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        {{-- Summary Statistics --}}
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                            <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; text-align: center;">
                                <p style="font-size: 14px; font-weight: 500; color: #6B7280; margin: 0 0 8px 0; text-transform: uppercase;">TOTAL TABLES</p>
                                <p id="total_tables" style="font-size: 32px; font-weight: 700; color: #374151; margin: 0;">0</p>
                            </div>
                            <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; text-align: center;">
                                <p style="font-size: 14px; font-weight: 500; color: #6B7280; margin: 0 0 8px 0; text-transform: uppercase;">AVAILABLE</p>
                                <p id="available_count" style="font-size: 32px; font-weight: 700; color: #10B981; margin: 0;">0</p>
                            </div>
                            <div style="background: #F9FAFB; border-radius: 12px; padding: 16px; text-align: center;">
                                <p style="font-size: 14px; font-weight: 500; color: #6B7280; margin: 0 0 8px 0; text-transform: uppercase;">OCCUPIED</p>
                                <p id="occupied_count" style="font-size: 32px; font-weight: 700; color: #F59E0B; margin: 0;">0</p>
                            </div>
                        </div>
                        
                        {{-- Selected Waiter Display --}}
                        <div style="text-align: center;">
                            <p id="selected_waiter_display" style="font-size: 14px; color: #6B7280; margin: 0;">
                                Selected: <span style="font-weight: 500; color: #374151;">None</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="modal-footer" style="background: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; padding: 16px 24px;">
                <div class="row w-100 align-items-center">
                    <div class="col-md-12">
                        <strong id="selected_waiter_display" style="color: #374151; font-size: 14px; font-weight: 500;"></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Transfert Simple --}}
<div class="modal fade" id="transfer_table_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background: #059669; color: white; border-radius: 12px 12px 0 0;">
                <h4 class="modal-title" style="margin: 0;">
                    <i class="fa fa-exchange"></i> Transfer Table <span id="transfer_table_name"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p style="margin-bottom: 15px; color: #6B7280;">
                    Transfer to:
                </p>
                <div class="form-group">
                    <select id="transfer_to_waiter" class="form-control" style="border-radius: 6px;">
                        <option value="">-- Select Service Staff --</option>
                    </select>
                </div>
                <p style="margin-top: 15px; font-size: 12px; color: #9CA3AF; text-align: center;">
                    Your PIN will be verified automatically
                </p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E5E7EB; padding: 15px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px;">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm_transfer_btn" style="background: #059669; border: none; border-radius: 6px;">
                    <i class="fa fa-check"></i> Transfer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Segmented Control Styles - Matching the Image Design */
.filter-segmented-control {
    display: inline-flex;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.filter-segmented-control .filter-btn {
    border: none;
    background: #f9fafb;
    color: #374151;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}

.filter-segmented-control .filter-btn:first-child {
    border-radius: 8px 0 0 8px;
}

.filter-segmented-control .filter-btn:last-child {
    border-radius: 0 8px 8px 0;
}

.filter-segmented-control .filter-btn.active {
    background: #10b981;
    color: white;
}

.filter-segmented-control .filter-btn:hover:not(.active) {
    background: #e5e7eb;
}

/* Close Button Styles */
.modal-close-btn {
    background: none;
    border: none;
    font-size: 20px;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.modal-close-btn:hover {
    color: #374151;
    background: #f3f4f6;
}

/* Table Card Styles - Matching the Image Design */
.table-card {
    background: white;
    border-radius: 8px;
    padding: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    width: fit-content;
    min-width: 100px;
    text-align: center;
}

/* Bouton Transfer */
.btn-transfer-table {
    transition: all 0.2s ease;
}

.btn-transfer-table:hover {
    background: #047857 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.btn-transfer-table:active {
    transform: translateY(0);
}

.table-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}


.table-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.table-card.disabled:hover {
    transform: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.table-card.restricted {
    opacity: 0.6;
    cursor: not-allowed;
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
}

.table-card.restricted:hover {
    transform: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.table-card.restricted .table-status {
    color: #6b7280;
}

.table-card.restricted .status-dot {
    background: #9ca3af;
}

/* Available table placeholder styling */
.table-card.available .table-details .detail-value {
    color: #10b981;
    font-weight: 500;
}

.table-card.available .detail-label {
    color: #6b7280;
}

/* Make occupied tables bigger and wider */
.table-card.occupied {
    min-height: 120px !important;
    padding: 6px !important;
    width: 100% !important;
    min-width: 140px !important;
}

.table-card.available {
    min-height: 120px !important;
    padding: 6px !important;
    width: 100% !important;
    min-width: 140px !important;
}

/* Improve detail text styling */
.table-details .detail-value {
    font-size: 12px;
    line-height: 1.2;
    text-align: center;
    display: block;
    margin-bottom: 2px;
}

.table-details {
    margin-top: 4px;
    text-align: center;
}

/* Force all text to be centered */
.table-card {
    text-align: center !important;
}

.table-card * {
    text-align: center !important;
}

.table-card .table-header {
    text-align: center !important;
}

.table-card .table-status {
    text-align: center !important;
}

.table-card .table-name {
    text-align: center !important;
}

.table-card .table-details {
    text-align: center !important;
}

.table-card .detail-row {
    text-align: center !important;
}

.table-card .detail-value {
    text-align: center !important;
    display: block !important;
}

.table-card h3 {
    text-align: center !important;
}

.table-card span {
    text-align: center !important;
}

.table-card.occupied .detail-value {
    color: #374151;
    font-weight: 500;
}

.table-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 4px;
    text-align: center;
}

.table-name {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.table-status {
    display: flex;
    align-items: center;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 12px;
    margin-bottom: 6px;
    justify-content: center;
}

.table-status.available {
    background: #dcfce7;
    color: #166534;
}

.table-status.occupied {
    background: #fed7aa;
    color: #9a3412;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    margin-right: 6px;
}

.status-dot.available {
    background: #22c55e;
}

.status-dot.occupied {
    background: #f97316;
}

.table-capacity {
    text-align: center;
    margin: 16px 0;
}

.capacity-number {
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin: 0;
}


.table-details {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    font-size: 12px;
}

.detail-label {
    color: #6b7280;
    font-weight: 500;
}

.detail-value {
    color: #1f2937;
    font-weight: 600;
}

/* Filter Button Styles */
.filter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filter-btn.active {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    #tables_grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .table-card {
        padding: 12px;
    }
    
    .capacity-number {
        font-size: 24px;
    }
}
</style>

<script>
$(document).ready(function() {
    let selectedWaiterId = null;
    let selectedWaiterName = '';
    let autoRefreshInterval = null;

    // Initialize modal
    $('#table_status_modal').on('show.bs.modal', function() {
        showWaiterSelectionRequired();
    });

    // Waiter selection change
    $('#modal_waiter_select').change(function() {
        selectedWaiterId = $(this).val();
        selectedWaiterName = $(this).find('option:selected').text();
        const waiterPin = $(this).find('option:selected').data('pin');
        const hasPin = waiterPin && String(waiterPin).trim() !== '';
        
        // Hide PIN input initially
        $('#pin_input_row').hide();
        $('#waiter_pin_input').val('');
        
        if (selectedWaiterId) {
            if (hasPin) {
                // Show PIN input for waiters that have a PIN set
                $('#pin_waiter_name').text(selectedWaiterName);
                $('#pin_input_row').show();
                $('#waiter_pin_input').focus();
                showWaiterSelectionRequired();
                stopAutoRefresh();
            } else {
                // No PIN required, load tables directly
                $('#selected_waiter_display').text(`Selected: ${selectedWaiterName}`);
                loadTableStatus();
                startAutoRefresh();
            }
        } else {
            showWaiterSelectionRequired();
            stopAutoRefresh();
        }
    });

    // PIN verification handlers
    $('#verify_pin_btn').click(function() {
        verifyWaiterPin();
    });
    
    $('#waiter_pin_input').keypress(function(e) {
        if (e.which === 13) { // Enter key
            verifyWaiterPin();
        }
    });
    
    $('#cancel_pin_btn').click(function() {
        // Reset waiter selection
        $('#modal_waiter_select').val('');
        $('#pin_input_row').hide();
        $('#waiter_pin_input').val('');
        showWaiterSelectionRequired();
        stopAutoRefresh();
    });
    
    function verifyWaiterPin() {
        const pin = $('#waiter_pin_input').val();
        if (!pin) {
            toastr.error('Please enter a PIN');
            $('#waiter_pin_input').focus();
            return;
        }
        
        // Show loading state
        $('#verify_pin_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Verifying...');
        
        $.ajax({
            method: 'GET',
            url: '/modules/data/check-staff-pin',
            dataType: 'json',
            data: {
                service_staff_pin: pin,
                user_id: selectedWaiterId,
            },
            success: function(result) {
                if (result === true || result === 'true') {
                    // PIN verification successful
                    toastr.success('PIN verification successful');
                    $('#selected_waiter_display').text(`Selected: ${selectedWaiterName} (Verified)`);
                    $('#pin_input_row').hide();
                    loadTableStatus();
                    startAutoRefresh();
                } else {
                    // PIN verification failed
                    toastr.error('Invalid PIN. Please try again.');
                    $('#waiter_pin_input').val('').focus();
                }
            },
            error: function() {
                toastr.error('PIN verification failed. Please try again.');
                $('#waiter_pin_input').val('').focus();
            },
            complete: function() {
                // Reset button state
                $('#verify_pin_btn').prop('disabled', false).html('<i class="fa fa-check"></i> Verify');
            }
        });
    }

    // Filter button clicks
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        // Update button styles
        $('.filter-btn').css({
            'background': 'transparent',
            'color': '#6B7280'
        });
        $(this).css({
            'background': '#059669',
            'color': 'white'
        });
        
        const filter = $(this).data('filter');
        filterTables(filter);
    });

    // Table card clicks
    $(document).on('click', '.table-card:not(.disabled):not(.restricted)', function() {
        const tableId = $(this).data('table-id');
        const tableName = $(this).data('table-name');
        const status = $(this).data('status');
        const transactionId = $(this).data('transaction-id');
        const assignedWaiterId = $(this).data('assigned-waiter-id');
        
        if (!selectedWaiterId) {
            toastr.error('Please select a waiter first');
            return;
        }
        
        // Check if table is occupied by another waiter
        if (status === 'occupied' && assignedWaiterId && assignedWaiterId != selectedWaiterId) {
            toastr.error(`Table ${tableName} is occupied by another waiter. Only the assigned waiter can resume this table.`);
            return;
        }
        
        selectTable(tableId, tableName, status, transactionId);
    });

    // Transfer button clicks
    $(document).on('click', '.btn-transfer-table', function(e) {
        e.stopPropagation();
        const tableId = $(this).data('table-id');
        const tableName = $(this).data('table-name');
        const transactionId = $(this).data('transaction-id');
        openTransferModal(tableId, tableName, transactionId);
    });


    // Load table status from server
    function loadTableStatus() {
        if (!selectedWaiterId) return;
        
        let location_id = $('#location_id').val();
        
        $.ajax({
            url: '/modules/restaurant/tables/status',
            method: 'GET',
            data: {
                location_id: location_id,
                selected_waiter_id: selectedWaiterId,
                _t: Date.now() // Force refresh to bypass cache
            },
            success: function(response) {
                if (response.success) {
                    renderTables(response.tables);
                    updateSummary(response.summary);
                } else {
                    showError(response.message || 'Failed to load tables');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading tables:', error);
                showError('Failed to load tables. Please try again.');
            }
        });
    }

    // Render tables in grid
    function renderTables(tables) {
        let html = '';
        
        if (tables.length === 0) {
            html = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #6B7280;">
                    <i class="fa fa-table" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                    <h4 style="margin-bottom: 8px; color: #374151; font-size: 18px; font-weight: 600;">No tables found</h4>
                    <p style="color: #6B7280;">No tables are available for this location.</p>
                </div>
            `;
        } else {
            tables.forEach(function(table) {
                const statusClass = table.status;
                const statusText = table.status === 'available' ? 'Available' : 'Occupied';
                const statusColor = table.status === 'available' ? '#10B981' : '#F59E0B';
                
                // Check if table is occupied by another waiter
                const isRestricted = table.status === 'occupied' && 
                                   table.assigned_waiter_id && 
                                   table.assigned_waiter_id != selectedWaiterId;
                const restrictedClass = isRestricted ? 'restricted' : '';
                
                html += `
                    <div class="table-card ${statusClass} ${restrictedClass}" 
                         data-table-id="${table.id}" 
                         data-table-name="${table.name}" 
                         data-status="${table.status}"
                         data-transaction-id="${table.transaction_id || ''}"
                         data-assigned-waiter-id="${table.assigned_waiter_id || ''}"
                         style="background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); text-align: center;"
                         onmouseover="this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.15)'; this.style.borderColor='#059669'"
                         onmouseout="this.style.boxShadow='0 1px 3px rgba(0, 0, 0, 0.1)'; this.style.borderColor='#E5E7EB'">
                        
                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; background: ${statusColor}; border-radius: 50%;"></span>
                            <span style="font-size: 14px; font-weight: 500; color: ${statusColor};">${statusText}</span>
                        </div>
                        
                        <h3 style="font-size: 20px; font-weight: 700; color: #374151; margin: 0;">${table.name}</h3>
                        
                        ${table.status === 'occupied' ? `
                            <div style="font-size: 14px; color: #6B7280; margin-top: auto;">
                                <p style="margin: 0 0 2px 0;">${table.waiter_name || 'Unknown'}</p>
                                <p style="margin: 0 0 6px 0;">${table.time_elapsed || ''}</p>
                                ${table.is_paid ? `<span style="display:inline-block; background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; padding:2px 8px; border-radius:20px; margin-bottom:6px;"><i class="fa fa-check-circle"></i> Paid</span>` : ''}
                            </div>
                            ${!isRestricted && table.assigned_waiter_id == selectedWaiterId ? `
                                <button class="btn-transfer-table"
                                        data-table-id="${table.id}"
                                        data-table-name="${table.name}"
                                        data-transaction-id="${table.transaction_id}"
                                        style="margin-top: 4px; padding: 6px 12px; font-size: 12px; background: #059669; color: white; border: none; border-radius: 6px; cursor: pointer; width: 100%;">
                                    <i class="fa fa-exchange"></i> Transfer
                                </button>
                            ` : ''}
                        ` : `
                            <p style="font-size: 14px; color: #059669; font-weight: 500; margin: 0; margin-top: auto;">Ready for service</p>
                        `}
                    </div>
                `;
            });
        }
        
        $('#tables_grid').html(html);
    }

    // Update summary statistics
    function updateSummary(summary) {
        $('#total_tables').text(summary.total || 0);
        $('#available_count').text(summary.available || 0);
        $('#occupied_count').text(summary.occupied || 0);
    }

    // Filter tables by status
    function filterTables(filter) {
        $('.table-card').each(function() {
            const status = $(this).data('status');
            
            if (filter === 'all' || status === filter) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Show waiter selection required
    function showWaiterSelectionRequired() {
        $('#tables_grid').html(`
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #6b7280;">
                <i class="fa fa-user fa-3x" style="margin-bottom: 16px; opacity: 0.3;"></i>
                <h4 style="margin-bottom: 8px; color: #374151;">Select a Waiter</h4>
                <p style="color: #6b7280;">Please choose a waiter from the dropdown above to view tables.</p>
            </div>
        `);
        updateSummary({total: 0, available: 0, occupied: 0});
        $('#selected_waiter_display').text('');
    }

    // Show error message
    function showError(message) {
        $('#tables_grid').html(`
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #ef4444;">
                <i class="fa fa-exclamation-triangle fa-3x" style="margin-bottom: 16px;"></i>
                <h4 style="margin-bottom: 8px;">Error Loading Tables</h4>
                <p>${message}</p>
                <button class="btn btn-primary" onclick="loadTableStatus()" style="margin-top: 16px; border-radius: 6px;">
                    <i class="fa fa-refresh"></i> Try Again
                </button>
            </div>
        `);
        updateSummary({total: 0, available: 0, occupied: 0});
    }

    // Variable globale pour stocker les infos de transfert
    let transferData = {
        tableId: null,
        tableName: null,
        transactionId: null
    };

    // Ouvrir le modal de transfert
    function openTransferModal(tableId, tableName, transactionId) {
        transferData = {
            tableId: tableId,
            tableName: tableName,
            transactionId: transactionId
        };
        
        $('#transfer_table_name').text(tableName);
        
        // Charger la liste des service staff (exclure le serveur actuel)
        loadAvailableServiceStaffForTransfer(selectedWaiterId);
        
        $('#transfer_table_modal').modal('show');
    }

    // Charger les service staff disponibles
    function loadAvailableServiceStaffForTransfer(excludeWaiterId) {
        $.ajax({
            url: '/modules/data/get-service-staff',
            method: 'GET',
            data: { exclude_id: excludeWaiterId },
            success: function(response) {
                const select = $('#transfer_to_waiter');
                select.empty();
                select.append('<option value="">-- Select Service Staff --</option>');
                
                if (response && response.length > 0) {
                    response.forEach(function(waiter) {
                        select.append(`<option value="${waiter.id}">${waiter.name}</option>`);
                    });
                } else {
                    select.append('<option value="">No other service staff available</option>');
                }
            },
            error: function() {
                toastr.error('Failed to load service staff list');
            }
        });
    }

    // Confirmer le transfert
    $('#confirm_transfer_btn').click(function() {
        const toWaiterId = $('#transfer_to_waiter').val();
        
        if (!toWaiterId) {
            toastr.error('Please select a service staff');
            return;
        }
        
        // Le PIN sera vérifié automatiquement côté serveur (utilise le PIN déjà vérifié de la session)
        performTransfer(transferData.tableId, transferData.transactionId, selectedWaiterId, toWaiterId);
    });

    // Effectuer le transfert
    function performTransfer(tableId, transactionId, fromWaiterId, toWaiterId) {
        $('#confirm_transfer_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Transferring...');
        
        $.ajax({
            url: '/modules/restaurant/tables/transfer',
            method: 'POST',
            data: {
                table_id: tableId,
                transaction_id: transactionId,
                from_waiter_id: fromWaiterId,
                to_waiter_id: toWaiterId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Table transferred successfully');
                    $('#transfer_table_modal').modal('hide');
                    loadTableStatus(); // Recharger la liste des tables
                } else {
                    toastr.error(response.msg || 'Transfer failed');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.msg || 'Transfer failed. Please try again.';
                toastr.error(errorMsg);
            },
            complete: function() {
                $('#confirm_transfer_btn').prop('disabled', false).html('<i class="fa fa-check"></i> Transfer');
            }
        });
    }

    // Select table and close modal
    function selectTable(tableId, tableName, status, transactionId) {
        // Update hidden fields
        $('#res_table_id').val(tableId);
        $('#res_waiter_id').val(selectedWaiterId);
        
        // Update display field
        $('#table_waiter_display').val(`Table ${tableName} - ${selectedWaiterName}`);
        
        // Update hint text
        $('#table_hint').html(
            `<i class="fa fa-check-circle text-success"></i> 
             Table ${tableName} assigned to ${selectedWaiterName}`
        );
        
        // Close modal
        $('#table_status_modal').modal('hide');
        
        // Handle resume or new order
        if (status === 'occupied' && transactionId) {
            toastr.success(`Resumed Table ${tableName}`, 'Loading existing order...');
            loadExistingTableOrder(transactionId);
        } else {
            toastr.success(`Table ${tableName} selected for ${selectedWaiterName}`);
        }
    }

    // Load existing order items into POS
    function loadExistingTableOrder(transactionId) {
        if (!transactionId) return;

        $.ajax({
            url: `/modules/pos/load-table-order/${transactionId}`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.items) {
                    // Clear current cart
                    $('#pos_table tbody').empty();
                    $('#product_row_count').val(0);
                    
                    // Add products to cart with transaction_sell_lines_id
                    response.items.forEach(function(item) {
                        // Store transaction_sell_lines_id in a global variable before calling pos_product_row
                        // This will be picked up by the AJAX call in pos.js
                        if (item.transaction_sell_lines_id) {
                            window.current_loading_transaction_sell_lines_id = item.transaction_sell_lines_id;
                        }
                        
                        // Call pos_product_row - it will pick up transaction_sell_lines_id from global variable
                        pos_product_row(item.variation_id, null, null, item.quantity);
                        
                        // Clear the global variable after use
                        window.current_loading_transaction_sell_lines_id = null;
                    });
                    
                    // Update totals
                    if (typeof pos_total_row === 'function') {
                        pos_total_row();
                    }
                    
                    toastr.success('Order loaded successfully');
                } else {
                    toastr.error('Failed to load order items');
                }
            },
            error: function() {
                toastr.error('Failed to load existing order');
            }
        });
    }

    // Auto refresh every 30 seconds
    function startAutoRefresh() {
        stopAutoRefresh();
        autoRefreshInterval = setInterval(function() {
            if (selectedWaiterId) {
                loadTableStatus();
            }
        }, 30000);
    }

    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
        }
    }

    // Clean up on modal close
    $('#table_status_modal').on('hidden.bs.modal', function() {
        stopAutoRefresh();
    });
});
</script>