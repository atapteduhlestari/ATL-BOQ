<!-- Modal Atap Limasan dengan Three.js 3D -->
<style>
    #modalLimasan::-webkit-scrollbar {
        width: 0px;
        height: 0px;
    }
    #modalLimasan {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasan .overflow-y-auto {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modalLimasan .overflow-y-auto::-webkit-scrollbar {
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
    
    /* ===== 3D MODEL CONTAINER ===== */
    #modelContainer {
        width: 100%;
        height: 100%;
        min-height: 280px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
        border-radius: 20px 0 0 20px;
    }
    
    @media (max-width: 768px) {
        #modelContainer {
            border-radius: 20px 20px 0 0;
            min-height: 220px;
        }
    }
    
    #modelContainer canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }
    
    .model-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
        z-index: 10;
    }
    
    .model-loading .spinner {
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
    
    /* ===== FLOATING GLASS MODAL OVERRIDE ===== */
    #modalLimasan .glass-modal-container {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow:
            0 25px 60px rgba(15, 23, 42, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        animation: modalSlide 0.35s cubic-bezier(0.25, 1, 0.5, 1);
    }
    
    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(-30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    /* Overlay pakai glass blur */
    #modalLimasan .modal-overlay-glass {
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    /* Close button bulat */
    #modalLimasan .glass-close {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.25s;
        z-index: 10;
    }
    
    #modalLimasan .glass-close:hover {
        color: #1a1a2e;
        background: #ffffff;
        transform: rotate(90deg);
    }
    
    /* Input field glass */
    #modalLimasan .glass-input {
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
    
    #modalLimasan .glass-input:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }
    
    #modalLimasan .glass-input::placeholder {
        color: #cbd5e1;
    }
    
    #modalLimasan .glass-select {
        width: 100%;
        padding: 8px 34px 8px 12px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 8px;
        font-size: 13px;
        color: #1a1a2e;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        transition: all 0.25s;
        font-family: 'Poppins', sans-serif;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        cursor: pointer;
    }
    
    #modalLimasan .glass-select:focus {
        outline: none;
        border-color: #6366f1;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
    }
    
    /* Tombol utama gradient */
    #modalLimasan .glass-btn-primary {
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
        box-shadow: 0 4px 14px rgba(26, 26, 46, 0.25);
    }
    
    #modalLimasan .glass-btn-primary:hover {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    #modalLimasan .glass-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Tombol outline */
    #modalLimasan .glass-btn-outline {
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
    
    #modalLimasan .glass-btn-outline:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: rgba(99, 102, 241, 0.06);
    }
    
    /* Result box */
    #modalLimasan .glass-result-box {
        background: rgba(248, 250, 252, 0.8);
        border-radius: 10px;
        padding: 12px 14px;
        border: 1px solid rgba(226, 232, 240, 0.9);
    }
    
    #modalLimasan .glass-divider {
        border-top: 1px solid rgba(226, 232, 240, 0.9);
    }
    
    #modalLimasan .glass-label {
        display: block;
        font-size: 10px;
        font-weight: 500;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }
</style>

