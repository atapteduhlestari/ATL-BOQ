@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    
    .card {
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
    }
    
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -6px rgba(59, 130, 246, 0.12);
        border-color: #3b82f6;
    }
    
    .btn-hitung {
        background: #1e293b;
        transition: all 0.3s ease;
        font-size: 12px;
        font-weight: 500;
        padding: 10px 0;
        border-radius: 10px;
    }
    
    .btn-hitung:hover {
        background: #3b82f6;
        transform: scale(1.01);
    }
    
    .card-title {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        letter-spacing: -0.2px;
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    
    .stat-box {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
    }
</style>

    
    
    <!-- Cards Grid -->
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <!-- Card: Jendela Mati 1 Kaca -->
<div class="card bg-white rounded-xl overflow-hidden">
    <div class="h-36 bg-teal-50 flex items-center justify-center p-4">
        <img src="{{ asset('images/jendela/jendela-mati-1-kaca.png') }}" 
             alt="Jendela Mati 1 Kaca" 
             class="max-h-full object-contain">
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="card-title">Jendela Mati 1 Kaca</h3>
            <span class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full">Fixed</span>
        </div>
        <p class="text-xs text-gray-500 mb-3">Jendela tetap / non-opening, 1 panel kaca</p>
        <button onclick="openModal('modalJendelaMati1Kaca')" 
                class="btn-hitung w-full text-white transition-all duration-300">
            Hitung Kebutuhan →
        </button>
    </div>
</div>
            
        

        </div>
    </div>

@include('jendela.partials.modals.modal-jendela-mati-1-kaca')

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="modal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            });
        }
    });
</script>
@endsection