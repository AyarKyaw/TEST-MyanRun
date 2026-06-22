import os
import json
from deepface import DeepFace

def rebuild_face_matrix():
    # Paths setup: Update these to match your environment paths exactly
    image_folder = "./storage/app/public/S3"
    output_json_path = "./storage/app/public/S3/face_index.json"
    
    # Model profile locked: 128-dimensional signatures
    MODEL_NAME = "Facenet" 
    
    face_database = {}

    if not os.path.exists(image_folder):
        print(f"❌ Error: Target folder does not exist at '{image_folder}'")
        return

    print(f"🚀 Scanning directory for runner profiles: {image_folder}")
    print(f"🧬 Model profile locked: {MODEL_NAME} (Enforcing 128-Vector Mapping Alignment)")

    supported_extensions = (".jpg", ".jpeg", ".png", ".webp")
    all_files = [f for f in os.listdir(image_folder) if f.lower().endswith(supported_extensions)]
    total_files = len(all_files)

    print(f"📸 Found {total_files} photo targets to compile.")

    for index, filename in enumerate(all_files, start=1):
        image_path = os.path.join(image_folder, filename)
        
        try:
            # Extract facial signatures using DeepFace
            detections = DeepFace.represent(
                img_path=image_path,
                model_name=MODEL_NAME,
                enforce_detection=False, 
                detector_backend="opencv",
                normalization="Facenet"
            )
            
            face_profiles_list = []
            for face_data in detections:
                # Filter out low-confidence false-positive face detections
                confidence = face_data.get("face_confidence", 0)
                if confidence > 0.40:
                    facial_area = face_data.get("facial_area", {})
                    
                    # 🌟 Capture bounding box dimensions alongside the vector array 
                    # Naming fields with underscores directly mirrors face-api.js properties
                    face_entry = {
                        "box": {
                            "_x": float(facial_area.get("x", 0)),
                            "_y": float(facial_area.get("y", 0)),
                            "_width": float(facial_area.get("w", 0)),
                            "_height": float(facial_area.get("h", 0))
                        },
                        "score": float(confidence),
                        "embedding": face_data["embedding"]
                    }
                    face_profiles_list.append(face_entry)
            
            # Save the new object profiles under the image key
            if face_profiles_list:
                face_database[filename] = face_profiles_list
                print(f"[{index}/{total_files}] ✅ {filename} - Extracted {len(face_profiles_list)} structured face profile(s).")
            else:
                print(f"[{index}/{total_files}] ⚠️ {filename} - No clear human faces detected. Skipping representation.")

        except Exception as e:
            print(f"[{index}/{total_files}] ❌ Unexpected breakdown parsing {filename}: {str(e)}")

    # Save the structured face maps matrix back to disk
    print(f"\n💾 Writing new structured vector index map back to disk layout...")
    with open(output_json_path, "w", encoding="utf-8") as json_file:
        json.dump(face_database, json_file, indent=2)
        
    print(f"✨ Success! Matrix map successfully rebuilt at: {output_json_path}")
    print(f"📊 Total runners with indexed facial profiles: {len(face_database)}")

if __name__ == "__main__":
    rebuild_face_matrix()