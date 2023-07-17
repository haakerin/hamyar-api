<?php $curl = curl_init();
curl_setopt_array($curl, array(CURLOPT_URL => 'http://hamyar-api.iran.liara.run/islogin.php',   CURLOPT_RETURNTRANSFER => true,   CURLOPT_ENCODING => '',   CURLOPT_MAXREDIRS => 10,   CURLOPT_TIMEOUT => 0,   CURLOPT_FOLLOWLOCATION => true,   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,   CURLOPT_CUSTOMREQUEST => 'POST',   CURLOPT_POSTFIELDS => '{     "token":"S2h1a3kzTnc2OUR3V3Qwd0JRNjVPaWpMVXRCVTIwek5rMUs2V1h6T0JpSDkwU2NBbkRjVWVscjU4R3hINVBKenZJaENGQlRsZGxSTG5xQjBHSlVrWkttMk94RWkzNE5yUEdHK2NaWS9jdlJJOUtUUk81VG5CRWlQUWpGc0laSFNYVnA4dkJqWFZuMmtPQ3RQRDRFeW1mcGFzTldTZWhuQTFaWm9qejkxdHJzPQ==" }',   CURLOPT_HTTPHEADER => array('Content-Type: application/json'),));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
