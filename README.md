# J&T Express Tracking API
Return JSON formatted string of J&amp;T Express Tracking details

# Installation
```composer require afzafri/jnt-express-tracking-api:dev-master```

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# Sample Response
```yaml
{
  "http_code": 200,
  "error_msg": "No error",
  "message": "Record Found",
  "data": [
    {
      "date": "21/09/2020",
      "time": "17:41",
      "location": "Drop Point CDC KUALAKANGSAR 305",
      "city": "KUALA KANGSAR",
      "process": "Delivered",
      "remark": ""
    },
    {
      "date": "21/09/2020",
      "time": "11:00",
      "location": "Drop Point CDC KUALAKANGSAR 305",
      "city": "KUALA KANGSAR",
      "process": "Delivery",
      "remark": ""
    },
    {
      "date": "20/09/2020",
      "time": "17:44",
      "location": "Drop Point CDC KUALAKANGSAR 305",
      "city": "KUALA KANGSAR",
      "process": "On Hold",
      "remark": "Out of droppoint's business hour"
    },
    {
      "date": "19/09/2020",
      "time": "17:41",
      "location": "Drop Point CDC KUALAKANGSAR 305",
      "city": "KUALA KANGSAR",
      "process": "On Hold",
      "remark": "Out of droppoint's business hour"
    },
    {
      "date": "19/09/2020",
      "time": "09:17",
      "location": "Drop Point CDC KUALAKANGSAR 305",
      "city": "KUALA KANGSAR",
      "process": "Arrived",
      "remark": ""
    },
    {
      "date": "19/09/2020",
      "time": "07:02",
      "location": "Transit Center PRK GATEWAY",
      "city": "KAMPAR",
      "process": "Departure",
      "remark": ""
    },
    {
      "date": "19/09/2020",
      "time": "00:19",
      "location": "Transit Center PRK GATEWAY",
      "city": "KAMPAR",
      "process": "Arrived",
      "remark": ""
    },
    {
      "date": "18/09/2020",
      "time": "19:18",
      "location": "Transit Center PJS GATEWAY",
      "city": "KLANG",
      "process": "Arrived",
      "remark": ""
    },
    {
      "date": "18/09/2020",
      "time": "17:46",
      "location": "Drop Point DP SETIAALAM 01",
      "city": "PETALING",
      "process": "Departure",
      "remark": ""
    },
    {
      "date": "18/09/2020",
      "time": "17:25",
      "location": "Drop Point DP SETIAALAM 01",
      "city": "PETALING",
      "process": "Picked Up",
      "remark": ""
    }
  ],
  "info": {
    "creator": "Afif Zafri (afzafri)",
    "project_page": "https://github.com/afzafri/JNT-Express-Tracking-API",
    "date_updated": "22/09/2020"
  }
}
```

# License
This library is under ```MIT license```, please look at the LICENSE file
