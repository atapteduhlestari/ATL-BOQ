<!-- Modal Atap Pelana 3 Arah dengan Three.js 3D -->
<style>
    #modalPelana3Arah::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalPelana3Arah {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana3Arah .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalPelana3Arah .overflow-y-auto::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    .modal-content-scroll::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    .modal-content-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    #modelContainerPelana3Arah {
        width: 100%;
        height: 100%;
        min-height: 280px;
        position: relative;
        overflow: hidden;
        background: #1a1a2e;
        border-radius: 12px 0 0 12px;
    }
    
    @media (max-width: 768px) {
        #modelContainerPelana3Arah {
            border-radius: 12px 12px 0 0;
            min-height: 220px;
        }
    }
    
    #modelContainerPelana3Arah canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }
    
    .model-loading-pelana {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
        z-index: 10;
    }
    
    .model-loading-pelana .spinner {
        display: inline-block;
        width: 30px;
        height: 30px;
        border: 3px solid rgba(255,255,255,0.1);
        border-radius: 50%;
        border-top-color: #6366f1;
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 10px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Load Three.js dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<div id="modalPelana3Arah" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalPelana3Arah')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalPelana3Arah')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: 3D Model Container -->
                <div class="md:w-2/5 relative rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
                    <div id="modelContainerPelana3Arah" class="h-64 md:h-full min-h-[280px]">
                        <div class="model-loading-pelana" id="modelLoadingPelana3Arah">
                            <div class="spinner"></div>
                            <div>Memuat model 3D...</div>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 text-white pointer-events-none">
                        <h4 class="text-lg font-medium">Pelana 3 Arah</h4>
                        <p class="text-xs text-gray-300">Kombinasi atap pelana dengan 3 arah kemiringan</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Pelana 3 Arah</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">

                     <div class="bg-gray-50 border-l-2 border-gray-400 rounded-lg p-3">
                        <p class="text-xs text-gray-600 font-medium">Cara menghitung :</p>
                        <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                            <div>Bagi bidang menjadi 2 bagian:</div>
                            <div class="pl-2">• <strong>Bagian Depan</strong> = Pelana</div>
                            <div class="pl-2">• <strong>Bagian Belakang</strong> = Pelana</div>
                        </div>
                    </div>
                    
                    <!-- Bagian 1: Sisi Depan -->
                    <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-sm font-medium text-gray-700">Bagian Depan</span>
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Pelana</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" id="pelana3arah_panjang_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0" oninput="updateModelPelana3Arah()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" id="pelana3arah_lebar_a" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0" oninput="updateModelPelana3Arah()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" id="pelana3arah_sudut_a" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" oninput="updateModelPelana3Arah()">
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Sisi Belakang -->
                    <div class="border rounded-lg p-4 bg-gray-50/50 border-gray-200">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-sm font-medium text-gray-700">Bagian Belakang</span>
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Pelana</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" id="pelana3arah_panjang_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0" oninput="updateModelPelana3Arah()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" id="pelana3arah_lebar_b" step="0.1" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" placeholder="0" oninput="updateModelPelana3Arah()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" id="pelana3arah_sudut_b" step="1" min="1" max="89" value="30" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white" oninput="updateModelPelana3Arah()">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Hitung -->
                    <button type="button" onclick="hitungPelana3Arah()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                        Hitung Luas & Estimasi
                    </button>

                    <!-- Hasil Perhitungan -->
                    <div id="hasilPerhitunganPelana3Arah" class="hidden">
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                            <div class="flex justify-between items-center text-sm font-medium text-gray-700">
                                <span>Total Keseluruhan</span>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                <span class="text-gray-500">Luas Atap</span>
                                <span id="totalLuasPelana3Arah" class="font-medium text-gray-900">- m²</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Panjang Starter</span>
                                <span id="totalStarterPelana3Arah" class="font-medium text-gray-900">- m</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500" id="labelNokJuraiPelana3Arah">Panjang Nok & Jurai</span>
                                <span id="totalNokJuraiPelana3Arah" class="font-medium text-gray-900">- m</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Panjang Flashing</span>
                                <span id="totalFlashingPelana3Arah" class="font-medium text-gray-900">- m</span>
                            </div>
                        </div>
                        <div id="detailBagianPelana3Arah" class="mt-3 space-y-2"></div>
                    </div>

                    <!-- Pilih Brand untuk BOQ -->
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">
                            Pilih Brand
                        </label>
                        <select id="brand_boq_pelana3arah" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands ?? [] as $brand)
                                <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                            @endforeach
                        </select>
                        
                        <button onclick="lanjutKeBOQPelana3Arah()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                            Lanjut ke BOQ
                        </button>
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button onclick="closeModal('modalPelana3Arah')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                    <button onclick="resetPelana3Arah()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== THREE.JS 3D MODEL ====================
var scenePelana, cameraPelana, rendererPelana, controlsPelana, modelGroupPelana;
var isModelLoadedPelana = false;

