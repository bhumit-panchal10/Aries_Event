@if (Session::has('success') || Session::has('error'))
    <style>
        /* === Oroveda Alert Modal === */
        .oroveda-modal .modal-content {
            border-radius: 18px;
            background: #ffffff;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: fadeInUp 0.4s ease-out;
            position: relative;
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .oroveda-modal .modal-header {
            background: linear-gradient(135deg, #2a7d3e, #8bc34a);
            color: white;
            text-align: center;
            justify-content: center;
            padding: 25px;
            border-bottom: none;
            position: relative;
        }

        .oroveda-modal .modal-header img {
            width: 100px;
            border-radius: 5%;
            background: #fff;
            padding: 8px;
            box-shadow: 0 2px 10px rgba(255, 255, 255, 0.3);
        }

        .oroveda-modal .btn-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            font-size: 1.3rem;
            color: white;
            opacity: 1;
        }

        .oroveda-modal .modal-body {
            text-align: center;
            padding: 35px 25px;
        }

        .oroveda-modal .modal-body i {
            font-size: 56px;
            color: {{ Session::has('success') ? '#4CAF50' : '#f44336' }};
            margin-bottom: 15px;
            animation: popIn 0.5s ease-out;
        }

        @keyframes popIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .oroveda-modal .modal-body h5 {
            font-weight: 700;
            color: #2a7d3e;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .oroveda-modal .modal-body p {
            color: #444;
            font-size: 1rem;
            margin-bottom: 0;
        }
    </style>

    <div class="modal fade oroveda-modal" id="frontAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header position-relative">
                    <img src="{{ asset('assets/front/images/logo.png') }}" alt="Oroveda Logo">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <i class="fa {{ Session::has('success') ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    <h5>{{ Session::has('success') ? 'Success!' : 'Oops!' }}</h5>
                    <p>{{ session('success') ?? session('error') }}</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = new bootstrap.Modal(document.getElementById('frontAlertModal'));
            modal.show();
            setTimeout(() => modal.hide(), 4000); // Auto-hide after 4 seconds
        });
    </script>
@endif
