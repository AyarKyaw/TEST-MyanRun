import requests
import json

url = "https://wx.racetigertiming.com/Dif/bio"
params = {
    "pc": "000001",
    "rid": "102692", 
    "token": "620519e266414af19b175e5edce0fc57",
    "page": "1"
}

try:
    response = requests.post(url, params=params)
    data = response.json()

    # This will show you exactly what the API sent back
    print("--- Full API Response ---")
    print(json.dumps(data, indent=4))
    print("-------------------------")

    if data.get('code') == 0:
        # Use .get() to avoid KeyError if the API changes its mind
        total = data.get('total', 'N/A')
        print(f"Success! Total records: {total}")
    else:
        print(f"API Error Message: {data.get('msg', 'Unknown Error')}")

except Exception as e:
    print(f"Connection Error: {e}")