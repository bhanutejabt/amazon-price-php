<?php
require __DIR__ . '/vendor/autoload.php';

function getprices($link)
{
    $page_content = file_get_contents($link);

    $res=preg_match('/"priceblock_ourprice".*\₹(.*)</i',
    $page_content, $matches);
    
    $res2=preg_match('/"priceblock_dealprice".*\₹(.*)</i',
    $page_content, $matches2);
    
    $res3=preg_match('/"priceblock_saleprice".*\₹(.*)</i',
    $page_content, $matches3);
    
    
    if($res) {
        $price = trim($matches[1]);
    }
    elseif($res2){
        $price=trim($matches2[1]);
    } 
    elseif($res3)
    {
        $price=trim($matches3[1]);
    }
    else {
        $price = 0;
    }
   return $price; 
}

$client = new \Google_Client();

$client->setApplicationName('Google Sheets and PHP');

$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);

$client->setAccessType('offline');

$client->setAuthConfig(__DIR__ . '/credentials.json');

$service = new Google_Service_Sheets($client);

$spreadsheetId = "Spread Sheet ID from URL";

$get_range = "Sheet1!B2:B";

$response = $service->spreadsheets_values->get($spreadsheetId, $get_range);

$values = $response->getValues();

$prices=array();
$dates=array();
foreach ($values as $i){
    $prices[]="₹ ".getprices($i[0]);
    $dates[]=date("m/d/Y");
}

foreach ( $prices as $p){
    printf($p."\n");
}

$update_range = "Sheet1!C2:C"; 
$updatevalues = [$prices];

$data[] = new Google_Service_Sheets_ValueRange([
    'range' => $update_range,
    'majorDimension' => 'COLUMNS',
    'values' => $updatevalues
  ]);

$body = new Google_Service_Sheets_BatchUpdateValuesRequest([
    'valueInputOption' => 'RAW',
    'data' => $data
]);

$update_sheet = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);

$date_range = "Sheet1!A2:A"; 
$datevalues = [$dates];

$data[] = new Google_Service_Sheets_ValueRange([
    'range' => $date_range,
    'majorDimension' => 'COLUMNS',
    'values' => $datevalues
  ]);

$body = new Google_Service_Sheets_BatchUpdateValuesRequest([
    'valueInputOption' => 'RAW',
    'data' => $data
]);

$update_sheet = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);

?>