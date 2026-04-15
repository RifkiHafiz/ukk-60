@extends('layouts.app')
@section('content')
<style>
    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #0369a1);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0284c7, #075985);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(14, 165, 233, 0.4);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0ea5e9;
        box-shadow: 0 0 0 0.25rem rgba(14, 165, 233, 0.15);
    }
</style>

<div class="bg-light min-vh-100 py-4">
    <div class="container">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 ms-3">
            <div>
                <h1 class="h2 fw-bold text-primary mb-1">
                    <i class="bi bi-bag-plus-fill me-2"></i>
                    Edit Loan
                </h1>
                <p class="text-muted mb-0">Edit loan request for borrowing items</p>
            </div>
            <a href="{{ route('loans.index-table') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Loans
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Whoops!</strong>
            </div>
            <ul class="mb-0 ms-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
        @endif

        @if(in_array($loan->status, ['rejected', 'cancelled']) && $loan->rejected_reason)
        @php
            $isCancelled = $loan->status === 'cancelled';
            $bannerLabel = $isCancelled ? 'This loan was cancelled.' : 'This loan was rejected.';
            $reasonLabel = $isCancelled ? 'Cancellation reason' : 'Rejection reason';
            $borderColor = $isCancelled ? '#f97316' : '#ef4444';
            $bgColor     = $isCancelled ? '#fff7ed' : '#fff1f2';
        @endphp
        <div class="alert border-0 rounded-3 mb-4" style="background:{{ $bgColor }}; border-left:4px solid {{ $borderColor }} !important;" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1" style="color:{{ $borderColor }}"></i>
                <div>
                    <strong style="color:{{ $borderColor }}">{{ $bannerLabel }}</strong>
                    <p class="mb-0 text-muted small mt-1">{{ $reasonLabel }}: <em>{{ $loan->rejected_reason }}</em></p>
                    <p class="mb-0 text-muted small">Please make the necessary changes and save to resubmit.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4">
            <!-- Form Section -->
            <div class="col">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('loans.resubmit', $loan->id) }}" method="POST" id="loanForm">
                            @csrf
                            @method('PUT')

                            <!-- Item Preview Card -->
                            <div class="card border-0 bg-light rounded-3 mb-4" id="itemPreviewCard" style="display: none;">
                                <div class="card-body p-3">
                                    <h6 class="text-primary fw-bold mb-3">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Selected Item Details
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4" id="previewImageContainer" style="display: none;">
                                            <img id="previewItemImage" src="" alt="Item Image" class="img-fluid rounded-3" style="max-height: 150px; object-fit: cover; width: 100%;">
                                        </div>
                                        <div class="col-md-8" id="previewDetailsContainer">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="text-muted" width="40%">Item Name:</td>
                                                    <td class="fw-bold" id="previewItemName">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Item Code:</td>
                                                    <td class="fw-bold" id="previewItemCode">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Category:</td>
                                                    <td id="previewCategory">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Available:</td>
                                                    <td class="fw-bold text-success" id="previewAvailable">-</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Condition:</td>
                                                    <td id="previewCondition">-</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden"
                                   name="item_id"
                                   id="itemId"
                                   value="{{ $selectedItem->id ?? '' }}"
                                   data-available="{{ $selectedItem->available_quantity ?? 0 }}">

                            <!-- Quantity -->
                            <div class="mb-4">
                                <label for="quantity" class="form-label fw-semibold text-primary">
                                    Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       class="form-control rounded-3 @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       min="1"
                                       max="{{ $selectedItem->available_quantity ?? '' }}"
                                       value="{{ old('quantity', $loan->quantity) }}"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" id="quantityHelp">
                                    @if(isset($selectedItem))
                                        Maximum available: {{ $selectedItem->available_quantity }}
                                    @else
                                        Enter the quantity you want to borrow
                                    @endif
                                </div>
                            </div>

                            <!-- Date Range -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="loan_date" class="form-label fw-semibold text-primary">
                                        Loan Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control rounded-3 @error('loan_date') is-invalid @enderror"
                                           id="loan_date"
                                           name="loan_date"
                                           value="{{ old('loan_date', $loan->loan_date) }}"
                                           required>
                                    @error('loan_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="return_date" class="form-label fw-semibold text-primary">
                                        Expected Return Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control rounded-3 @error('return_date') is-invalid @enderror"
                                           id="return_date"
                                           name="return_date"
                                           value="{{ old('return_date', $loan->return_date) }}"
                                           required>
                                    @error('return_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold text-primary">
                                    Notes <span class="text-muted small">(Optional)</span>
                                </label>
                                <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror"
                                          id="notes"
                                          name="notes"
                                          rows="4"
                                          placeholder="Add any additional notes or special requests...">{{ old('notes', $loan->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                                <a href="{{ route('loans.index-table') }}" class="btn btn-secondary px-4 rounded-3">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4 rounded-3">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Submit Loan Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to show item preview
    function showItemPreview(item) {
        const previewCard = document.getElementById('itemPreviewCard');
        const quantityInput = document.getElementById('quantity');

        // Show preview card
        previewCard.style.display = 'block';

        // Update preview details
        document.getElementById('previewItemName').textContent = item.name;
        document.getElementById('previewItemCode').textContent = item.code;
        document.getElementById('previewCategory').textContent = item.category;
        document.getElementById('previewAvailable').textContent = item.available;

        const conditionElement = document.getElementById('previewCondition');
        conditionElement.textContent = item.condition;
        conditionElement.className = item.condition === 'Good' ? 'fw-bold text-success' : 'fw-bold text-danger';

        // Handle item image
        const previewImageContainer = document.getElementById('previewImageContainer');
        const previewDetailsContainer = document.getElementById('previewDetailsContainer');

        if (item.image) {
            document.getElementById('previewItemImage').src = item.image;
            previewImageContainer.style.display = 'block';
            previewDetailsContainer.className = 'col-md-8';
        } else {
            previewImageContainer.style.display = 'none';
            previewDetailsContainer.className = 'col-md-12';
        }

        // Set max quantity
        quantityInput.max = item.available;
        document.getElementById('quantityHelp').textContent = `Maximum available: ${item.available}`;

        // Reset quantity if exceeds available
        if (parseInt(quantityInput.value) > item.available) {
            quantityInput.value = item.available;
        }
    }

    // Initialize page when loaded
    document.addEventListener('DOMContentLoaded', function() {
        const loanDateInput = document.getElementById('loan_date');
        const returnDateInput = document.getElementById('return_date');
        const quantityInput = document.getElementById('quantity');
        const itemIdInput = document.getElementById('itemId');
        const previewCard = document.getElementById('itemPreviewCard');

        // Show item preview on page load
        @if(isset($loan) && $loan->item)
            showItemPreview({
                id: {{ $loan->item->id }},
                name: "{{ $loan->item->item_name }}",
                code: "{{ $loan->item->item_code }}",
                category: "{{ $loan->item->category->category_name ?? 'No Category' }}",
                available: {{ $loan->item->available_quantity }},
                condition: "{{ $loan->item->condition }}",
                image: "{{ $loan->item->item_image ? asset('storage/' . $loan->item->item_image) : '' }}"
            });
        @endif

        // Set minimum return date based on loan date
        loanDateInput.addEventListener('change', function() {
            const loanDate = new Date(this.value);
            loanDate.setDate(loanDate.getDate() + 1);
            returnDateInput.min = loanDate.toISOString().split('T')[0];

            // Reset return date if it's before new minimum
            if (returnDateInput.value && new Date(returnDateInput.value) < loanDate) {
                returnDateInput.value = '';
            }
        });

        // Initialize return date minimum
        if (loanDateInput.value) {
            const loanDate = new Date(loanDateInput.value);
            loanDate.setDate(loanDate.getDate() + 1);
            returnDateInput.min = loanDate.toISOString().split('T')[0];
        }

        // Enforce quantity validation - prevent exceeding max
        quantityInput.addEventListener('input', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value);

            // Don't allow exceeding max quantity
            if (max && value > max) {
                this.value = max;
                alert(`Quantity cannot exceed available quantity (${max})`);
            }

            // Don't allow less than 1
            if (value < 1) {
                this.value = 1;
            }
        });

        // Prevent typing characters that would exceed max
        quantityInput.addEventListener('keydown', function(e) {
            const max = parseInt(this.max);
            const currentValue = parseInt(this.value) || 0;

            // Allow: backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }

            // Check if the new value would exceed max
            const newValue = parseInt(this.value + String.fromCharCode(e.keyCode));
            if (max && newValue > max) {
                e.preventDefault();
            }
        });

        // Validate before form submission
        document.getElementById('loanForm').addEventListener('submit', function(e) {
            const max = parseInt(quantityInput.max);
            const value = parseInt(quantityInput.value);

            if (max && value > max) {
                e.preventDefault();
                alert(`Quantity cannot exceed available quantity (${max})`);
                quantityInput.focus();
                return false;
            }

            if (!itemIdInput.value) {
                e.preventDefault();
                alert('Item ID is missing. Please select an item.');
                return false;
            }
        });
    });
</script>

@endsection
