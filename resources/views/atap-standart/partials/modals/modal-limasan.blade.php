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
    
    #modelContainer {
        width: 100%;
        height: 100%;
        min-height: 280px;
        position: relative;
        overflow: hidden;
        background: #1a1a2e;
        border-radius: 12px 0 0 12px;
    }
    
    @media (max-width: 768px) {
        #modelContainer {
            border-radius: 12px 12px 0 0;
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
</style>

<!-- Load Three.js dari CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<div id="modalLimasan" class="fixed inset-0 z-50 hidden overflow-y-auto modal-content-scroll">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" onclick="closeModal('modalLimasan')"></div>
        
        <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-auto transition-all max-h-[90vh] overflow-y-auto modal-content-scroll">
            <button onclick="closeModal('modalLimasan')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row">
                <!-- Kolom Kiri: 3D Model Container -->
                <div class="md:w-2/5 relative rounded-t-xl md:rounded-l-xl md:rounded-tr-none overflow-hidden">
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
                <div class="md:w-3/5 p-6">
                    <div class="mb-5">
                        <h3 class="text-base font-medium text-gray-900">Perhitungan Atap Limasan</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan ukuran untuk estimasi material</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Panjang</label>
                                <input type="number" 
                                       id="panjang_limasan" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0"
                                       oninput="updateModel3D()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Lebar</label>
                                <input type="number" 
                                       id="lebar_limasan" 
                                       step="0.1"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="0"
                                       oninput="updateModel3D()">
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Kemiringan</label>
                                <input type="number" 
                                       id="sudut_limasan" 
                                       step="1"
                                       min="1"
                                       max="89"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white"
                                       placeholder="30"
                                       oninput="updateModel3D()">
                            </div>
                        </div>

                        <button type="button" onclick="hitungAtapLimasan()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium transition-all">
                            Hitung Luas & Estimasi
                        </button>

                        <div id="hasilPerhitunganLimasan" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-gray-200">
                                <div class="flex justify-between items-center text-sm font-medium text-gray-700">
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

                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <label class="block text-[10px] font-medium text-gray-500 uppercase tracking-wide mb-1.5">Pilih Brand</label>
                            <select id="brand_boq" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-400 focus:border-gray-400 transition-all bg-white cursor-pointer">
                                <option value="">-- Pilih Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}">{{ $brand->nama_brand }}</option>
                                @endforeach
                            </select>
                            
                            <button onclick="lanjutKeBOQ()" class="w-full bg-gray-900 hover:bg-gray-800 text-white py-2.5 rounded-lg text-sm font-medium mt-3 transition-all">
                                Lanjut ke BOQ
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button onclick="closeModal('modalLimasan')" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Tutup</button>
                        <button onclick="resetFormLimasan()" class="flex-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-all">Reset</button>
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
    
    // Coba load dari path yang benar
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