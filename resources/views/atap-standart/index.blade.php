@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif;
    }
    
    /* ===== PAGE ENTRANCE (anti-kedip) ===== */
    .page-enter {
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .page-enter.ready {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* View Transition API (browser modern) */
    @view-transition {
        navigation: auto;
    }
    ::view-transition-old(root),
    ::view-transition-new(root) {
        animation-duration: 0.6s;
        animation-timing-function: cubic-bezier(0.25, 1, 0.5, 1);
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
        font-weight: 600;
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
        padding: 9px 18px;
        background: linear-gradient(135deg, #1a1a2e, #2d2d44);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        font-family: 'Poppins', sans-serif;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(26, 26, 46, 0.2);
    }
    
    .btn-guide:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
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
    
    /* ===== CARD GRID ===== */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    @media (max-width: 1024px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .page-title { font-size: 17px; }
        .page-subtitle { font-size: 12px; margin-bottom: 16px; }
        .card-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .card-image { height: 140px; padding: 14px; }
        .card-body { padding: 12px 14px 14px; }
        .card-name { font-size: 13px; }
        .modal-container { max-height: 95vh; border-radius: 12px; }
        .modal-grid { flex-direction: column; }
        .modal-image {
            width: 100%;
            border-radius: 12px 12px 0 0;
            min-height: 160px;
            padding: 16px;
        }
        .modal-form { width: 100%; padding: 16px; }
        .modal-form-title { font-size: 14px; }
        .modal-form-sub { font-size: 11px; margin-bottom: 12px; }
        .input-group, .input-group-2 {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .input-field, .select-field {
            padding: 6px 10px;
            font-size: 12px;
        }
        .btn-primary { padding: 8px 0; font-size: 12px; }
        .result-box { padding: 10px 12px; }
        .result-item { font-size: 11px; }
        .modal-footer { flex-direction: column; gap: 6px; }
        .modal-footer .btn-outline { padding: 6px 0; font-size: 11px; }
        .modal-close { top: 10px; right: 12px; font-size: 18px; }
        .modal-wrapper { padding: 0; }
        .modal-overlay { padding: 10px; }
    }
    
    /* ===== FLOATING GLASS CARD ===== */
    .card-item {
        position: relative;
        background: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(248, 250, 252, 0.85) 100%
        );
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        cursor: pointer;
        box-shadow:
            0 4px 16px rgba(15, 23, 42, 0.06),
            0 1px 3px rgba(15, 23, 42, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }
    
    .card-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(
            180deg,
            rgba(255, 255, 255, 0.8) 0%,
            rgba(255, 255, 255, 0.0) 100%
        );
        pointer-events: none;
        z-index: 1;
    }
    
    .card-item:hover {
        transform: translateY(-8px) scale(1.015);
        border-color: rgba(99, 102, 241, 0.3);
        background: #ffffff;
        box-shadow:
            0 20px 40px rgba(99, 102, 241, 0.15),
            0 8px 16px rgba(15, 23, 42, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }
    
    .card-image {
        position: relative;
        height: 230px;
        background: linear-gradient(
            135deg,
            #f8fafc 0%,
            #eef2ff 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 22px;
        z-index: 2;
    }
    
    .card-image img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        filter: drop-shadow(0 8px 16px rgba(26, 26, 46, 0.12));
        transition: transform 0.4s ease, filter 0.4s ease;
    }
    
    .card-item:hover .card-image img {
        transform: translateY(-4px) scale(1.05);
        filter: drop-shadow(0 14px 24px rgba(99, 102, 241, 0.25));
    }
    
    .card-body {
        position: relative;
        padding: 16px 18px 18px;
        z-index: 2;
        background: linear-gradient(
            180deg,
            rgba(255, 255, 255, 0.0) 0%,
            rgba(255, 255, 255, 0.4) 100%
        );
    }
    
    .card-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 10px;
        letter-spacing: -0.2px;
    }
    
    .card-btn {
        width: 100%;
        padding: 9px 0;
        background: linear-gradient(135deg, #1a1a2e, #2d2d44);
        border: none;
        border-radius: 10px;
        color: #ffffff;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 4px 12px rgba(26, 26, 46, 0.2);
    }
    
    .card-btn:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        overflow-y: auto;
        padding: 20px;
    }
    
    .modal-overlay.active { display: block !important; }
    
    .modal-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
        width: 100%;
    }
    
    .modal-container {
        position: relative;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        max-width: 880px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow:
            0 25px 60px rgba(15, 23, 42, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        animation: modalSlide 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        margin: auto;
    }
    
    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(-30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .modal-close {
        position: absolute;
        top: 14px; right: 16px;
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 50%;
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.25s;
        z-index: 10;
        font-size: 20px;
        line-height: 1;
    }
    
    .modal-close:hover {
        color: #1a1a2e;
        background: #ffffff;
        transform: rotate(90deg);
    }
    
    .modal-grid {
        display: flex;
        flex-direction: row;
        min-height: 380px;
    }
    
    @media (max-width: 768px) {
        .modal-grid { flex-direction: column; }
    }
    
    .modal-image {
        width: 40%;
        background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
        border-radius: 20px 0 0 20px;
        min-height: 260px;
        position: relative;
        overflow: hidden;
    }
    
    .modal-image::before {
        content: '';
        position: absolute;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.4), transparent 70%);
        top: -60px; right: -60px;
        border-radius: 50%;
    }
    
    @media (max-width: 768px) {
        .modal-image {
            width: 100%;
            border-radius: 20px 20px 0 0;
            min-height: 200px;
        }
    }
    
    .modal-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        opacity: 0.95;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
    }
    
    .modal-form {
        width: 60%;
        padding: 24px 28px;
    }
    
    @media (max-width: 768px) {
        .modal-form { width: 100%; padding: 20px; }
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
    
    .input-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .input-group-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    
    @media (max-width: 480px) {
        .input-group, .input-group-2 {
            grid-template-columns: 1fr;
            gap: 8px;
        }
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
    
    .input-field, .select-field {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 8px;
        font-size: 13px;
        color: #1a1a2e;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        transition: all 0.25s;
        font-family: 'Poppins', sans-serif;
    }
    
    .select-field {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 34px;
    }
    
    .input-field:focus, .select-field:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }
    
    .input-field::placeholder { color: #cbd5e1; }
    
    .btn-primary {
        width: 100%;
        padding: 10px 0;
        background: linear-gradient(135deg, #1a1a2e, #2d2d44);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        margin-top: 4px;
        box-shadow: 0 4px 14px rgba(26, 26, 46, 0.25);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    .result-box {
        background: rgba(248, 250, 252, 0.8);
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        margin-top: 12px;
        display: none;
    }
    
    .result-box.show { display: block; }
    
    .result-item {
        display: flex;
        justify-content: space-between;
        padding: 3px 0;
        font-size: 12px;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .result-item:last-child { border-bottom: none; }
    .result-label { color: #94a3b8; }
    .result-value { font-weight: 500; color: #1a1a2e; }
    .result-title {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 4px;
    }
    
    .modal-footer {
        display: flex;
        gap: 10px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #eef2f6;
    }
    
    .modal-footer .btn-outline {
        flex: 1;
        padding: 8px 0;
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 8px;
        font-size: 12px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.25s;
        font-family: 'Poppins', sans-serif;
    }
    
    .modal-footer .btn-outline:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: rgba(99, 102, 241, 0.06);
    }
    
    /* ===== MODAL PANDUAN ===== */
    .modal-guide-content {
        padding: 0 4px;
    }
    
    .guide-step {
        display: flex;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #eef2f6;
        align-items: flex-start;
    }
    
    .guide-step:last-child {
        border-bottom: none;
    }
    
    .guide-number {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #1a1a2e, #2d2d44);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(26, 26, 46, 0.2);
    }
    
    .guide-text {
        flex: 1;
    }
    
    .guide-text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 4px 0;
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
        gap: 16px;
        margin-top: 8px;
    }
    
    @media (max-width: 600px) {
        .modal-guide-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .guide-tip {
        background: #f8fafc;
        border-radius: 8px;
        padding: 14px 16px;
        border-left: 3px solid #6366f1;
    }
    
    .guide-tip h5 {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 4px 0;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .guide-tip p {
        font-size: 12px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    
    .modal-container::-webkit-scrollbar { width: 5px; }
    .modal-container::-webkit-scrollbar-track { background: transparent; }
    .modal-container::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.4);
        border-radius: 4px;
    }
    
    body.modal-open { overflow: hidden !important; }
</style>

<div class="max-w-7xl mx-auto px-4 py-6 page-enter" id="pageRoot">
    <!-- Header dengan Tombol Panduan -->
    <div class="header-section">
        <div class="header-left">
            <h1 class="page-title">Atap Standar</h1>
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
    <p class="page-subtitle">Pilih model atap untuk menghitung kebutuhan material</p>

    <div class="card-grid">
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-limasan2.png') }}" alt="Limasan">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Limasan</div>
                <button class="card-btn" onclick="openModal('modalLimasan')">Hitung</button>
            </div>
        </div>

        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-pelana2.png') }}" alt="Pelana">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Pelana</div>
                <button class="card-btn" onclick="openModal('modalPelana')">Hitung</button>
            </div>
        </div>

        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-piramid2.png') }}" alt="Limas">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Limas</div>
                <button class="card-btn" onclick="openModal('modalPiramid')">Hitung</button>
            </div>
        </div>

        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-satu-kemiringan2.png') }}" alt="Satu Kemiringan">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Satu Kemiringan</div>
                <button class="card-btn" onclick="openModal('modalSatuKemiringan')">Hitung</button>
            </div>
        </div>

        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-kerucut2.png') }}" alt="Kerucut">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Kerucut</div>
                <button class="card-btn" onclick="openModal('modalKerucut')">Hitung</button>
            </div>
        </div>

        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/iko-atap-dome2.png') }}" alt="Dome">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Dome</div>
                <button class="card-btn" onclick="openModal('modalDome')">Hitung</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PANDUAN PENGGUNA -->
<div class="modal-overlay" id="modalPanduan">
    <div class="modal-wrapper">
        <div class="modal-container" style="max-width: 720px;">
            <button class="modal-close" onclick="closeModal('modalPanduan')">&times;</button>
            
            <div style="padding: 28px 30px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #1a1a2e; margin: 0 0 4px 0; letter-spacing: -0.2px;">
                    📘 Panduan Pengguna
                </h2>
                <p style="font-size: 13px; color: #94a3b8; margin: 0 0 20px 0;">
                    Cara menghitung kebutuhan material atap standar
                </p>
                
                <div class="modal-guide-content">
                    <!-- Langkah 1 -->
                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <div class="guide-text">
                            <h4>Pilih Model Atap</h4>
                            <p>Klik pada tombol <span class="highlight">"Hitung"</span> di bawah gambar model atap yang ingin Anda gunakan.</p>
                            <div class="guide-icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:14px;height:14px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                </svg>
                                <span>Pilih sesuai bentuk atap bangunan Anda</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 2 -->
                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <div class="guide-text">
                            <h4>Masukkan Dimensi</h4>
                            <p>Isi semua ukuran yang diminta seperti <span class="highlight">panjang</span>, <span class="highlight">lebar</span>, dan <span class="highlight">kemiringan</span> atap.</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px;">
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">📏 Panjang (m)</span>
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">📐 Lebar (m)</span>
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">📐 Kemiringan (°)</span>
                            </div>
                        </div>
                    </div>
               
                    
                    <!-- Langkah 3 -->
                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <div class="guide-text">
                            <h4>Klik Hitung & Lihat Hasil</h4>
                            <p>Klik tombol <span class="highlight">"Hitung"</span> untuk melihat hasil perhitungan kebutuhan material.</p>
                            <div style="margin-top: 8px; display: flex; gap: 10px; flex-wrap: wrap;">
                                <span style="background:linear-gradient(135deg,#1a1a2e,#2d2d44);color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Luas Atap</span>
                                <span style="background:linear-gradient(135deg,#1a1a2e,#2d2d44);color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Jumlah Genteng</span>
                                <span style="background:linear-gradient(135deg,#1a1a2e,#2d2d44);color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Kebutuhan Nok</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tips -->
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #eef2f6;">
                    <div class="modal-guide-grid">
                        <div class="guide-tip">
                            <h5>💡 Tips</h5>
                            <p>Tambahkan <strong>5-10%</strong> material cadangan untuk antisipasi pemotongan dan kerusakan.</p>
                        </div>
                        <div class="guide-tip">
                            <h5>📌 Catatan</h5>
                            <p>Pastikan semua ukuran dalam satuan <strong>meter (m)</strong> untuk hasil yang akurat.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Tutup -->
                <div style="margin-top: 20px; text-align: right;">
                    <button onclick="closeModal('modalPanduan')" style="padding:9px 24px;background:linear-gradient(135deg,#1a1a2e,#2d2d44);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;font-family:'Poppins',sans-serif;box-shadow:0 4px 12px rgba(26,26,46,0.2);transition:all 0.3s ease;">
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include All Modals -->
@include('atap-standart.partials.modals.modal-limasan')
@include('atap-standart.partials.modals.modal-pelana')
@include('atap-standart.partials.modals.modal-piramid')
@include('atap-standart.partials.modals.modal-satu-kemiringan')
@include('atap-standart.partials.modals.modal-kerucut')
@include('atap-standart.partials.modals.modal-dome')

<script>
function openModal(modalId) {
    var overlay = document.getElementById(modalId);
    if (overlay) {
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
    }
}

function closeModal(modalId) {
    var overlay = document.getElementById(modalId);
    if (overlay) {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
}

// Close modal when clicking on overlay background
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('modal-overlay')) {
        var overlay = e.target;
        overlay.style.display = 'none';
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        var overlays = document.querySelectorAll('.modal-overlay');
        overlays.forEach(function(overlay) {
            if (overlay.style.display === 'block') {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open');
            }
        });
    }
});

// ===== PAGE ENTRANCE: fade-in halus saat halaman dibuka =====
window.addEventListener('load', function () {
    var root = document.getElementById('pageRoot');
    if (root) {
        requestAnimationFrame(function () {
            root.classList.add('ready');
        });
    }
});

// ===== HANDLE BACK/FORWARD CACHE (bfcache) =====
window.addEventListener('pageshow', function (e) {
    var root = document.getElementById('pageRoot');
    if (root && e.persisted) {
        root.classList.add('ready');
    }
});
</script>
@endsection