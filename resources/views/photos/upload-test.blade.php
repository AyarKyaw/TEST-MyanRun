<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyanRun OCR & Face Search Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
    <style>
        .pan-zoom-img {
            transition: transform 0.1s ease-out;
            cursor: zoom-in;
        }
        .pan-zoom-img.dragging {
            transition: none;
            cursor: grabbing;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased py-10 px-4">
    
    <div class="max-w-6xl mx-auto space-y-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 flex flex-col md:flex-row gap-6 justify-between items-center">
            <div class="w-full md:max-w-xl space-y-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
                        <span>🏃‍♂️</span> MyanRun Smart Search View
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Search runner photos globally using either their race day BIB number code or dynamic facial recognition.
                    </p>
                </div>

                <form id="searchForm" class="space-y-3">
                    @csrf
                    
                    <div class="flex gap-2 border-b border-slate-200 pb-2 text-sm">
                        <button type="button" id="tabBib" class="font-bold text-emerald-600 border-b-2 border-emerald-600 pb-1 px-2 focus:outline-none cursor-pointer">BIB Code Search</button>
                        <button type="button" id="tabFace" class="font-medium text-slate-500 hover:text-slate-800 pb-1 px-2 focus:outline-none cursor-pointer flex items-center gap-1">
                            Facial Recognition <span id="modelStatusBadge" class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded-md font-semibold">Loading AI Models...</span>
                        </button>
                    </div>

                    <div id="bibInputWrapper" class="flex gap-2">
                        <input type="text" name="bib_number" id="bibNumberInput" placeholder="e.g., M11069, F10708" 
                            class="flex-1 bg-slate-50 border border-slate-300 rounded-lg px-4 py-2.5 text-base font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition">
                    </div>

                    <div id="faceInputWrapper" class="hidden flex flex-col gap-3 bg-slate-50 border border-slate-200 p-4 rounded-xl">
                        <div class="flex justify-between items-center">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Reference Runner Face Selfie</label>
                            <div class="flex gap-2">
                                <button type="button" id="toggleUploadBtn" class="text-xs bg-emerald-700 text-white font-semibold px-2 py-1 rounded-md cursor-pointer hidden">Use Upload</button>
                                <button type="button" id="toggleCameraBtn" class="text-xs bg-blue-600 text-white font-semibold px-2 py-1 rounded-md cursor-pointer">Use Camera Mode</button>
                            </div>
                        </div>

                        <div id="fileSourceBox" class="flex items-center gap-4">
                            <input type="file" id="faceFileInput" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>

                        <div id="cameraSourceBox" class="hidden flex flex-col items-center gap-2">
                            <div class="relative w-full max-w-sm aspect-video bg-black rounded-lg overflow-hidden border border-slate-300">
                                <video id="webcamStream" autoplay playsinline class="w-full h-full object-cover scale-x-[-1]"></video>
                            </div>
                            <button type="button" id="captureSnapshotBtn" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-md shadow-xs transition cursor-pointer">
                                📸 Capture Photo
                            </button>
                        </div>

                        <div id="previewStatusRow" class="hidden flex items-center gap-4 border-t border-slate-200 pt-3 mt-1">
                            <img id="facePreview" src="" class="w-14 h-14 object-cover rounded-full border border-slate-300 shadow-xs">
                            <div>
                                <p class="text-xs font-bold text-slate-700" id="previewMetaTitle">Selfie Profile Loaded</p>
                                <p class="text-[11px] text-slate-400">Ready to compare matrix matching maps.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition shadow-sm cursor-pointer whitespace-nowrap text-center block">
                        Find Matches Matrix
                    </button>
                </form>
            </div>

            <div class="w-full md:w-auto bg-slate-50 rounded-xl p-4 border border-slate-200 text-center md:text-left space-y-2 max-w-sm">
                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                    Need to sync or regenerate your local search storage cache matrix rules map?
                </p>
                <a href="{{ url('admin/photos/compile-index') }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-md transition shadow-sm">
                    🔄 Compile OCR Index JSON
                </a>
            </div>
        </div>

        <div id="loadingState" class="hidden flex flex-col items-center justify-center py-12 space-y-3">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-slate-200 border-t-emerald-600"></div>
            <p id="loadingText" class="text-sm font-medium text-slate-500">Scanning indexed folder files...</p>
        </div>

        <div id="resultsWrapper" class="space-y-12">
            <div id="exactSection" class="hidden space-y-4">
                <div class="border-b border-slate-200 pb-2 flex items-center gap-2">
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full" id="exactBadge">0</span>
                    <h3 id="exactHeading" class="text-lg font-bold text-slate-900">Exact Matches</h3>
                </div>
                <div id="exactGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
            </div>

            <div id="fuzzySection" class="hidden space-y-4">
                <div class="border-b border-slate-200 pb-2 flex items-center gap-2">
                    <span class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-800 text-xs font-bold rounded-full" id="fuzzyBadge">0</span>
                    <h3 id="fuzzyHeading" class="text-lg font-bold text-slate-900">Similar Alternatives (OCR Variance)</h3>
                </div>
                <div id="fuzzyGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
            </div>

            <div id="emptyState" class="bg-white rounded-2xl border border-dashed border-slate-300 py-16 px-4 text-center text-slate-400">
                <span class="text-4xl block mb-2">🖼️</span>
                <p class="font-medium text-slate-600">No photos loaded yet.</p>
                <p class="text-xs mt-1">Submit a query target or image input above to stream your photography array layout views.</p>
            </div>
        </div>

    </div>

    <div id="previewModal" class="hidden fixed inset-0 z-50 bg-slate-900/90 backdrop-blur-sm flex flex-col items-center justify-between p-4 transition-opacity duration-300 opacity-0 select-none">
        <div class="w-full flex justify-between items-center z-10 max-w-6xl mx-auto px-2">
            <div class="bg-slate-800/80 backdrop-blur-md text-white px-4 py-2 rounded-xl border border-slate-700 flex items-center gap-3 shadow-lg text-xs md:text-sm">
                <span id="modalFilename" class="font-semibold text-slate-300 truncate max-w-[180px] md:max-w-sm"></span>
                <div id="modalBadges" class="flex gap-2"></div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="hidden md:inline-block text-[11px] text-slate-400 bg-slate-800/50 px-2.5 py-1.5 rounded-lg border border-slate-700/50">
                    💡 Scroll Wheel to Zoom • Drag to Move
                </span>
                <button id="closeModalBtn" class="bg-white/10 hover:bg-white/25 text-white w-9 h-9 flex items-center justify-center rounded-xl transition cursor-pointer text-sm font-bold shadow-lg">
                    ✕
                </button>
            </div>
        </div>
        
        <div class="w-full flex-1 flex items-center justify-center overflow-hidden relative my-4" id="viewportWrapper">
            <div id="imageCoordinateContainer" class="relative max-h-[78vh] max-w-full">
                <img id="modalImage" src="" alt="Preview Target" 
                     class="max-h-[78vh] max-w-full rounded-lg object-contain shadow-2xl border border-white/5 pan-zoom-img select-none"
                     draggable="false">
            </div>
        </div>
        <div class="h-6 w-full pointer-events-none"></div>
    </div>

    <script>
    let currentMode = 'BIB'; 
    let modelsLoaded = false;
    let targetFaceDescriptor = null;
    let currentSelectedBox = null; 
    let activeWebcamStream = null;

    const tabBib = document.getElementById('tabBib');
    const tabFace = document.getElementById('tabFace');
    const bibWrapper = document.getElementById('bibInputWrapper');
    const faceWrapper = document.getElementById('faceInputWrapper');
    const bibInput = document.getElementById('bibNumberInput');
    const badge = document.getElementById('modelStatusBadge');

    const fileSourceBox = document.getElementById('fileSourceBox');
    const cameraSourceBox = document.getElementById('cameraSourceBox');
    const toggleCameraBtn = document.getElementById('toggleCameraBtn');
    const toggleUploadBtn = document.getElementById('toggleUploadBtn');
    const videoElement = document.getElementById('webcamStream');
    const previewStatusRow = document.getElementById('previewStatusRow');

    tabBib.addEventListener('click', () => {
        currentMode = 'BIB';
        tabBib.className = "font-bold text-emerald-600 border-b-2 border-emerald-600 pb-1 px-2 focus:outline-none cursor-pointer";
        tabFace.className = "font-medium text-slate-500 hover:text-slate-800 pb-1 px-2 focus:outline-none cursor-pointer flex items-center gap-1";
        bibWrapper.classList.remove('hidden');
        faceWrapper.classList.add('hidden');
        bibInput.required = true;
        disableWebcam();
    });

    tabFace.addEventListener('click', () => {
        currentMode = 'FACE';
        tabFace.className = "font-bold text-emerald-600 border-b-2 border-emerald-600 pb-1 px-2 focus:outline-none cursor-pointer flex items-center gap-1";
        tabBib.className = "font-medium text-slate-500 hover:text-slate-800 pb-1 px-2 focus:outline-none cursor-pointer";
        faceWrapper.classList.remove('hidden');
        bibWrapper.classList.add('hidden');
        bibInput.required = false;
        initFaceApiModels();
    });

    async function initFaceApiModels() {
        if (modelsLoaded) return;
        try {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            
            modelsLoaded = true;
            badge.innerText = "Engine Active";
            badge.className = "text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded-md font-semibold";
        } catch (err) {
            console.error("AI Weights Resolution Error:", err);
            badge.innerText = "Initialization Failed";
            badge.className = "text-[10px] bg-rose-100 text-rose-800 px-1.5 py-0.5 rounded-md font-semibold";
        }
    }

    toggleCameraBtn.addEventListener('click', async () => {
        fileSourceBox.classList.add('hidden');
        cameraSourceBox.classList.remove('hidden');
        toggleCameraBtn.classList.add('hidden');
        toggleUploadBtn.classList.remove('hidden');
        
        try {
            activeWebcamStream = await navigator.mediaDevices.getUserMedia({
                video: { width: 640, height: 480, facingMode: "user" },
                audio: false
            });
            videoElement.srcObject = activeWebcamStream;
        } catch (cameraErr) {
            console.error("Camera acquisition exception:", cameraErr);
            alert("Could not pull video track feed. Ensure browser has camera context permissions enabled.");
            switchToUploadView();
        }
    });

    toggleUploadBtn.addEventListener('click', switchToUploadView);

    function switchToUploadView() {
        disableWebcam();
        fileSourceBox.classList.remove('hidden');
        cameraSourceBox.classList.add('hidden');
        toggleCameraBtn.classList.remove('hidden');
        toggleUploadBtn.classList.add('hidden');
    }

    function disableWebcam() {
        if (activeWebcamStream) {
            activeWebcamStream.getTracks().forEach(track => track.stop());
            activeWebcamStream = null;
        }
        videoElement.srcObject = null;
    }

    document.getElementById('captureSnapshotBtn').addEventListener('click', async () => {
        if (!videoElement.srcObject) return;

        const canvas = document.createElement('canvas');
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;
        const ctx = canvas.getContext('2d');
        
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
        
        const blobDataUrl = canvas.toDataURL('image/jpeg');
        document.getElementById('facePreview').src = blobDataUrl;
        previewStatusRow.classList.remove('hidden');
        document.getElementById('previewMetaTitle').innerText = "Live Snapshot Captured";

        const rawHTMLImageInstance = new Image();
        rawHTMLImageInstance.src = blobDataUrl;
        rawHTMLImageInstance.onload = () => evaluateFaceProperties(rawHTMLImageInstance, "WebcamCapture.jpg");
    });

    document.getElementById('faceFileInput').addEventListener('change', async (e) => {
        const file = e.target.value ? e.target.files[0] : null;
        if (!file) return;

        const preview = document.getElementById('facePreview');
        preview.src = URL.createObjectURL(file);
        previewStatusRow.classList.remove('hidden');
        document.getElementById('previewMetaTitle').innerText = file.name;

        if (!modelsLoaded) await initFaceApiModels();
        
        const img = await faceapi.bufferToImage(file);
        evaluateFaceProperties(img, file.name);
    });

    async function evaluateFaceProperties(imageDOMContext, fallbackName) {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('loadingText').innerText = "Analyzing facial structures & landmarks...";

        try {
            if (!modelsLoaded) await initFaceApiModels();
            const detection = await faceapi.detectSingleFace(imageDOMContext).withFaceLandmarks().withFaceDescriptor();
            document.getElementById('loadingState').classList.add('hidden');

            if (detection) {
                targetFaceDescriptor = Array.from(detection.descriptor); 
                badge.innerText = "Signatures Encoded";
                badge.className = "text-[10px] bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded-md font-semibold";

                console.log("=== TARGET FACE-API DATA FOR: " + fallbackName + " ===");
                console.log("Detection Score:", detection.detection.score);
            } else {
                alert("Could not extract clean facial properties. Ensure your face is fully clear and properly lit.");
                targetFaceDescriptor = null;
                badge.innerText = "Extraction Failed";
                badge.className = "text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded-md font-semibold";
            }
        } catch (err) {
            document.getElementById('loadingState').classList.add('hidden');
            console.error(err);
            alert("Error parsing tracking vector calculations.");
        }
    }

    document.getElementById("searchForm").addEventListener("submit", function(e) {
        e.preventDefault();
        if (currentMode === 'FACE' && !targetFaceDescriptor) {
            alert("Please supply or re-upload a clear baseline photo asset first.");
            return;
        }

        document.getElementById("loadingState").classList.remove("hidden");
        document.getElementById("emptyState").classList.add("hidden");
        document.getElementById("exactSection").classList.add("hidden");
        document.getElementById("fuzzySection").classList.add("hidden");
        document.getElementById("exactGrid").innerHTML = "";
        document.getElementById("fuzzyGrid").innerHTML = "";

        if (currentMode === 'BIB') {
            document.getElementById('loadingText').innerText = "Scanning index for OCR records...";
            executeBibSearch(new FormData(this));
        } else {
            document.getElementById('loadingText').innerText = "Streaming matrices to server matrix validator...";
            executeFaceSearch();
        }
    });

    async function executeBibSearch(formData) {
        const loading = document.getElementById("loadingState");
        const empty = document.getElementById("emptyState");
        const exactSection = document.getElementById("exactSection");
        const exactGrid = document.getElementById("exactGrid");
        const exactBadge = document.getElementById("exactBadge");
        const fuzzySection = document.getElementById("fuzzySection");
        const fuzzyGrid = document.getElementById("fuzzyGrid");
        const fuzzyBadge = document.getElementById("fuzzyBadge");

        document.getElementById('exactHeading').innerText = "Exact Matches";
        const queryBib = formData.get('bib_number').trim().toUpperCase();

        try {
            const response = await fetch('/storage/S3/ocr_index.json');
            if (!response.ok) throw new Error("Index file lookup error.");
            
            const ocrDatabase = await response.json(); 
            let exactMatches = [];
            let fuzzyMatches = [];
            const hostUrl = window.location.protocol + "//" + window.location.host + "/storage/S3/";

            for (let [filename, bibArray] of Object.entries(ocrDatabase)) {
                const normalizedArray = Array.isArray(bibArray) ? bibArray.map(b => String(b).toUpperCase()) : [];
                if (normalizedArray.includes(queryBib)) {
                    exactMatches.push({ url: hostUrl + filename, filename: filename });
                } else {
                    const partialHit = normalizedArray.some(bib => bib.includes(queryBib) || queryBib.includes(bib));
                    if (partialHit) {
                        fuzzyMatches.push({ url: hostUrl + filename, filename: filename });
                    }
                }
            }

            loading.classList.add("hidden");

            if (exactMatches.length === 0 && fuzzyMatches.length === 0) {
                empty.classList.remove("hidden");
                empty.querySelector("p").innerText = `No photos found matching BIB code "${queryBib}".`;
                return;
            }

            if (exactMatches.length > 0) {
                exactBadge.innerText = exactMatches.length;
                exactSection.classList.remove("hidden");
                exactMatches.forEach(photo => {
                    exactGrid.appendChild(createImageCard(photo.url, photo.filename, "EXACT", null, null));
                });
            }

            if (fuzzyMatches.length > 0) {
                fuzzyBadge.innerText = fuzzyMatches.length;
                fuzzySection.classList.remove("hidden");
                fuzzyMatches.forEach(photo => {
                    fuzzyGrid.appendChild(createImageCard(photo.url, photo.filename, "FUZZY VARIANCE", null, null));
                });
            }
        } catch (err) {
            loading.classList.add("hidden");
            empty.classList.remove("hidden");
            alert("Error locating local storage database indexes.");
            console.error(err);
        }
    }

    async function executeFaceSearch() {
        const loading = document.getElementById("loadingState");
        const empty = document.getElementById("emptyState");
        const exactSection = document.getElementById("exactSection");
        const exactGrid = document.getElementById("exactGrid");
        const exactBadge = document.getElementById("exactBadge");
        
        // Reset layout containers
        exactGrid.innerHTML = "";
        document.getElementById('exactHeading').innerText = "Facial Match Results";

        try {
            const tokenElement = document.querySelector('input[name="_token"]');
            const csrfToken = tokenElement ? tokenElement.value : '';

            const response = await fetch("{{ url('admin/photos/upload-test') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    face_embedding: targetFaceDescriptor
                })
            });

            if (!response.ok) throw new Error(`Server returned HTTP ${response.status}`);
            
            const data = await response.json();
            loading.classList.add("hidden");

            if (data.success) {
                exactSection.classList.remove("hidden");
                
                // 🎯 COSINE SIMILARITY THRESHOLD:
                // Since your backend uses Cosine, closer to 1.0 is a match.
                // 0.70 to 0.72 is a standard cutoff for face-api.js embeddings.
                const COSINE_THRESHOLD = 0.72; 
                let structuralMatchesFound = 0;

                // 1. Sort Descending: Highest similarity (best match) down to lowest similarity
                const sortedPhotos = data.matched_photos.sort((a, b) => {
                    return (b.similarity || 0) - (a.similarity || 0);
                });

                // 2. Loop and filter matches based on Cosine Similarity
                sortedPhotos.forEach(photo => {
                    const similarityScore = photo.similarity ?? 0;
                    
                    // Only let strong similarities pass through (filters out the wrong images)
                    if (similarityScore >= COSINE_THRESHOLD) {
                        structuralMatchesFound++;
                        
                        // Convert similarity directly into a clear display percentage (e.g., 0.8245 -> 82%)
                        const confidencePercentage = Math.round(similarityScore * 100);
                        
                        exactGrid.appendChild(
                            createImageCard(
                                photo.url, 
                                photo.filename, 
                                `Match: ${confidencePercentage}%`, 
                                `Score: ${similarityScore.toFixed(4)}`, 
                                photo.box
                            )
                        );
                    }
                });

                if (structuralMatchesFound === 0) {
                    empty.classList.remove("hidden");
                    exactSection.classList.add("hidden");
                    empty.querySelector("p").innerText = `No runner photos matched your baseline profile within the acceptable similarity threshold (>= ${COSINE_THRESHOLD}).`;
                } else {
                    exactBadge.innerText = structuralMatchesFound;
                }
            } else {
                empty.classList.remove("hidden");
                empty.querySelector("p").innerText = data.message || "No matches discovered in photo collections.";
            }
        } catch (err) {
            loading.classList.add("hidden");
            empty.classList.remove("hidden");
            alert("Transmission failure or internal processing mismatch.");
            console.error("AJAX Vector Sync Error Debugger Logs:", err);
        }
    }

    function createImageCard(url, filename, matchedText, scoreMetric = null, box = null) {
        const card = document.createElement("div");
        card.className = "group bg-white rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between cursor-pointer";

        let badgeHtml = `<span class="bg-slate-900 text-white text-[11px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">${matchedText}</span>`;
        if (scoreMetric !== null) {
            badgeHtml += ` <span class="bg-amber-100 text-amber-800 text-[10px] font-medium px-1.5 py-0.5 rounded-md">Sim: ${scoreMetric}</span>`;
        }

        card.innerHTML = `
            <div class="relative aspect-[3/2] bg-slate-100 overflow-hidden">
                <img src="${url}" alt="${filename}" class="object-cover w-full h-full group-hover:scale-105 transition duration-300" loading="lazy">
            </div>
            <div class="p-3 bg-white border-t border-slate-100 flex flex-wrap gap-1.5 items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 truncate max-w-[110px]">${filename}</span>
                <div class="flex items-center gap-1">
                    ${badgeHtml}
                </div>
            </div>
        `;

        card.addEventListener("click", () => openPreviewModal(url, filename, badgeHtml, box));
        return card;
    }

    const modal = document.getElementById("previewModal");
    const modalImg = document.getElementById("modalImage");
    const modalFilename = document.getElementById("modalFilename");
    const modalBadges = document.getElementById("modalBadges");
    const viewport = document.getElementById("viewportWrapper");
    const coordContainer = document.getElementById("imageCoordinateContainer");

    let scale = 1, pointX = 0, pointY = 0, startX = 0, startY = 0, isDragging = false;

    function updateImageTransform() {
        modalImg.style.transform = `translate(${pointX}px, ${pointY}px) scale(${scale})`;
        const activeBox = document.getElementById("faceHighlightOverlayBox");
        if (activeBox) {
            activeBox.style.transform = `translate(${pointX}px, ${pointY}px) scale(${scale})`;
        }
    }

    function resetZoomParameters() {
        scale = 1; pointX = 0; pointY = 0; isDragging = false;
        modalImg.classList.remove("dragging");
        updateImageTransform();
        modalImg.style.cursor = "zoom-in";
    }

    function drawBoundingOverlayFrame() {
        const oldBox = document.getElementById("faceHighlightOverlayBox");
        if (oldBox) oldBox.remove();

        if (!currentSelectedBox) return;

        const naturalWidth = modalImg.naturalWidth;
        const naturalHeight = modalImg.naturalHeight;
        const displayedWidth = modalImg.clientWidth;
        const displayedHeight = modalImg.clientHeight;

        if (!naturalWidth || !displayedWidth) return;

        const scaleX = displayedWidth / naturalWidth;
        const scaleY = displayedHeight / naturalHeight;

        const boxLeft = (currentSelectedBox.x ?? currentSelectedBox._x ?? 0) * scaleX;
        const boxTop = (currentSelectedBox.y ?? currentSelectedBox._y ?? 0) * scaleY;
        const boxWidth = (currentSelectedBox.width ?? currentSelectedBox._width ?? 0) * scaleX;
        const boxHeight = (currentSelectedBox.height ?? currentSelectedBox._height ?? 0) * scaleY;

        const overlayBox = document.createElement("div");
        overlayBox.id = "faceHighlightOverlayBox";
        
        overlayBox.style.position = "absolute";
        overlayBox.style.left = `${boxLeft}px`;
        overlayBox.style.top = `${boxTop}px`;
        overlayBox.style.width = `${boxWidth}px`;
        overlayBox.style.height = `${boxHeight}px`;
        overlayBox.style.border = "3px solid #10b981"; 
        overlayBox.style.boxShadow = "0 0 12px #10b981, inset 0 0 8px rgba(16, 185, 129, 0.3)";
        overlayBox.style.borderRadius = "6px";
        overlayBox.style.pointerEvents = "none";
        overlayBox.style.transformOrigin = `${-boxLeft}px ${-boxTop}px`; 
        
        coordContainer.appendChild(overlayBox);
        overlayBox.style.transform = `translate(${pointX}px, ${pointY}px) scale(${scale})`;
    }

    function openPreviewModal(url, filename, badgeHtml, box = null) {
        currentSelectedBox = box;
        modalImg.src = url;
        modalFilename.innerText = filename;
        modalBadges.innerHTML = badgeHtml;
        resetZoomParameters();
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            modal.classList.add("opacity-100");
        }, 10);
        document.body.classList.add("overflow-hidden");
    }

    modalImg.addEventListener('load', () => {
        drawBoundingOverlayFrame();
    });

    window.addEventListener('resize', () => {
        if (!modal.classList.contains('hidden')) {
            drawBoundingOverlayFrame();
        }
    });

    function closePreviewModal() {
        modal.classList.remove("opacity-100");
        modal.classList.add("opacity-0");
        setTimeout(() => { 
            modal.classList.add("hidden"); 
            const oldBox = document.getElementById("faceHighlightOverlayBox");
            if (oldBox) oldBox.remove();
        }, 300);
        document.body.classList.remove("overflow-hidden");
    }

    viewport.addEventListener("wheel", (e) => {
        e.preventDefault();
        const zoomFactor = 0.25;
        scale = e.deltaY < 0 ? scale + zoomFactor : scale - zoomFactor;
        scale = Math.min(Math.max(0.8, scale), 6);

        if (scale <= 1) {
            pointX = 0; pointY = 0;
            modalImg.style.cursor = "zoom-in";
        } else {
            modalImg.style.cursor = "grab";
        }
        updateImageTransform();
    }, { passive: false });

    modalImg.addEventListener("mousedown", (e) => {
        if (scale <= 1) return;
        e.preventDefault();
        isDragging = true;
        modalImg.classList.add("dragging");
        modalImg.style.cursor = "grabbing";
        startX = e.clientX - pointX;
        startY = e.clientY - pointY;
    });

    window.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        pointX = e.clientX - startX;
        pointY = e.clientY - startY;
        updateImageTransform();
    });

    window.addEventListener("mouseup", () => {
        if (!isDragging) return;
        isDragging = false;
        modalImg.classList.remove("dragging");
        modalImg.style.cursor = scale > 1 ? "grab" : "zoom-in";
    });

    document.getElementById("closeModalBtn").addEventListener("click", closePreviewModal);
    
    modal.addEventListener("click", (e) => {
        if (e.target === modal || e.target === viewport || e.target === coordContainer) closePreviewModal();
    });
    
    document.getElementById("imageCoordinateContainer").addEventListener("click", (e) => {
        if (e.target === coordContainer) closePreviewModal();
    });
    
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !modal.classList.contains("hidden")) closePreviewModal();
    });
</script>
</body>
</html>