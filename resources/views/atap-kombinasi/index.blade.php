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
    
    .card-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    
    @media (max-width: 1200px) {
        .card-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .card-grid {
            grid-template-columns: 1fr;
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
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }
    
    .card-image {
        height: 150px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    
    .card-image img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        opacity: 0.85;
        transition: opacity 0.3s ease;
    }
    
    .card-item:hover .card-image img {
        opacity: 1;
    }
    
    .card-body {
        padding: 12px 16px 14px;
    }
    
    .card-name {
        font-size: 13px;
        font-weight: 500;
        color: #1a1a2e;
        margin-bottom: 8px;
        letter-spacing: -0.2px;
        line-height: 1.3;
    }
    
    .card-btn {
        width: 100%;
        padding: 7px 0;
        background: #f1f4f9;
        border: none;
        border-radius: 6px;
        color: #1a1a2e;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
    }
    
    .card-btn:hover {
        background: #1a1a2e;
        color: #ffffff;
    }
    
    /* MODAL - FIXED CENTER */
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
        padding: 20px;
    }
    
    .modal-overlay.active {
        display: block !important;
    }
    
    .modal-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    
    .modal-container {
        position: relative;
        background: #ffffff;
        border-radius: 16px;
        max-width: 880px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        animation: modalSlide 0.3s ease-out;
        margin: auto;
    }
    
    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
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
        padding: 30px;
        border-radius: 16px 0 0 16px;
        min-height: 260px;
    }
    
    @media (max-width: 768px) {
        .modal-image {
            width: 100%;
            border-radius: 16px 16px 0 0;
            min-height: 200px;
        }
    }
    
    .modal-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        opacity: 0.9;
    }
    
    .modal-form {
        width: 60%;
        padding: 24px 28px;
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
    
    .input-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    
    @media (max-width: 480px) {
        .input-group {
            grid-template-columns: 1fr;
        }
    }
    
    .input-group-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }
    
    @media (max-width: 480px) {
        .input-group-2 {
            grid-template-columns: 1fr;
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
        box-shadow: 0 0 0 3px rgba(26,26,46,0.06);
    }
    
    .input-field::placeholder {
        color: #cbd5e1;
    }
    
    .select-field {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        color: #1a1a2e;
        background: #ffffff;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 32px;
    }
    
    .select-field:focus {
        outline: none;
        border-color: #1a1a2e;
        box-shadow: 0 0 0 3px rgba(26,26,46,0.06);
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
    
    .btn-secondary {
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
        margin-top: 6px;
    }
    
    .btn-secondary:hover {
        background: #2d2d44;
    }
    
    .result-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 14px;
        border: 1px solid #eef2f6;
        margin-top: 12px;
        display: none;
    }
    
    .result-box.show {
        display: block;
    }
    
    .result-item {
        display: flex;
        justify-content: space-between;
        padding: 3px 0;
        font-size: 12px;
        border-bottom: 1px solid #f1f4f9;
    }
    
    .result-item:last-child {
        border-bottom: none;
    }
    
    .result-label {
        color: #94a3b8;
    }
    
    .result-value {
        font-weight: 500;
        color: #1a1a2e;
    }
    
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
        padding: 7px 0;
        background: none;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 12px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Poppins', sans-serif;
    }
    
    .modal-footer .btn-outline:hover {
        border-color: #1a1a2e;
        color: #1a1a2e;
        background: #f8fafc;
    }
    
    /* MODAL PANDUAN */
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
        background: #1a1a2e;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
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
        border-left: 3px solid #1a1a2e;
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
    
    body.modal-open {
        overflow: hidden !important;
    }
</style>