<!-- Load Three.js dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<div id="modalLimasan" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <!-- Overlay glass -->
        <div class="fixed inset-0 modal-overlay-glass transition-opacity" onclick="closeModal('modalLimasan')"></div>
        
        <div class="relative glass-modal-container max-w-4xl w-full mx-auto max-h-[90vh] overflow-y-auto modal-content-scroll">
            <!-- Close button bulat -->
            <button onclick="closeModal('modalLimasan')" class="glass-close absolute top-4 right-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: 3D Model Container -->
                <div class="md:w-2/5 relative rounded-t-[20px] md:rounded-l-[20px] md:rounded-tr-none overflow-hidden">
                    <div id="modelContainer" class="h-64 md:h-full min-h-[200px]">
                        <div class="model-loading" id="modelLoading">
                            <div class="spinner"></div>
                            <div>Memuat model 3D...</div>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5 text-white pointer-events-none">
                        <h4 class="text-lg font-medium">Atap Limasan</h4>
                        <p class="text-xs text-gray-300">Model atap limasan tradisional</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Perhitungan -->
                <div class="md:w-3/5 p-6" style="font-family: 'Poppins', sans-serif;">
                    <div class="mb-5">
                        <h3 class="text-base font-semibold text-gray-900">Perhitungan Atap Limasan</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="glass-label">Panjang</label>
                                <input type="number" 
                                       id="panjang_limasan" 
                                       step="0.1"
                                       class="glass-input"
                                       placeholder="0"
                                       oninput="updateModel3D()">
                            </div>
                            <div>
                                <label class="glass-label">Lebar</label>
                                <input type="number" 
                                       id="lebar_limasan" 
                                       step="0.1"
                                       class="glass-input"
                                       placeholder="0"
                                       oninput="updateModel3D()">
                            </div>
                            <div>
                                <label class="glass-label">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_limasan" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       class="glass-input"
                                       placeholder="30"
                                       oninput="updateModel3D()">
                            </div>
                        </div>

                        <button type="button" onclick="hitungAtapLimasan()" class="glass-btn-primary">
                            Hitung Luas & Estimasi
                        </button>

                        <div id="hasilPerhitunganLimasan" class="hidden">
                            <div class="glass-result-box space-y-2">
                                <div class="flex justify-between items-center text-sm font-semibold text-gray-800">
                                    <span>Total Keseluruhan</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pt-2 border-t border-gray-200">
                                    <span class="text-gray-500">Luas Atap</span>
                                    <span id="luasAtap" class="font-medium text-gray-900">- m²</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Panjang Sisi Miring</span>
                                    <span id="panjangSisiMiring" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Keliling / Starter</span>
                                    <span id="starting" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Nok & Jurai</span>
                                    <span id="nokJurai" class="font-medium text-gray-900">- m</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Flashing</span>
                                    <span id="flashing" class="font-medium text-gray-900">- m</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 glass-divider">
                            <label class="glass-label">Pilih Brand</label>
                            <select id="brand_boq" class="glass-select">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQ()" class="glass-btn-primary" style="margin-top: 12px;">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 glass-divider">
                        <button onclick="closeModal('modalLimasan')" class="glass-btn-outline">Tutup</button>
                        <button onclick="resetFormLimasan()" class="glass-btn-outline">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ==================== THREE.JS 3D MODEL ====================
var scene, camera, renderer, controls, modelGroup;
var isModelLoaded = false;

function initThreeJS() {
    var container = document.getElementById('modelContainer');
    if (!container) return;
    
    // Scene
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x1a1a2e);
    
    // Camera
    var aspect = container.clientWidth / container.clientHeight;
    camera = new THREE.PerspectiveCamera(40, aspect, 0.1, 100);
    camera.position.set(5, 3, 6);
    camera.lookAt(0, 0, 0);
    
    // Renderer
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;
    container.appendChild(renderer.domElement);
    
    // Controls
    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.08;
    controls.autoRotate = true;
    controls.autoRotateSpeed = 1.5;
    controls.minDistance = 2;
    controls.maxDistance = 15;
    controls.target.set(0, 0.5, 0);
    
    // Lights
    var ambientLight = new THREE.AmbientLight(0x404060, 0.5);
    scene.add(ambientLight);
    
    var hemisphereLight = new THREE.HemisphereLight(0x8888ff, 0x444422, 0.6);
    scene.add(hemisphereLight);
    
    var directionalLight = new THREE.DirectionalLight(0xffffff, 1.5);
    directionalLight.position.set(5, 10, 7);
    directionalLight.castShadow = true;
    directionalLight.shadow.mapSize.width = 1024;
    directionalLight.shadow.mapSize.height = 1024;
    scene.add(directionalLight);
    
    var fillLight = new THREE.DirectionalLight(0xffeedd, 0.5);
    fillLight.position.set(-3, 2, -4);
    scene.add(fillLight);
    
    // Ground plane (invisible)
    var groundGeometry = new THREE.PlaneGeometry(10, 10);
    var groundMaterial = new THREE.ShadowMaterial({ opacity: 0.3 });
    var ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.rotation.x = -Math.PI / 2;
    ground.position.y = -0.5;
    ground.receiveShadow = true;
    scene.add(ground);
    
    // Model group
    modelGroup = new THREE.Group();
    scene.add(modelGroup);
    
    // Load GLB model
    loadGLBModel();
    
    // Handle resize
    window.addEventListener('resize', onResize);
    
    // Start animation
    animate();
}

