<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-sandbox.factus.com.co/v1/bills/validate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "numbering_range_id": 38,
    "reference_code": "I3",
    "observation": "PAAGR 11",
    "payment_method_code": "26",
    "customer": {
        "identification": "123456789",
        "dv": "3",
        "company": "",
        "trade_name": "",
        "names": "Alan Turing",
        "address": "calle 1 # 2-68",
        "email": "alanturing@enigmasas.com",
        "phone": "1234567890",
        "legal_organization_id": "2",
        "tribute_id": "21",
        "identification_document_id": "3",
        "municipality_id": "980"
    },
    "items": [
        {
            "code_reference": "1",
            "name": "prueba",
            "quantity": "4",
            "discount": "11",
            "discount_rate": 20,
            "price": "12000.00",
            "tax_rate": "19.00",
            "unit_measure_id": 70,
            "standard_code_id": 1,
            "is_excluded": 0,
            "tribute_id": 1,
            "withholding_taxes": [
                {
                    "code": "06",
                    "withholding_tax_rate": "7.00"
                },
                {
                    "code": "05",
                    "withholding_tax_rate": "15.00"
                }
            ]
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZDYwYTVjNy04Y2Q5LTQzNDMtOTdjMy0xNWU2YzRkNGQ4ZjMiLCJqdGkiOiI1MWJhMzJjOWM1NzRjMDI2N2QwODBjNGJmNzEwYmYxMWYzOGE3MDRmYzhhYzczOWExMjRhODA0Mjk5OTg0NTA4MzFmY2MxYTRhMTk5NzkzOCIsImlhdCI6MTczMTY5OTkxMi4zOTE1MjMsIm5iZiI6MTczMTY5OTkxMi4zOTE1MjcsImV4cCI6MTczMTcwMzUxMi4zNzUyMDcsInN1YiI6IjMiLCJzY29wZXMiOltdfQ.QmPIPwVLfg8gP4HGzquDtFaeQeDH0vKxB4Vcx8-DC_Ml5nwrz-A7XEuGrI34hmWiG93G2U7ZkP5QAOdUUF1PGSTklBNKWOxw-jGIBLpxlzsv2el9N8inPFeBcmqBe4XvicYfem3Yt-xXmg27TaSEBwy6HL_qupz7E_yFWYp1MFNWI8xJg0a_1LvKwClkaZtYU_yQP9YlHBePlEkqnMAznSCyT88Pq2AZc6IbzHOMxSQRSJ_ETi0ZaCNB8zF4KB6bAFhZDsweskh8wWCD5Kd02t5sXjTDYJtOuO7otD90LeJWJAYFLhdYFT4hGstEw3xQl2mk95cl7YpSfw8X6qjxE87b8Yt9s7tG5N6QAfzjyTmv1FNMtQQniWK8D2Snnm0ukwNcJ2swETs_8N60wfKX9FXzbosk9kLLjgHDz-XXDgQcshEEJntYwqAEjXLOYUnOT5rSEOy81ybcbZ4Kywk_VMyvRfypj2W9qjvtpyE_R8KJPtsA9YwoinnE1a9_CzltoBsIheh6fBWuep7Ca1NkBz10aUXQNB31OnNTaZFzKrawoQXVEjWkd4T9EwdOVwgJeoSs8yuaznCIs3akHecFeDRtkYts7sSY9_x5yXwJJUTu44x1jpyJtim3V14kjEUZ70c3Ii67Q8DKfllI_3WTGzLgX6zqE_n0HzPdXGnIeW0'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
