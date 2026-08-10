@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif;
    }

    /* Header dengan Panduan */
    .header-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .header-left {
        flex: 1;
    }
    
    .header-right {
        flex-shrink: 0;
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
    
    .btn-guide {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #1a1a2e;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
        white-space: nowrap;
    }
    
    .btn-guide:hover {
        background: #2d2d44;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26,26,46,0.15);
    }
    
    .btn-guide svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
    
    @media (max-width: 640px) {
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .header-right {
            width: 100%;
        }
        
        .btn-guide {
            width: 100%;
            justify-content: center;
        }
    }

    /* Filter Buttons */
    .filter-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 24px;
    }

    .filter-btn {
        padding: 8px 20px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }

    .filter-btn:hover {
        border-color: #1a1a2e;
        color: #1a1a2e;
    }

    .filter-btn.active {
        background: #1a1a2e;
        color: white;
        border-color: #1a1a2e;
    }

    .filter-btn.active:hover {
        background: #2d2d44;
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
            height: 200px !important;
        }
        .card-body {
            padding: 10px 14px 14px;
        }
        .card-name {
            font-size: 13px;
        }
        .filter-btn {
            font-size: 12px;
            padding: 6px 14px;
        }
        .header-section {
            flex-direction: column;
            align-items: flex-start;
        }
        .header-right {
            width: 100%;
        }
        .btn-guide {
            width: 100%;
            justify-content: center;
        }
    }

    .card-item {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        display: block;
    }

    .card-item:hover {
        border-color: #1a1a2e;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transform: translateY(-2px);
    }

    .card-item.hidden {
        display: none;
    }

   .card-image {
    height: 200px;
    width: 100%;
    background: #f8fafc;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
        font-family: 'Poppins', sans-serif;
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

    /* MODAL PANDUAN KHUSUS */
    .modal-panduan {
        max-width: 720px !important;
    }

    .modal-panduan .modal-body {
        padding: 28px 30px;
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
            height: 300px;
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
        font-family: 'Poppins', sans-serif;
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
        font-family: 'Poppins', sans-serif;
        margin-top: 4px;
    }

    .btn-primary:hover {
        background: #2d2d44;
    }

    /* ===== MODAL PANDUAN CONTENT ===== */
    .modal-guide-content {
        padding: 0;
    }
    
    .guide-step {
        display: flex;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #eef2f6;
        align-items: flex-start;
    }
    
    .guide-step:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .guide-step:first-child {
        padding-top: 0;
    }
    
    .guide-number {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        background: #1a1a2e;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        margin-top: 2px;
    }
    
    .guide-text {
        flex: 1;
    }
    
    .guide-text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 2px 0;
    }
    
    .guide-text p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    
    .guide-text .highlight {
        color: #1a1a2e;
        font-weight: 500;
    }
    
    .guide-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }
    
    .guide-tag {
        background: #f1f4f9;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 11px;
        color: #1a1a2e;
    }
    
    .guide-icon-box {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f4f9;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 12px;
        color: #1a1a2e;
        margin-top: 4px;
    }
    
    .guide-icon-box svg {
        width: 14px;
        height: 14px;
    }
    
    .modal-guide-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 4px;
    }
    
    @media (max-width: 600px) {
        .modal-guide-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .guide-tip {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 14px;
        border-left: 3px solid #1a1a2e;
    }
    
    .guide-tip h5 {
        font-size: 11px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 3px 0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .guide-tip p {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    
    .guide-title-main {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 2px 0;
        letter-spacing: -0.2px;
    }
    
    .guide-subtitle {
        font-size: 13px;
        color: #94a3b8;
        margin: 0 0 18px 0;
    }

    .guide-divider {
        margin: 18px 0 16px 0;
        border: none;
        border-top: 1px solid #eef2f6;
    }

    .guide-btn-close {
        padding: 8px 24px;
        background: #1a1a2e;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
    }

    .guide-btn-close:hover {
        background: #2d2d44;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
        grid-column: 1 / -1;
    }
    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 12px;
    }
    .empty-state .title {
        font-size: 16px;
        font-weight: 500;
        color: #64748b;
    }
    .empty-state .desc {
        font-size: 13px;
        margin-top: 4px;
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
    /* ===== NOTES DALAM MODAL ===== */
.modal-note {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 14px;
}

.modal-note .note-title {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.modal-note .note-title .icon {
    font-size: 14px;
}

.modal-note .note-title .label {
    font-size: 11px;
    font-weight: 600;
    color: #92400e;
}

.modal-note .note-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.modal-note .note-list li {
    font-size: 10px;
    color: #78350f;
    padding: 2px 0;
    display: flex;
    align-items: flex-start;
    gap: 5px;
    line-height: 1.3;
}

.modal-note .note-list li .bullet {
    color: #d97706;
    font-weight: 700;
}

.modal-note .note-list li strong {
    font-weight: 600;
}
</style>

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header dengan Tombol Panduan -->
    <div class="header-section">
        <div class="header-left">
            <h1 class="page-title">Jendela</h1>
            <p class="page-subtitle">Hitung kebutuhan material jendela</p>
        </div>
        <div class="header-right">
            <button class="btn-guide" onclick="openModal('modalPanduan')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Panduan Pengguna
            </button>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-container">
        <button class="filter-btn active" data-filter="all">Semua</button>
        <button class="filter-btn" data-filter="mati">Jendela Mati</button>
        <button class="filter-btn" data-filter="jungkit">Jendela Jungkit</button>
        <button class="filter-btn" data-filter="swing">Jendela Swing</button>
        <button class="filter-btn" data-filter="sliding">Jendela Sliding</button>
    </div>

    <!-- Cards Grid -->
    <div class="card-grid" id="cardGrid">
        <!-- Card: Jendela Mati 1 Kaca -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-1-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 1 Daun</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati1Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Mati 2 Kaca -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-2-kaca.png') }}" alt="Jendela Mati 2 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 2 Daun Coupling</div>
                <p class="card-desc">Jendela tetap / non-opening, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati2Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Mati 3 Kaca -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-3-kaca.png') }}" alt="Jendela Mati 3 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 3 Daun Coupling</div>
                <p class="card-desc">Jendela tetap / non-opening, 3 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati3Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Mati 1 Kaca Mullion -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-1-kaca_mullion.png') }}" alt="Jendela Mati 1 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 1 Daun Mullion</div>
                <p class="card-desc">Jendela Mati dengan Mullion, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati1KacaMullion')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Mati 2 Kaca Mullion -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-2-kaca_mullion.png') }}" alt="Jendela Mati 2 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 2 Daun Mullion</div>
                <p class="card-desc">Jendela Mati 2 Kaca dengan Mullion</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati2KacaMullion')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Mati 3 Kaca Mullion -->
        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-3-kaca_mullion.png') }}" alt="Jendela Mati 3 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 3 Daun Mullion</div>
                <p class="card-desc">Jendela Mati 3 Kaca dengan Mullion</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati3KacaMullion')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-1-kaca-1-mullion-vertikal-dan-horizontal.png') }}" alt="Jendela Mati 3 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 1 Daun 1 Mullion Vertikal dan Horizontal</div>
                <p class="card-desc">Jendela Mati 1 Kaca dengan 2 Mullion Horizontal</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati1Kaca1MullionVertikalHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-2-kaca-2-mullion-vertikal.png') }}" alt="Jendela Mati 3 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 2 Daun 2 Mullion Vertikal dan 1 Mullion Horizontal</div>
                <p class="card-desc">Jendela Mati 1 Kaca dengan 2 Mullion Horizontal</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati2Kaca2MullionVertikalHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-3-kaca-2-mullion-vertikal-1-mullion-horizontal.png') }}" alt="Jendela Mati 3 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 3 Daun 2 Mullion Vertikal 1 Mullion Horizontal</div>
                <p class="card-desc">Jendela Mati 1 Kaca dengan 2 Mullion Horizontal</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati3Kaca2MullionVertikal1MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-1-mullion-horizontal.png') }}" alt="Jendela Mati 3 Kaca Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Mati 1 Daun 2 Mullion Horizontal</div>
                <p class="card-desc">Jendela Mati 1 Kaca dengan 2 Mullion Horizontal</p>
                <button class="card-btn" onclick="openModal('modalJendelaMati1Kaca2MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

          <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-bouven-1-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Bouven 1 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaBouven1Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-bouven-2-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Bouven 2 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaBouven2Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-bouven-3-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Bouven 3 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaBouven3Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-bouven-4-kaca.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Bouven 4 Kaca</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaBouven4Kaca')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <div class="card-item" data-type="mati">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-bouven-silang.png') }}" alt="Jendela Mati 1 Kaca">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Bouven Silang</div>
                <p class="card-desc">Jendela tetap / non-opening, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaBouvenSilang')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Swing 1 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-1.png') }}" alt="Jendela Swing 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 1 Daun</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing1Daun')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Swing 1 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-1-1-mullion-vertikal-1-mullion-horizontal.png') }}" alt="Jendela Swing 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 1 Daun 1 Mullion Vertikal Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing1Daun1MullionVertikalHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>
         <!-- Card: Jendela Swing 1 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-1-1-mullion-vertikal-2-mullion-horizontal.png') }}" alt="Jendela Swing 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 1 Daun 1 Mullion Vertikal 2 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing1Daun1MullionVertikal2MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>
         <!-- Card: Jendela Swing 1 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-1-1-mullion-vertikal-3-mullion-horizontal.png') }}" alt="Jendela Swing 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 1 Daun 1 Mullion Vertikal 3 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing1Daun1MullionVertikal3MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Swing 2 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-2.png') }}" alt="Jendela Swing 2 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 2 Daun</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing2Daun')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Swing 2 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-2-2-mullion-vertikal-1-mullion-horizontal.png') }}" alt="Jendela Swing 2 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 2 Daun 2 Mullion Vertikal 1 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing2Daun2MullionVertikal1MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>
        <!-- Card: Jendela Swing 2 Daun -->
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-2-2-mullion-vertikal-2-mullion-horizontal.png') }}" alt="Jendela Swing 2 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 2 Daun 2 Mullion Vertikal 2 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing2Daun2MullionVertikal2MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>
        <div class="card-item" data-type="swing">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-swing-2-2-mullion-vertikal-3-mullion-horizontal.png') }}" alt="Jendela Swing 2 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Swing 2 Daun 2 Mullion Vertikal 3 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaSwing2Daun2MullionVertikal3MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Jungkit 1 Daun -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-1.png') }}" alt="Jendela Jungkit 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 1 Daun</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit1Daun')">Hitung Kebutuhan →</button>
            </div>
        </div>

             <!-- Card: Jendela Jungkit 1 Daun -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-1-kaca-1-mullion-vertikal-horizontal.png') }}" alt="Jendela Jungkit 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 1 Daun 1 Mullion Vertikal 1 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit1MullionVertikal1MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

           <!-- Card: Jendela Jungkit 1 Daun -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-1-mullion-vertikal-2-horizontal.png') }}" alt="Jendela Jungkit 1 Daun">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 1 Daun 1 Mullion Vertikal 2 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit1MullionVertikal2MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

           <!-- Card: Jendela Jungkit 1 Daun Mullion -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-1-mullion.png') }}" alt="Jendela Jungkit 1 Daun Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 1 Daun Mullion</div>
                <p class="card-desc">Jendela buka dengan engsel, 1 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit1DaunMullion')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Jungkit 2 Daun Coupling -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-2.png') }}" alt="Jendela Jungkit 2 Daun Coupling">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 2 Daun Coupling</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit2Daun')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Jungkit 2 Daun Mullion -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-2-mullion-vertikal-1-mullion-horizontal.png') }}" alt="Jendela Jungkit 2 Daun Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 2 Daun 2 Mullion Vertikal 1 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit2Daun2MullionVertikal2MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-2-mullion.png') }}" alt="Jendela Jungkit 2 Daun Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 2 Daun Mullion</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit2DaunMullion')">Hitung Kebutuhan →</button>
            </div>
        </div>

          <!-- Card: Jendela Jungkit 2 Daun Mullion -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-jungkit-2-mullion-vertikal-3-mullion-horizontal.png') }}" alt="Jendela Jungkit 2 Daun Mullion">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 2 Daun 2 Mullion Vertikal 3 Mullion Horizontal</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit2Daun2MullionVertikal3MullionHorizontal')">Hitung Kebutuhan →</button>
            </div>
        </div>

         <!-- Card: Jendela Jungkit 2 Daun Bouven -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-2-bouven.png') }}" alt="Jendela Jungkit 2 Daun Bouven">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 2 Daun Bouven</div>
                <p class="card-desc">Jendela buka dengan engsel, 2 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit2DaunBouven')">Hitung Kebutuhan →</button>
            </div>
        </div>

     

    
      


       
      
        

        <!-- Card: Jendela Jungkit 4 Daun Bouven -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-4-bouven.png') }}" alt="Jendela Jungkit 4 Daun Bouven">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 4 Daun Bouven</div>
                <p class="card-desc">Jendela buka dengan engsel, 4 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit4DaunBouven')">Hitung Kebutuhan →</button>
            </div>
        </div>

       

        <!-- Card: Jendela Jungkit 12 Daun Bouven -->
        <div class="card-item" data-type="jungkit">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-12-bouven.png') }}" alt="Jendela Jungkit 12 Daun Bouven">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Jungkit 12 Daun Bouven</div>
                <p class="card-desc">Jendela buka dengan engsel, 12 panel kaca</p>
                <button class="card-btn" onclick="openModal('modalJendelaJungkit12DaunBouven')">Hitung Kebutuhan →</button>
            </div>
        </div>

        <!-- Card: Jendela Sliding -->
        <div class="card-item" data-type="sliding">
            <div class="card-image">
                <img src="{{ asset('images/jendela/jendela-sliding.png') }}" alt="Jendela Sliding">
            </div>
            <div class="card-body">
                <div class="card-name">Jendela Sliding</div>
                <p class="card-desc">Jendela geser / sliding</p>
                <button class="card-btn" onclick="openModal('modalJendelaSliding')">Hitung Kebutuhan →</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL PANDUAN ===== -->