<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header dengan Tombol Panduan -->
    <div class="header-section">
        <div class="header-left">
            <h1 class="page-title">Atap Kombinasi</h1>
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
    <p class="page-subtitle">Pilih model atap kombinasi untuk menghitung kebutuhan material</p>

    <!-- Cards -->
    <div class="card-grid">
        <!-- Limasan + Trapesium -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/trapesium-limasan2.png') }}" alt="Limasan + Trapesium">
            </div>
            <div class="card-body">
                <div class="card-name">Limasan Trapesium</div>
                <button class="card-btn" onclick="openModal('modalLimasanTrapesium')">Hitung</button>
            </div>
        </div>

        <!-- Limas + Pelana -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/limas-pelana.png') }}" alt="Limas + Pelana">
            </div>
            <div class="card-body">
                <div class="card-name">Limasan Pelana</div>
                <button class="card-btn" onclick="openModal('modalLimasPelana')">Hitung</button>
            </div>
        </div>

        <!-- Pelana + 2 Trapesium -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana-2-trapesium.png') }}" alt="Pelana + 2 Trapesium">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana 2 Trapesium</div>
                <button class="card-btn" onclick="openModal('modalPelana2Trapesium')">Hitung</button>
            </div>
        </div>

        <!-- Limasan + Limasan -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/atap-kombinasi-limasan-t.png') }}" alt="Limasan + Limasan">
            </div>
            <div class="card-body">
                <div class="card-name">Double Limasan</div>
                <button class="card-btn" onclick="openModal('modalLimasanLimasan')">Hitung</button>
            </div>
        </div>

        <!-- Pelana X -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana x.png') }}" alt="Pelana X">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana X</div>
                <button class="card-btn" onclick="openModal('modalPelanaX')">Hitung</button>
            </div>
        </div>

        <!-- Limasan X -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/limasan-x.png') }}" alt="Limasan X">
            </div>
            <div class="card-body">
                <div class="card-name">Limasan X</div>
                <button class="card-btn" onclick="openModal('modalLimasanX')">Hitung</button>
            </div>
        </div>

        <!-- Atap Gergaji -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/atap-gergaji.png') }}" alt="Atap Gergaji">
            </div>
            <div class="card-body">
                <div class="card-name">Atap Gergaji</div>
                <button class="card-btn" onclick="openModal('modalGergaji')">Hitung</button>
            </div>
        </div>

        <!-- Pelana 2 Kemiringan -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana 2 kemiringan.png') }}" alt="Pelana 2 Kemiringan">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana 2 Kemiringan</div>
                <button class="card-btn" onclick="openModal('modalPelana2Kemiringan')">Hitung</button>
            </div>
        </div>

        <!-- Lengkung + 2 Sisi -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana-2-sisi-kemiringan.png') }}" alt="Lengkung + 2 Sisi">
            </div>
            <div class="card-body">
                <div class="card-name">Lengkung 2 Sisi Kemiringan</div>
                <button class="card-btn" onclick="openModal('modalLengkung2Sisi')">Hitung</button>
            </div>
        </div>

        <!-- Pelana + 2 Sisi -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana-2-sisi-kemiringan 1.png') }}" alt="Pelana + 2 Sisi">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana 2 Sisi Kemiringan</div>
                <button class="card-btn" onclick="openModal('modalPelana2Sisi')">Hitung</button>
            </div>
        </div>

        <!-- Pelana + Dinding -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana-dinding.png') }}" alt="Pelana + Dinding">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana Dinding</div>
                <button class="card-btn" onclick="openModal('modalPelanaDinding')">Hitung</button>
            </div>
        </div>

        <!-- Pelana 3 Arah -->
        <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/pelana3-arah.png') }}" alt="Pelana 3 Arah">
            </div>
            <div class="card-body">
                <div class="card-name">Pelana 3 Arah</div>
                <button class="card-btn" onclick="openModal('modalPelana3Arah')">Hitung</button>
            </div>
        </div>

        <!-- Trapesium + Pelana 4 Sisi -->
        <!-- <div class="card-item">
            <div class="card-image">
                <img src="{{ asset('images/atap-kombinasi/trapesium-pelana.png') }}" alt="Trapesium + Pelana">
            </div>
            <div class="card-body">
                <div class="card-name">Trapesium Pelana</div>
                <button class="card-btn" onclick="openModal('modalTrapesiumPelana4Sisi')">Hitung</button>
            </div>
        </div> -->
        <!-- Card: Atap Trapesium Kotak -->
<!-- <div class="card-item">
    <div class="card-image">
        <img src="{{ asset('images/atap-kombinasi/trapesium-kotak.png') }}" alt="Trapesium Kotak">
    </div>
    <div class="card-body">
        <div class="card-name">Trapesium Kotak</div>
        <button class="card-btn" onclick="openModal('modalTrapesiumKotak')">Hitung</button>
    </div>
</div> -->
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
                    Cara menghitung kebutuhan material atap kombinasi
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
                            <h4>Pilih Jenis Material</h4>
                            <p>Pilih material atap yang akan digunakan dari pilihan yang tersedia.</p>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px;">
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">Genteng Metal</span>
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">Genteng Keramik</span>
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">Asbes Gelombang</span>
                                <span style="background:#f1f4f9;padding:2px 10px;border-radius:4px;font-size:11px;color:#1a1a2e;">Seng Gelombang</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Langkah 4 -->
                    <div class="guide-step">
                        <div class="guide-number">4</div>
                        <div class="guide-text">
                            <h4>Klik Hitung & Lihat Hasil</h4>
                            <p>Klik tombol <span class="highlight">"Hitung"</span> untuk melihat hasil perhitungan kebutuhan material.</p>
                            <div style="margin-top: 8px; display: flex; gap: 10px; flex-wrap: wrap;">
                                <span style="background:#1a1a2e;color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Luas Atap</span>
                                <span style="background:#1a1a2e;color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Jumlah Genteng</span>
                                <span style="background:#1a1a2e;color:#fff;padding:4px 12px;border-radius:4px;font-size:11px;font-weight:500;">✓ Kebutuhan Nok</span>
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
                    <button onclick="closeModal('modalPanduan')" style="padding:8px 24px;background:#1a1a2e;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;font-family:'Poppins',sans-serif;">
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include All Modals -->
@include('atap-kombinasi.partials.modals.modal-limasan-trapesium')
@include('atap-kombinasi.partials.modals.modal-limas-pelana')
@include('atap-kombinasi.partials.modals.modal-pelana-2trapesium')
@include('atap-kombinasi.partials.modals.modal-limasan-limasan')
@include('atap-kombinasi.partials.modals.modal-pelana-x')
@include('atap-kombinasi.partials.modals.modal-limasan-x')
@include('atap-kombinasi.partials.modals.modal-atap-gergaji')
@include('atap-kombinasi.partials.modals.modal-pelana-2-kemiringan')
@include('atap-kombinasi.partials.modals.modal-atap-lengkung-2sisi')
@include('atap-kombinasi.partials.modals.modal-pelana-2-sisi')
@include('atap-kombinasi.partials.modals.modal-pelana-dinding')
@include('atap-kombinasi.partials.modals.modal-pelana-3-arah')
<!-- @include('atap-kombinasi.partials.modals.modal-trapesium-pelana-4-sisi') -->
<!-- @include('atap-kombinasi.partials.modals.modal-trapesium-kotak') -->

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
</script>
@endsection