function initThreeJSPelana3Arah() {
    var container = document.getElementById('modelContainerPelana3Arah');
    if (!container) return;
    
    // Scene
    scenePelana = new THREE.Scene();
    scenePelana.background = new THREE.Color(0x1a1a2e);
    
    // Camera
    var aspect = container.clientWidth / container.clientHeight;
    cameraPelana = new THREE.PerspectiveCamera(40, aspect, 0.1, 100);
    cameraPelana.position.set(5, 3, 6);
    cameraPelana.lookAt(0, 0, 0);
    
    // Renderer
    rendererPelana = new THREE.WebGLRenderer({ antialias: true });
    rendererPelana.setSize(container.clientWidth, container.clientHeight);
    rendererPelana.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    rendererPelana.shadowMap.enabled = true;
    rendererPelana.shadowMap.type = THREE.PCFSoftShadowMap;
    rendererPelana.toneMapping = THREE.ACESFilmicToneMapping;
    rendererPelana.toneMappingExposure = 1.2;
    container.appendChild(rendererPelana.domElement);
    
    // Controls
    controlsPelana = new THREE.OrbitControls(cameraPelana, rendererPelana.domElement);
    controlsPelana.enableDamping = true;
    controlsPelana.dampingFactor = 0.08;
    controlsPelana.autoRotate = true;
    controlsPelana.autoRotateSpeed = 1.5;
    controlsPelana.minDistance = 2;
    controlsPelana.maxDistance = 15;
    controlsPelana.target.set(0, 0.5, 0);
    
    // Lights
    var ambientLight = new THREE.AmbientLight(0x404060, 0.5);
    scenePelana.add(ambientLight);
    
    var hemisphereLight = new THREE.HemisphereLight(0x8888ff, 0x444422, 0.6);
    scenePelana.add(hemisphereLight);
    
    var directionalLight = new THREE.DirectionalLight(0xffffff, 1.5);
    directionalLight.position.set(5, 10, 7);
    directionalLight.castShadow = true;
    scenePelana.add(directionalLight);
    
    var fillLight = new THREE.DirectionalLight(0xffeedd, 0.5);
    fillLight.position.set(-3, 2, -4);
    scenePelana.add(fillLight);
    
    // Ground plane
    var groundGeometry = new THREE.PlaneGeometry(10, 10);
    var groundMaterial = new THREE.ShadowMaterial({ opacity: 0.3 });
    var ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -0.5;
    ground.receiveShadow = true;
    scenePelana.add(ground);
    
    // Model group
    modelGroupPelana = new THREE.Group();
    scenePelana.add(modelGroupPelana);
    
    // Load GLB model
    loadGLBModelPelana();
    
    // Handle resize
    window.addEventListener('resize', onResizePelana);
    
    // Start animation
    animatePelana();
}

