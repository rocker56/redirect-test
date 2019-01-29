<?php
$urlTests = [];
$base_file = 'redirectsChecked-' . time() . '.csv';
$file_lines = file('redirects.csv');
foreach ($file_lines as $line) {
    array_push($urlTests, trim($line));
}
$wcResult = "index;url testada;url acessada;url desejada;http code\n";
foreach ($urlTests as $key => $value) {
    //echo $key . ' :: ' . $value . '<br>';
    //if ($key == 50){break;}
    $temp = '';
    $var = '';
    $var = explode(';',$value);
    $result = testURL($var[0]);
    // index;url testada;url acessada;url desejada;http code
    if ($result['httpCode'] == '404'){
        $temp = $key . ';' . $var[0] . ';' .';' . $var[1] . ';' . $result['httpCode'] . "\n";
        echo $key . ';' . $var[0] . ';' .';' . $var[1] . ';' . $result['httpCode'] . '<br>';
    } else {
        $temp = $key . ';' . $var[0] . ';' . $result['url'] . ';' . $var[1] . ';' . $result['httpCode'] . "\n";
        echo $key . ';' . $var[0] . ';' . $result['url'] . ';' . $var[1] . ';' . $result['httpCode'] . '<br>';
    }
    $wcResult .= $temp;
}
if(file_put_contents($base_file, $wcResult)) {
    echo 'SALVO!!';
} else {
    echo 'ERRO!!';
}

function testURL ($url){
    // Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_HEADER => 1
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//echo curl_error($curl);
//echo $httpcode;
// Close request to clear up some resources
curl_close($curl);
if (preg_match('/^Location: (.+)$/im', $resp, $matches)){
        $retURL = trim($matches[1]);
    } else {
    // If not, there was no redirect so return the original URL
    // (Alternatively change this to return false)
    $retURL =  $url;
}
    $wcReturn['httpCode'] = $httpcode;
    $wcReturn['url'] = $retURL;
return $wcReturn;
}
?>