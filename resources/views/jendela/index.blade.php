@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }

    .page-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        letter-spacing: -0.3px;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 400;
        margin-bottom: 24px;
    }

    .card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        max-width: 900px;
    }

    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
            max-width: 600px;
        }
    }

    @media (max-width: 640px) {
        .card-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .page-title {
            font-size: 17px;
        }
        .page-subtitle {
            font-size: 12px;
            margin-bottom: 16px;
        }
        .card-image {
            height: 160px !important;
        }
        .card-body {
            padding: 10px 14px 14px;
        }
        .card-name {
            font-size: 13px;
        }
    }

    .card-item {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .card-item:hover {
        border-color: #1a1a2e;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

   .card-image {
    height: 200px;
    width: 100%;
    background: #f8fafc;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0; /* Hapus padding */
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ganti dari contain ke cover */
    opacity: 0.85;
    transition: opacity 0.3s ease;
}

    .card-item:hover .card-image img {
        opacity: 1;
    }

    .card-body {
        padding: 14px 16px 16px;
        background: white;
        border-top: 1px solid #eef2f6;
    }

    .card-name {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a2e;
        margin-bottom: 4px;
        letter-spacing: -0.2px;
    }

    .card-desc {
        font-size: 11px;
        color: #94a3b8;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .card-btn {
        width: 100%;
        padding: 8px 0;
        background: #1a1a2e;
        border: none;
        border-radius: 6px;
        color: #ffffff;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
    }

    .card-btn:hover {
        background: #2d2d44;
    }

    /* ===== MODAL STYLES ===== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex !important;
    }

    .modal-wrapper {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .modal-container {
        position: relative;
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlide 0.3s ease-out;
        margin: 0 auto;
    }

    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-close {
        position: absolute;
        top: 14px;
        right: 16px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        transition: color 0.2s;
        padding: 4px;
        z-index: 10;
        font-size: 22px;
        line-height: 1;
    }

    .modal-close:hover {
        color: #1a1a2e;
    }

    .modal-grid {
        display: flex;
        flex-direction: row;
        min-height: 380px;
    }

    @media (max-width: 768px) {
        .modal-grid {
            flex-direction: column;
        }
    }

    .modal-image {
        width: 40%;
        background: #1a1a2e;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 16px 0 0 16px;
        min-height: 260px;
        overflow: hidden;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .modal-image {
            width: 100%;
            border-radius: 16px 16px 0 0;
            min-height: 180px;
            height: 180px;
        }
    }

    .modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.9;
    }

    .modal-form {
        width: 60%;
        padding: 24px 28px;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .modal-form {
            width: 100%;
            padding: 20px;
        }
    }

    .modal-form-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 2px;
        letter-spacing: -0.2px;
    }

    .modal-form-sub {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 16px;
    }

    .input-label {
        display: block;
        font-size: 10px;
        font-weight: 500;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 3px;
    }

    .input-field {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        color: #1a1a2e;
        background: #ffffff;
        transition: all 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .input-field:focus {
        outline: none;
        border-color: #1a1a2e;
        box-shadow: 0 0 0 3px rgba(26, 26, 46, 0.06);
    }

    .input-field::placeholder {
        color: #cbd5e1;
    }

    .btn-primary {
        width: 100%;
        padding: 9px 0;
        background: #1a1a2e;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        margin-top: 4px;
    }

    .btn-primary:hover {
        background: #2d2d44;
    }

    /* ===== NOTES / PEMBERITAHUAN ===== */
    .notes-container {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 14px;
    }

    .notes-container .notes-title {
        font-size: 11px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .notes-container .notes-title .icon {
        font-size: 14px;
    }

    .notes-container .notes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notes-container .notes-list li {
        font-size: 10px;
        color: #78350f;
        padding: 2px 0;
        display: flex;
        align-items: flex-start;
        gap: 5px;
        line-height: 1.3;
    }

    .notes-container .notes-list li .bullet {
        color: #d97706;
        font-weight: 700;
    }

    .notes-container .notes-list li .highlight {
        background: #fef3c7;
        padding: 0 3px;
        border-radius: 2px;
        font-weight: 500;
        color: #92400e;
    }

    body.modal-open {
        overflow: hidden !important;
    }

    .modal-container::-webkit-scrollbar {
        width: 4px;
    }
    .modal-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .modal-container::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 4px;
    }
    .modal-container {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 transparent;
    }
</style>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div>
        <h1 class="page-title">Jendela</h1>
        <p class="page-subtitle">Hitung kebutuhan material jendela</p>
    </div>

    <!-- Cards Grid -->
    <div class="card-grid">
        <!-- Card: Jendela Mati 1 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-1-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 1 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati1Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Mati 2 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-mati-2-kaca.png') }}" alt="Jendela Mati 2 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 2 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati2Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Mati 3 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-mati-3-kaca.png') }}" alt="Jendela Mati 3 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 3 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 3 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati3Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Casement 1 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-casement-1-kaca.png') }}" alt="Jendela Casement 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Casement 1 Kaca</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaCasement1Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Casement 2 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-casement-2-kaca.png') }}" alt="Jendela Casement 2 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Casement 2 Kaca</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaCasement2Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Casement 3 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-casement-3-kaca.png') }}" alt="Jendela Casement 3 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Casement 3 Kaca</div>
                <p class="card-desc">Jendela buka dengan engsel, 3 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaCasement3Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Sliding 2 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-sliding-2-kaca.png') }}" alt="Jendela Sliding 2 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Sliding 2 Kaca</div>
                <p class="card-desc">Jendela geser, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSliding2Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Sliding 3 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-sliding-3-kaca.png') }}" alt="Jendela Sliding 3 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Sliding 3 Kaca</div>
                <p class="card-desc">Jendela geser, 3 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSliding3Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Sliding 4 Kaca -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-sliding-4-kaca.png') }}" alt="Jendela Sliding 4 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Sliding 4 Kaca</div>
                <p class="card-desc">Jendela geser, 4 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSliding4Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODALS ===== -->
@include('jendela.partials.modals.modal-jendela-mati-1-kaca')

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
}

// Close modal when clicking on overlay background
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('modal-overlay')) {
        const modal = e.target;
        modal.classList.remove('active');
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
        });
    }
});
</script>
@endsection