@if (session('success') || session('error'))
    @php
        $isSuccess = session('success') !== null;
        $message   = session('success') ?? session('error');
        $modalId   = 'alertModal';
    @endphp

    <!-- Alert Modal -->
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">

                <!-- Header strip -->
                <div style="height: 6px; background: {{ $isSuccess ? 'linear-gradient(90deg, #22c55e, #16a34a)' : 'linear-gradient(90deg, #ef4444, #dc2626)' }};"></div>

                <div class="modal-body text-center py-4 px-4">
                    <!-- Icon -->
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                         style="width: 72px; height: 72px; border-radius: 50%;
                                background: {{ $isSuccess ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' }};">
                        @if ($isSuccess)
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="#22c55e" opacity="0.15"/>
                                <path d="M7 12.5l3.5 3.5 6.5-7" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @else
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="#ef4444" opacity="0.15"/>
                                <path d="M12 7v5m0 3.5v.5" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Title -->
                    <h5 class="fw-bold mb-2" style="color: {{ $isSuccess ? '#15803d' : '#b91c1c' }}; font-size: 1.15rem;">
                        {{ $isSuccess ? 'Success!' : 'Failed!' }}
                    </h5>

                    <!-- Message -->
                    <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.5;">
                        {{ $message }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 justify-content-center pb-4 pt-0">
                    <button type="button" class="btn px-5 fw-semibold"
                            data-bs-dismiss="modal"
                            style="border-radius: 50px;
                                   background: {{ $isSuccess ? 'linear-gradient(135deg, #22c55e, #16a34a)' : 'linear-gradient(135deg, #ef4444, #dc2626)' }};
                                   color: white;
                                   border: none;
                                   box-shadow: {{ $isSuccess ? '0 4px 14px rgba(34,197,94,0.35)' : '0 4px 14px rgba(239,68,68,0.35)' }};">
                        OK
                    </button>
                </div>

                @if ($isSuccess)
                <!-- progress bar -->
                <div id="alertProgressBar"
                     style="height: 4px; width: 100%;
                            background: linear-gradient(90deg, #22c55e, #16a34a);
                            transform-origin: left;
                            transition: transform 3s linear;">
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalEl  = document.getElementById('{{ $modalId }}');
            var modal    = new bootstrap.Modal(modalEl);
            var isSuccess = {{ $isSuccess ? 'true' : 'false' }};

            modal.show();

            if (isSuccess) {
                // shrink progress bar then close
                var bar = document.getElementById('alertProgressBar');
                if (bar) {
                    setTimeout(function () {
                        bar.style.transform = 'scaleX(0)';
                    }, 50);
                }
                setTimeout(function () {
                    modal.hide();
                }, 3000);
            }
        });
    </script>
@endif