function loadGLBModelPelana() {
    var loader = new THREE.GLTFLoader();
    var loadingEl = document.getElementById('modelLoadingPelana3Arah');
    
    var modelPath = '{{ asset("images/atap-kombinasi/pelana-3-arah.glb") }}';
    
    loader.load(
        modelPath,
        function(gltf) {
            var model = gltf.scene;
            model.scale.set(1.5, 1.5, 1.5);
            model.position.y = 0;
            model.castShadow = true;
            model.traverse(function(node) {
                if (node.isMesh) {
                    node.castShadow = true;
                    node.receiveShadow = true;
                }
            });
            modelGroupPelana.add(model);
            isModelLoadedPelana = true;
            if (loadingEl) loadingEl.style.display = 'none';
            console.log('Model Pelana 3 Arah loaded successfully');
        },
        function(xhr) {
            var progress = (xhr.loaded / xhr.total * 100);
            if (loadingEl) {
                loadingEl.innerHTML = `<div class="spinner"></div><div>Memuat model... ${Math.round(progress)}%</div>`;
            }
        },
        function(error) {
            console.error('Error loading model:', error);
            if (loadingEl) {
                loadingEl.innerHTML = `
                    <div style="color: #94a3b8; font-size: 12px;">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div class="text-xs text-gray-400 mt-2">Model 3D tidak tersedia</div>
                        <div class="text-xs text-gray-500 mt-1">Gunakan gambar referensi</div>
                    </div>
                `;
            }
            showFallbackImagePelana();
        }
    );
}

function showFallbackImagePelana() {
    var container = document.getElementById('modelContainerPelana3Arah');
    if (container) {
        var img = document.createElement('img');
        img.src = '{{ asset("images/atap-kombinasi/atap-pelana-3-arah.png") }}';
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'contain';
        img.style.opacity = '0.8';
        img.style.position = 'absolute';
        img.style.top = '0';
        img.style.left = '0';
        img.style.padding = '20px';
        container.appendChild(img);
    }
}

function updateModelPelana3Arah() {
    var panjangA = parseFloat(document.getElementById('pelana3arah_panjang_a').value) || 5;
    var lebarA = parseFloat(document.getElementById('pelana3arah_lebar_a').value) || 4;
    var sudutA = parseFloat(document.getElementById('pelana3arah_sudut_a').value) || 30;
    var panjangB = parseFloat(document.getElementById('pelana3arah_panjang_b').value) || 5;
    var lebarB = parseFloat(document.getElementById('pelana3arah_lebar_b').value) || 4;
    var sudutB = parseFloat(document.getElementById('pelana3arah_sudut_b').value) || 30;
    
    if (isModelLoadedPelana && modelGroupPelana) {
        var scaleX = Math.min(Math.max(panjangA, panjangB) / 5, 2);
        var scaleZ = Math.min(Math.max(lebarA, lebarB) / 4, 2);
        var scale = Math.min(scaleX, scaleZ);
        modelGroupPelana.scale.set(scale, scale, scale);
    }
}

function onResizePelana() {
    var container = document.getElementById('modelContainerPelana3Arah');
    if (!container || !rendererPelana) return;
    var width = container.clientWidth;
    var height = container.clientHeight;
    rendererPelana.setSize(width, height);
    cameraPelana.aspect = width / height;
    cameraPelana.updateProjectionMatrix();
}

function animatePelana() {
    requestAnimationFrame(animatePelana);
    if (controlsPelana) {
        controlsPelana.update();
    }
    if (rendererPelana && scenePelana && cameraPelana) {
        rendererPelana.render(scenePelana, cameraPelana);
    }
}

// ==================== FUNGSI LAINNYA ====================
let hasilPelana3Arah = null;

