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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Return
            </h3>
            <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Returns
            </a>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
            <strong><i class="bi bi-exclamation-circle me-2"></i>Whoops!</strong> There were some problems with your input.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Container -->
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('returns.update', $returnItem->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Loan Information Card -->
                    @if(isset($selectedLoan))
                    <div class="card border-0 bg-light rounded-3 mb-4">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Loan Information
                            </h6>
                            <div class="row g-3">
                                <!-- Item Image Column -->
                                <div class="col-md-4" id="itemImageContainer" style="display: none;">
                                    <img id="loanItemImage" src="" alt="Item Image" class="img-fluid rounded-3" style="max-height: 150px; object-fit: cover; width: 100%;">
                                </div>
                                <!-- Loan Details Column -->
                                <div class="col-md-8" id="loanDetailsColumn">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Loan Code</small>
                                            <strong>{{ $selectedLoan->loan_code }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Item</small>
                                            <strong>{{ $selectedLoan->item->item_name }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Borrower</small>
                                            <strong>{{ $selectedLoan->user->username }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Quantity</small>
                                            <strong>{{ $selectedLoan->quantity }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Loan Date</small>
                                            <strong>{{ \Carbon\Carbon::parse($selectedLoan->loan_date)->format('d M Y') }}</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Deadline</small>
                                            <strong>{{ \Carbon\Carbon::parse($selectedLoan->return_date)->format('d M Y') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const itemImage = "{{ $selectedLoan->item->item_image ? asset('storage/' . $selectedLoan->item->item_image) : '' }}";
                            if (itemImage) {
                                const imgContainer = document.getElementById('itemImageContainer');
                                const detailsColumn = document.getElementById('loanDetailsColumn');
                                imgContainer.style.display = 'block';
                                detailsColumn.className = 'col-md-8';
                                document.getElementById('loanItemImage').src = itemImage;
                            } else {
                                document.getElementById('loanDetailsColumn').className = 'col-md-12';
                            }
                        });
                    </script>
                    @endif

                    <!-- Return Date -->
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="return_date" class="form-label fw-semibold text-primary">
                                Return Date <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                    class="form-control rounded-3 @error('return_date') is-invalid @enderror"
                                    id="return_date"
                                    name="return_date"
                                    value="{{ old('return_date', $returnItem->return_date) }}"
                                    required>
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="condition" class="form-label fw-semibold text-primary">
                                Condition <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('condition') is-invalid @enderror"
                                    id="condition"
                                    name="condition"
                                    required>
                                <option value="">Select Condition</option>
                                <option value="good"    {{ old('condition', $returnItem->condition) == 'good'    ? 'selected' : '' }}>Good</option>
                                <option value="damaged" {{ old('condition', $returnItem->condition) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="notes" class="form-label fw-semibold text-primary">
                                Notes
                            </label>
                            <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror"
                                        id="notes"
                                        name="notes"
                                        rows="4"
                                        placeholder="Add any additional notes...">{{ old('notes', $returnItem->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="{{ route('returns.index') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>
                            Update Return
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
