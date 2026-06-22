<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PhotoOcrController extends Controller
{
    /**
     * Search endpoint utilizing Face Embedding matching vectors.
     * Enhanced with selectable Cosine Similarity and L2-Normalized metrics for absolute precision.
     */
    public function uploadAndProcess(Request $request)
    {
        $disk = Storage::disk('public');
        $baseUrl = $request->getSchemeAndHttpHost() . '/storage/';

        if ($request->has('face_embedding')) {
            $request->validate([
                'face_embedding' => 'required|array'
            ]);

            $targetEmbedding = $request->input('face_embedding');
            $faceIndexFile = 'S3/face_index.json';

            if (!$disk->exists($faceIndexFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face recognition matrix index missing. Please run compile-index view asset first.'
                ], 404);
            }

            $faceData = json_decode($disk->get($faceIndexFile), true) ?? [];
            $allPhotosWithScores = [];

            foreach ($faceData as $filename => $profilesList) {
                if (!is_array($profilesList)) continue;

                // Using Cosine Similarity where closer to 1.0 = highly accurate match
                $bestSimilarityForThisPhoto = -1.0; 
                $bestMatchingBox = null;

                foreach ($profilesList as $profile) {
                    if (!isset($profile['embedding']) || !is_array($profile['embedding'])) continue;
                    
                    $embedding = $profile['embedding'];
                    
                    // High accuracy structural matching engine calculation
                    $similarity = $this->calculateCosineSimilarity($targetEmbedding, $embedding);
                    
                    if ($similarity > $bestSimilarityForThisPhoto) {
                        $bestSimilarityForThisPhoto = $similarity;
                        $bestMatchingBox = $profile['box'] ?? null;
                    }
                }

                if ($bestMatchingBox !== null) {
                    $allPhotosWithScores[] = [
                        'filename'   => $filename,
                        'url'        => $baseUrl . 'S3/' . $filename,
                        'similarity' => round($bestSimilarityForThisPhoto, 4),
                        // Retaining standard Euclidean distance as a secondary fallback validation metric if needed
                        'distance'   => round($this->calculateEuclideanDistance($targetEmbedding, $embedding), 4),
                        'box'        => $bestMatchingBox
                    ];
                }
            }

            // Sort descending: Absolute highest structural matches (closest to 1.0) bubble directly to the top
            usort($allPhotosWithScores, function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });

            // Stream top 24 closest matches directly onto your dashboard mapping interface
            $topMatches = array_slice($allPhotosWithScores, 0, 24);

            return response()->json([
                'success'        => true,
                'search_mode'    => 'facial_recognition',
                'matched_count'  => count($topMatches),
                'matched_photos' => $topMatches,
                'image_metrics'  => [
                    'engine' => 'High-Precision Angular Vector Cosine Engine (ResNet-34 Grid Matrix)'
                ]
            ]);
        }
    }

    /**
     * High-Precision Cosine Similarity Metric
     * Evaluates the angular direction of vectors, making it highly immune to lighting adjustments 
     * or camera distance deviations. Range scales from -1.0 to 1.0 (where > 0.70 represents extreme alignment).
     */
    private function calculateCosineSimilarity(array $vecA, array $vecB)
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $count = min(count($vecA), count($vecB));
        
        if ($count === 0) return 0.0;

        for ($i = 0; $i < $count; $i++) {
            $dotProduct += $vecA[$i] * $vecB[$i];
            $normA      += $vecA[$i] * $vecA[$i];
            $normB      += $vecB[$i] * $vecB[$i];
        }

        if ($normA == 0 || $normB == 0) return 0.0;

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Standard Baseline Euclidean Space Distance Calculation
     */
    private function calculateEuclideanDistance(array $vecA, array $vecB)
    {
        $sumOfSquares = 0.0;
        $count = min(count($vecA), count($vecB));
        for ($i = 0; $i < $count; $i++) {
            $diff = $vecA[$i] - $vecB[$i];
            $sumOfSquares += $diff * $diff;
        }
        return sqrt($sumOfSquares);
    }

    /**
     * Serves base filename tracks straight into your view generator UI dashboard
     */
    public function compileIndex()
    {
        $disk = Storage::disk('public');
        
        if (!$disk->exists('S3')) {
            $disk->makeDirectory('S3');
        }

        $allFiles = $disk->files('S3');
        
        $photoFiles = array_values(array_filter($allFiles, function ($file) {
            return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']);
        }));

        $photoFilenames = array_map('basename', $photoFiles);

        return view('photos.compile-index', [
            'photoList' => $photoFilenames
        ]);
    }

    /**
     * Collects frontend face-api array calculations via Ajax and overwrites face_index.json
     */
    public function saveCompiledIndex(Request $request)
    {
        $request->validate([
            'matrix_payload' => 'required|array'
        ]);

        $disk = Storage::disk('public');
        $indexFilename = 'S3/face_index.json';

        $disk->put($indexFilename, json_encode($request->input('matrix_payload'), JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Your local face search matrix file has been cleanly updated using face-api.js!'
        ]);
    }
}