<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyanRun · Face API Matrix Compiler Engine</title>
    
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; margin: 0; }
        .container { max-width: 750px; margin: 50px auto; background: #1e293b; border-radius: 12px; padding: 35px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3); border: 1px solid #334155; }
        h2 { color: #38bdf8; margin-top: 0; font-size: 24px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        p { color: #94a3b8; font-size: 15px; line-height: 1.6; }
        .stats-bar { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 25px 0; background: #111827; padding: 15px; border-radius: 8px; border: 1px solid #1e293b; }
        .stat-card { text-align: center; }
        .stat-val { font-size: 20px; font-weight: bold; color: #f1f5f9; }
        .stat-lbl { font-size: 12px; color: #64748b; text-transform: uppercase; margin-top: 4px; }
        button { background: #0284c7; color: #ffffff; border: none; padding: 14px 28px; width: 100%; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.2s ease; }
        button:hover { background: #0369a1; }
        button:disabled { background: #334155; color: #64748b; cursor: not-allowed; }
        #console-log { margin-top: 25px; background: #090d16; color: #34d399; padding: 20px; border-radius: 6px; max-height: 350px; overflow-y: auto; font-family: "SFMono-Regular", Consolas, monospace; font-size: 13px; line-height: 1.7; border: 1px solid #1e293b; }
        .text-yellow { color: #f59e0b; }
        .text-blue { color: #38bdf8; }
    </style>
</head>
<body>

<div class="container">
    <h2>📸 Face-API.js Index Sync Compiler</h2>
    <p>This automated layout utility processes race asset image metrics right inside your browser window context, generating perfect 128-dimension deep network embeddings that sync seamlessly with your frontend runner identification cards layout views.</p>
    
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-val" id="total-photos">{{ count($photoList) }}</div>
            <div class="stat-lbl">Queued Runner Photos</div>
        </div>
        <div class="stat-card">
            <div class="stat-val" id="processed-count">0</div>
            <div class="stat-lbl">Processed Matrix Status</div>
        </div>
    </div>

    <button id="engineControlBtn" onclick="runClientSideCompiler()">⚡ Compile Search Matrix File</button>
    
    <div id="console-log">> System initialized. Ready to execute local array compilation loop...</div>
</div>

<script>
// Inject your raw php collection array securely directly into client side script configuration
const filesToProcess = @json($photoList);
const logContainer = document.getElementById('console-log');
const counterView = document.getElementById('processed-count');
const controlBtn = document.getElementById('engineControlBtn');

// Set up globally unified Axios CSRF headers for safe Laravel POST validation checks
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function logger(message, classHighlight = '') {
    const timestamp = new Date().toLocaleTimeString();
    logContainer.innerHTML += `<br><span class="${classHighlight}">[${timestamp}] > ${message}</span>`;
    logContainer.scrollTop = logContainer.scrollHeight;
}

async function runClientSideCompiler() {
    if (filesToProcess.length === 0) {
        logger('❌ Error: No image photo assets discovered inside public/storage/S3 directory path alignment.', 'text-yellow');
        return;
    }

    controlBtn.disabled = true;
    const finalCalculatedMatrix = {};
    const assetBaseUrl = '/storage/S3/';
    
    try {
        logger('🔄 Fetching neural network weights directly from verified mirror configurations...');
        const MODEL_WEIGHTS_CDN = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights';
        
        await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_WEIGHTS_CDN);
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_WEIGHTS_CDN);
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_WEIGHTS_CDN);
        logger('✅ Neural tracking models cleanly structured inside browser engine cache.', 'text-blue');

        let trackIndex = 0;

        for (const filename of filesToProcess) {
            trackIndex++;
            counterView.innerText = trackIndex;
            logger(`Parsing Asset (${trackIndex}/${filesToProcess.length}): ${filename}...`);

            try {
                // Read and download local asset stream straight into target memory context
                const imageDOM = await faceapi.fetchImage(`${assetBaseUrl}${filename}`);
                
                // Track landmarks and calculate normalized face descriptor vector array
                const structuralDetections = await faceapi.detectAllFaces(imageDOM)
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                if (structuralDetections.length > 0) {
                    finalCalculatedMatrix[filename] = structuralDetections.map(match => ({
                        box: {
                            _x: Math.round(match.detection.box.x),
                            _y: Math.round(match.detection.box.y),
                            _width: Math.round(match.detection.box.width),
                            _height: Math.round(match.detection.box.height)
                        },
                        score: parseFloat(match.detection.score.toFixed(2)),
                        embedding: Array.from(match.descriptor) // Generates perfectly scaled L2 decimals
                    }));
                    logger(`   Found ${structuralDetections.length} face signatures inside photo.`, 'text-blue');
                } else {
                    logger(`   Skipped: No legible coordinates matched profiles.`);
                }
            } catch (fileErr) {
                logger(`   ⚠️ Error reading tracking frames for [${filename}]: ${fileErr.message}`, 'text-yellow');
            }
        }

        logger('📤 Transmitting final compiled array matrix mapping up to Laravel server droplet...');
        
        const backendEndpoint = "{{ route('admin.photos.save-index') }}";
        const expressResponse = await axios.post(backendEndpoint, {
            matrix_payload: finalCalculatedMatrix
        });

        if (expressResponse.data.success) {
            logger(`🎉 Complete! ${expressResponse.data.message}`, 'text-blue');
        } else {
            logger(`❌ Backend rejected save context mapping package processing format.`, 'text-yellow');
        }

    } catch (globalException) {
        logger(`❌ Fatal Thread Exception halted compilation layout: ${globalException.message}`, 'text-yellow');
        console.error(globalException);
    } finally {
        controlBtn.disabled = false;
    }
}
</script>
</body>
</html>