function hitungPelana3Arah() {
    // Ambil brand dari select BOQ
    let brandSelect = document.getElementById('brand_boq_pelana3arah');
    let brand = brandSelect ? brandSelect.value : 'iko';
    
    let panjangA = parseFloat(document.getElementById('pelana3arah_panjang_a').value) || 0;
    let lebarA = parseFloat(document.getElementById('pelana3arah_lebar_a').value) || 0;
    let sudutA = parseFloat(document.getElementById('pelana3arah_sudut_a').value) || 0;
    let panjangB = parseFloat(document.getElementById('pelana3arah_panjang_b').value) || 0;
    let lebarB = parseFloat(document.getElementById('pelana3arah_lebar_b').value) || 0;
    let sudutB = parseFloat(document.getElementById('pelana3arah_sudut_b').value) || 0;
    
    if (panjangA <= 0 || lebarA <= 0 || sudutA <= 0 ||
        panjangB <= 0 || lebarB <= 0 || sudutB <= 0) {
        alert('Isi semua field dengan nilai > 0!');
        return;
    }
    
    let data = {
        jenis_kombinasi: 'pelana_3_arah',
        brand: brand,
        panjang_a: panjangA,
        lebar_a: lebarA,
        sudut_a: sudutA,
        panjang_b: panjangB,
        lebar_b: lebarB,
        sudut_b: sudutB,
        panjang_c: panjangB,
        lebar_c: lebarB,
        sudut_c: sudutB
    };
    
    let btn = event.target;
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    let url;
    if (brand === 'palmex') {
        url = '{{ route("palmex.kombinasi.hitung") }}';
    } else {
        url = '{{ route("atap-kombinasi.hitung") }}';
    }
    
    fetch(url, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            hasilPelana3Arah = resData;
            let total = resData.total;
            
            document.getElementById('totalLuasPelana3Arah').innerHTML = total.luas_atap + ' m²';
            document.getElementById('totalStarterPelana3Arah').innerHTML = total.panjang_starter + ' m';
            document.getElementById('totalFlashingPelana3Arah').innerHTML = total.panjang_flashing + ' m';
            
            // ===== TAMPILKAN NOK & JURAI =====
            let labelElement = document.getElementById('labelNokJuraiPelana3Arah');
            let valueElement = document.getElementById('totalNokJuraiPelana3Arah');
            
            if (brand === 'palmex') {
                if (labelElement) labelElement.textContent = 'Panjang Jurai & Nok Atas';
                if (valueElement) {
                    valueElement.innerHTML = 
                        'Jurai: ' + total.panjang_jurai + ' m | Nok Atas: ' + total.panjang_nok_atas + ' m';
                }
            } else {
                if (labelElement) labelElement.textContent = 'Panjang Nok & Jurai';
                if (valueElement) {
                    valueElement.innerHTML = total.panjang_nok_jurai + ' m';
                }
            }
            
            // ===== DETAIL PER BAGIAN =====
            let detailHtml = '<div class="text-xs font-medium text-gray-600 mb-1">Detail Per Bagian</div>';
            
            let detailsToShow = resData.details.slice(0, 2);
            detailsToShow.forEach(item => {
                detailHtml += `<div class="bg-white border border-gray-200 rounded-lg p-2 text-xs">
                    <div class="font-medium text-gray-800">${item.bagian}</div>
                    <div class="grid grid-cols-2 gap-1 mt-1 text-gray-500">`;
                
                if (brand === 'palmex') {
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Jurai: ${item.jurai} m</div>
                        <div>Nok Atas: ${item.nok_atas} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                    `;
                } else {
                    detailHtml += `
                        <div>Luas: ${item.luas_atap} m²</div>
                        <div>Starter: ${item.starter} m</div>
                        <div>Nok & Jurai: ${item.nok_jurai} m</div>
                        <div>Flashing: ${item.flashing} m</div>
                    `;
                }
                
                detailHtml += `</div></div>`;
            });
            
            document.getElementById('detailBagianPelana3Arah').innerHTML = detailHtml;
            document.getElementById('hasilPerhitunganPelana3Arah').classList.remove('hidden');
        } else {
            alert('Error: ' + (resData.message || 'Gagal hitung'));
        }
    })
    .catch(err => { 
        console.error(err); 
        alert('Terjadi kesalahan server'); 
    })
    .finally(() => { 
        btn.innerHTML = originalText; 
        btn.disabled = false; 
    });
}

function resetPelana3Arah() {
    document.getElementById('pelana3arah_panjang_a').value = '';
    document.getElementById('pelana3arah_lebar_a').value = '';
    document.getElementById('pelana3arah_sudut_a').value = '30';
    document.getElementById('pelana3arah_panjang_b').value = '';
    document.getElementById('pelana3arah_lebar_b').value = '';
    document.getElementById('pelana3arah_sudut_b').value = '30';
    document.getElementById('hasilPerhitunganPelana3Arah').classList.add('hidden');
    hasilPelana3Arah = null;
    if (modelGroupPelana) {
        modelGroupPelana.scale.set(1, 1, 1);
    }
}

function lanjutKeBOQPelana3Arah() {
    let selectEl = document.getElementById('brand_boq_pelana3arah');
    let brandSlug = selectEl ? selectEl.value : '';
    
    if (!brandSlug || brandSlug === '') {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    if (!hasilPelana3Arah) {
        alert('Hitung luas atap terlebih dahulu!');
        return;
    }
    
    let d = hasilPelana3Arah.details;
    let total = hasilPelana3Arah.total;
    
    // MAPPING URL - TAMBAHKAN TAPE ROOF
    const controllerMap = {
        'iko-atap': '/boq/atap-kombinasi/pelana-3-arah',
        'skyshield': '/boq/atap-kombinasi-skyshield/pelana-3-arah',
        'palmex': '/boq/palmex/atap-kombinasi/pelana-3-arah',
        'tape-roof': '/boq/taperoof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'mahaflat': '/boq/mahaflat/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'flexi-roof': '/boq/flexiroof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'eco-roof': '/boq/ecoroof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'emarin-roof': '/boq/emarinroof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'master-roof': '/boq/masterroof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'maha-roof': '/boq/maharoof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'mahaspan-roof': '/boq/mahaspanroof/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
        'flexideck-seam': '/boq/flexideckseam/atap-kombinasi/pelana-3-arah',  // <-- TAMBAHKAN
    };
    
    let baseUrl = controllerMap[brandSlug] || '/boq/atap-kombinasi/pelana-3-arah';
    let url = `${baseUrl}?brand_slug=${brandSlug}`;
    
    // ===== BAGIAN 1 (DEPAN) =====
    url += `&luas_atap_1=${d[0]?.luas_atap||0}`;
    url += `&starter_1=${d[0]?.starter||0}`;
    url += `&flashing_1=${d[0]?.flashing||0}`;
    url += `&sudut_1=${document.getElementById('pelana3arah_sudut_a').value}`;
    url += `&panjang_a=${document.getElementById('pelana3arah_panjang_a').value}`;
    url += `&lebar_a=${document.getElementById('pelana3arah_lebar_a').value}`;
    
    // ===== BAGIAN 2 (BELAKANG) =====
    url += `&luas_atap_2=${d[1]?.luas_atap||0}`;
    url += `&starter_2=${d[1]?.starter||0}`;
    url += `&flashing_2=${d[1]?.flashing||0}`;
    url += `&sudut_2=${document.getElementById('pelana3arah_sudut_b').value}`;
    url += `&panjang_b=${document.getElementById('pelana3arah_panjang_b').value}`;
    url += `&lebar_b=${document.getElementById('pelana3arah_lebar_b').value}`;
    
    // ===== BEDAKAN BRAND =====
    if (brandSlug === 'palmex') {
        url += `&jurai_1=0&nok_atas_1=${d[0]?.nok_atas||0}`;
        url += `&jurai_2=0&nok_atas_2=${d[1]?.nok_atas||0}`;
        url += `&total_jurai=${total?.panjang_jurai||0}`;
        url += `&total_nok_atas=${total?.panjang_nok_atas||0}`;
    } else if (brandSlug === 'tape-roof') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
    } else if (brandSlug === 'mahaflat') {
        // TAPE ROOF: pakai total nok_jurai (digabung)
        url += `&total_nok_jurai=${total?.panjang_nok_jurai||0}`;
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
    }
     else {
        // IKO/SKYSHIELD
        url += `&nok_1=${d[0]?.nok_jurai||0}`;
        url += `&nok_2=${d[1]?.nok_jurai||0}`;
    }
    
    console.log('Final URL:', url);
    window.location.href = url;
}
// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        initThreeJSPelana3Arah();
    }, 500);
});

var modalObserverPelana = new MutationObserver(function() {
    var modal = document.getElementById('modalPelana3Arah');
    if (modal && !modal.classList.contains('hidden')) {
        setTimeout(function() {
            if (!rendererPelana) {
                initThreeJSPelana3Arah();
            } else {
                onResizePelana();
            }
        }, 300);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalPelana3Arah');
    if (modal) {
        modalObserverPelana.observe(modal, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>