<div class="modal-overlay" id="modalPanduan">
    <div class="modal-wrapper">
        <div class="modal-container modal-panduan">
            <button class="modal-close" onclick="closeModal('modalPanduan')">&times;</button>
            
            <div class="modal-body">
                <h2 class="guide-title-main">📘 Panduan Pengguna</h2>
                <img src="{{ asset('images/jendela/bagian-jendela.png') }}" style="width:50%" alt=""  class="mx-auto block">
                <p class="guide-subtitle">Cara menghitung kebutuhan material jendela</p>
                
                <div class="modal-guide-content">
                    <!-- Langkah 1 -->
                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <div class="guide-text">
                            <h4>Pilih Type Jendela</h4>
                            <p>Klik tombol filter di atas untuk memilih type jendela:
                            <div class="guide-tags">
                                <span class="guide-tag">Mati</span>
                                <span class="guide-tag">Jungkit</span>
                                <span class="guide-tag">Swing</span>
                                <span class="guide-tag">Sliding</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 2 -->
                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <div class="guide-text">
                            <h4>Pilih Model Jendela</h4>
                            <p>Klik tombol <span class="highlight">"Hitung Kebutuhan"</span> pada kartu model jendela yang Anda pilih.</p>
                        </div>
                    </div>
                    
                    <!-- Langkah 3 -->
                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <div class="guide-text">
                            <h4>Masukkan Dimensi</h4>
                            <p>Isi semua ukuran yang diminta seperti <span class="highlight">panjang</span>, <span class="highlight">lebar</span>, dan <span class="highlight">jumlah unit</span> jendela.</p>
                            <div class="guide-tags">
                                <span class="guide-tag">📏 Panjang (cm)</span>
                                <span class="guide-tag">📐 Lebar (cm)</span>
                                <span class="guide-tag">🔢 Jumlah Unit</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 4 -->
                    <div class="guide-step">
                        <div class="guide-number">4</div>
                        <div class="guide-text">
                            <h4>Pilih Spesifikasi</h4>
                            <p>Pilih <span class="highlight">warna profile</span>, <span class="highlight">type kaca</span>, dan <span class="highlight">handle</span> yang diinginkan.</p>
                            <div class="guide-tags">
                                <span class="guide-tag">🎨 Warna Profile</span>
                                <span class="guide-tag">🔍 Type Kaca</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 5 -->
                    <div class="guide-step">
                        <div class="guide-number">5</div>
                        <div class="guide-text">
                            <h4>Klik Hitung & Lihat Hasil</h4>
                            <p>Klik tombol <span class="highlight">"Hitung Kebutuhan Material"</span> untuk melihat hasil perhitungan.</p>
                            <div class="guide-tags">
                                <span class="guide-tag" style="background:#1a1a2e;color:#fff;">✓ Profile</span>
                                <span class="guide-tag" style="background:#1a1a2e;color:#fff;">✓ Reinforcement</span>
                                <span class="guide-tag" style="background:#1a1a2e;color:#fff;">✓ Kaca</span>
                                <span class="guide-tag" style="background:#1a1a2e;color:#fff;">✓ Screw</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tips -->
                <hr class="guide-divider">
                
                <div class="modal-guide-grid">
                    <div class="guide-tip">
                        <h5>💡 Tips</h5>
                        <p>Pastikan dimensi yang dimasukkan adalah ukuran <strong>bersih</strong> lubang jendela (bukaan) untuk hasil yang akurat.</p>
                    </div>
                    <div class="guide-tip">
                        <h5>📌 Catatan</h5>
                        <p>Hasil perhitungan akan menampilkan kebutuhan <strong>Profile, Reinforcement, Kaca, dan Screw</strong> secara lengkap.</p>
                    </div>
                </div>
                
                <!-- Tombol Tutup -->
                <div style="margin-top: 18px; text-align: right;">
                    <button class="guide-btn-close" onclick="closeModal('modalPanduan')">
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODALS ===== -->
@include('jendela.partials.modals.modal-jendela-mati-1-kaca')
@include('jendela.partials.modals.modal-jendela-bouven-1-kaca')
@include('jendela.partials.modals.modal-jendela-bouven-2-kaca')
@include('jendela.partials.modals.modal-jendela-bouven-3-kaca')
@include('jendela.partials.modals.modal-jendela-bouven-4-kaca')
@include('jendela.partials.modals.modal-jendela-bouven-silang')
@include('jendela.partials.modals.modal-jendela-mati-1-kaca-1-mullion-vertikal-horizontal')
@include('jendela.partials.modals.modal-jendela-mati-1-kaca-2-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-mati-2-kaca-2-mullion-vertikal-horizontal') 
@include('jendela.partials.modals.modal-jendela-jungkit-2-mullion-vertikal-1-mullion-horizontal') 
@include('jendela.partials.modals.modal-jendela-jungkit-2-mullion-vertikal-3-mullion-horizontal') 
@include('jendela.partials.modals.modal-jendela-mati-2-kaca')
@include('jendela.partials.modals.modal-jendela-mati-3-kaca')
@include('jendela.partials.modals.modal-jendela-mati-3-kaca-2-mullion-vertikal-1-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-mati-1-kaca_mullion')
@include('jendela.partials.modals.modal-jendela-mati-2-kaca_mullion')
@include('jendela.partials.modals.modal-jendela-mati-3-kaca_mullion')
@include('jendela.partials.modals.modal-jendela-swing-1-daun')
@include('jendela.partials.modals.modal-jendela-swing-2-daun')
@include('jendela.partials.modals.modal-jendela-swing-2-daun-2-mullion-vertikal-1-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-swing-2-daun-2-mullion-vertikal-2-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-swing-2-daun-2-mullion-vertikal-3-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-swing-1-daun-1-mullion-vertikal-1-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-swing-1-daun-1-mullion-vertikal-2-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-swing-1-daun-1-mullion-vertikal-3-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-jungkit-1-daun')
@include('jendela.partials.modals.modal-jendela-jungkit-1-mullion-vertikal-1-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-jungkit-1-mullion-vertikal-2-mullion-horizontal')
@include('jendela.partials.modals.modal-jendela-jungkit-2-daun')
@include('jendela.partials.modals.modal-jendela-jungkit-1-daun-mullion')
@include('jendela.partials.modals.modal-jendela-jungkit-2-daun-mullion')
@include('jendela.partials.modals.modal-jendela-jungkit-4-daun-bouven')
@include('jendela.partials.modals.modal-jendela-jungkit-2-daun-bouven')
@include('jendela.partials.modals.modal-jendela-jungkit-12-daun-bouven')
@include('jendela.partials.modals.modal-jendela-sliding')

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cardItems = document.querySelectorAll('.card-item');
    const cardGrid = document.getElementById('cardGrid');

    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            filterButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            let visibleCount = 0;

            cardItems.forEach(function(card) {
                const cardType = card.getAttribute('data-type');
                
                if (filter === 'all' || cardType === filter) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const existingEmpty = cardGrid.querySelector('.empty-state');
            if (visibleCount === 0) {
                if (!existingEmpty) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'empty-state';
                    emptyDiv.innerHTML = `
                        <div class="icon">🔍</div>
                        <div class="title">Tidak ada jendela</div>
                        <div class="desc">Tidak ada jenis jendela yang sesuai dengan filter ini</div>
                    `;
                    cardGrid.appendChild(emptyDiv);
                }
            } else {
                if (existingEmpty) {
                    existingEmpty.remove();
                }
            }
        });
    });
});

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
}

// Close modal when clicking on overlay background
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('modal-overlay')) {
        const modal = e.target;
        modal.style.display = 'none';
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(function(modal) {
            if (modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open');
            }
        });
    }
});
</script>
@endsection