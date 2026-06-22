import json

# Update the path to wherever your file saves
with open('./storage/app/public/S3/face_index.json', 'r') as f:
    data = json.load(f)

first_image = list(data.keys())[0]
first_face_profile = data[first_image][0]

print(f"Image analyzed: {first_image}")
print(f"Bounding Box dimensions: {first_face_profile['box']}")
print(f"Vector Dimensions shape count: {len(first_face_profile['embedding'])}")