function loadGLBModel() {
    var loader = new THREE.GLTFLoader();
    var loadingEl = document.getElementById('modelLoading');
    
    var modelPath = '{{ asset("images/atap-limasan.glb") }}';
    
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
            modelGroup.add(model);
            isModelLoaded = true;
            if (loadingEl) loadingEl.style.display = 'none';
            console.log('Model 3D loaded successfully');
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
            showFallbackImage();
        }
    );
}

function showFallbackImage() {
    var container = document.getElementById('modelContainer');
    if (container) {
        var img = document.createElement('img');
        img.src = '{{ asset("images/iko-atap-limasan2.png") }}';
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

function updateModel3D() {
    var panjang = parseFloat(document.getElementById('panjang_limasan').value) || 5;
    var lebar = parseFloat(document.getElementById('lebar_limasan').value) || 4;
    var sudut = parseFloat(document.getElementById('sudut_limasan').value) || 30;
    
    if (isModelLoaded && modelGroup) {
        var scaleX = Math.min(panjang / 5, 2);
        var scaleZ = Math.min(lebar / 4, 2);
        var scale = Math.min(scaleX, scaleZ);
        modelGroup.scale.set(scale, scale, scale);
    }
}

function onResize() {
    var container = document.getElementById('modelContainer');
    if (!container || !renderer) return;
    var width = container.clientWidth;
    var height = container.clientHeight;
    renderer.setSize(width, height);
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
}

function animate() {
    requestAnimationFrame(animate);
    if (controls) {
        controls.update();
    }
    if (renderer && scene && camera) {
        renderer.render(scene, camera);
    }
}

function hitungAtapLimasan() {
    let panjang = document.getElementById('panjang_limasan').value;
    let lebar = document.getElementById('lebar_limasan').value;
    let sudut = document.getElementById('sudut_limasan').value;
    
    if (!panjang || !lebar || !sudut) {
        alert('Isi semua field terlebih dahulu!');
        return;
    }
    
    let p = parseFloat(panjang);
    let l = parseFloat(lebar);
    let s = parseFloat(sudut);
    
    if (p <= 0 || l <= 0) {
        alert('Panjang dan lebar harus lebih dari 0!');
        return;
    }
    
    if (s <= 0 || s >= 90) {
        alert('Sudut kemiringan harus antara 1° - 89°!');
        return;
    }
    
    let btn = event.target;
    if (btn.tagName !== 'BUTTON') {
        btn = btn.closest('button');
    }
    let originalText = btn.innerHTML;
    btn.innerHTML = 'Menghitung...';
    btn.disabled = true;
    
    let brandSlug = document.getElementById('brand_boq')?.value || '';
    let url = '/atap-standar/hitung';
    let requestData = {};
    
    if (brandSlug === 'palmex') {
        url = '/atap-standar/hitung-palmex-limasan';
        requestData = {
            panjang: p,
            lebar: l,
            sudut: s
        };
    } else {
        requestData = {
            jenis_atap: 1,
            panjang: p,
            lebar: l,
            sudut: s
        };
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('luasAtap').innerHTML = data.luas_atap + ' m²';
            document.getElementById('panjangSisiMiring').innerHTML = data.panjang_sisi_miring + ' m';
            document.getElementById('starting').innerHTML = data.starting + ' m';
            document.getElementById('flashing').innerHTML = data.flashing + ' m';
            
            if (data.panjang_nok !== undefined && data.panjang_jurai !== undefined) {
                document.getElementById('nokJurai').innerHTML = 'Nok: ' + data.panjang_nok + ' m | Jurai: ' + data.panjang_jurai + ' m';
                window.hasilPerhitunganLimasan = {
                    luas_atap: data.luas_atap,
                    starting: data.starting,
                    panjang_nok: data.panjang_nok,
                    panjang_jurai: data.panjang_jurai,
                    flashing: data.flashing,
                    sudut: s
                };
            } else {
                document.getElementById('nokJurai').innerHTML = data.nok_jurai + ' m';
                window.hasilPerhitunganLimasan = {
                    luas_atap: data.luas_atap,
                    starting: data.starting,
                    nok_jurai: data.nok_jurai,
                    flashing: data.flashing,
                    sudut: s
                };
            }
            
            document.getElementById('hasilPerhitunganLimasan').classList.remove('hidden');
        } else {
            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan pada server: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function resetFormLimasan() {
    document.getElementById('panjang_limasan').value = '';
    document.getElementById('lebar_limasan').value = '';
    document.getElementById('sudut_limasan').value = '';
    document.getElementById('hasilPerhitunganLimasan').classList.add('hidden');
    if (modelGroup) {
        modelGroup.scale.set(1, 1, 1);
    }
}

function lanjutKeBOQ() {
    let brandSlug = document.getElementById('brand_boq').value;
    
    if (!brandSlug) {
        alert('Pilih brand terlebih dahulu!');
        return;
    }
    
    let hasil = window.hasilPerhitunganLimasan || {};
    let luasAtap = hasil.luas_atap || 0;
    let sudut = hasil.sudut || 0;
    let panjangStarter = hasil.starting || 0;
    let panjangFlashing = hasil.flashing || 0;
    let panjangNokJurai = hasil.panjang_nok || hasil.nok_jurai || 0;
    let panjangJurai = hasil.panjang_jurai || 0;
    
    const controllerMap = {
        'iko-atap': '/boq/iko-atap',
        'skyshield': '/boq/skyshield',
        'iko-insulasi': '/boq/iko-insulasi',
        'palmex': '/boq/palmex/limasan',
        'tape-roof': '/boq/taperoof/limasan',
        'mahaflat': '/boq/mahaflat/limasan',
        'flexi-roof': '/boq/flexiroof/limasan',
        'eco-roof': '/boq/ecoroof/limasan',
        'emarin-roof': '/boq/emarin/limasan',
        'master-roof': '/boq/masterroof/limasan',
        'maha-roof': '/boq/maharoof/limasan',
        'mahaspan-roof': '/boq/mahaspanroof/limasan',
        'flexideck-seam': '/boq/flexideckseam/limasan',
    };
    
    let url = controllerMap[brandSlug] || `/boq/${brandSlug}`;
    
    if (brandSlug === 'iko-insulasi') {
        window.location.href = `${url}?luas=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    } else if (brandSlug === 'palmex') {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok=${panjangNokJurai}&panjang_jurai=${panjangJurai}&panjang_flashing=${panjangFlashing}`;
    } else {
        window.location.href = `${url}?luas_atap=${luasAtap}&sudut=${sudut}&panjang_starter=${panjangStarter}&panjang_nok_jurai=${panjangNokJurai}&panjang_flashing=${panjangFlashing}`;
    }
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        initThreeJS();
    }, 500);
});

var modalObserver = new MutationObserver(function() {
    var modal = document.getElementById('modalLimasan');
    if (modal && !modal.classList.contains('hidden')) {
        setTimeout(function() {
            if (!renderer) {
                initThreeJS();
            } else {
                onResize();
            }
        }, 300);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalLimasan');
    if (modal) {
        modalObserver.observe(modal, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>