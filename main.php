<?php
require_once 'search.php';

$authtoken = "a971dc64e866ffd73ca1d00c996b2704" ;
$id = "2999761000006493001" ;
$email = $_REQUEST['email'] ;

function curlCall ($url , $para)
{
    $ch = curl_init(); curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $para);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $response_info = curl_getinfo($ch);
    curl_close($ch);
}
function get_tiny_url($url)  {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}



$configuration = new PiplApi_SearchRequestConfiguration();
$configuration->api_key = '068z5pr48itmxk6eramsimgd';




$request = new PiplApi_SearchAPIRequest(array('email' => $email ), $configuration);
$response = $request->send();

$name = "" ;
if (!empty($response->name()->display))
{
    $name = $response->name()->display ;
}
else
{
    $name = "Not Found" ;
}
$imageLink = "";
if ($response->image() !== null )
{
    $imageLink = $response->image()->get_thumbnail_url(200, 100, true, false) ;
}
else
{
    $imageLink = "Not Found" ;
}
$dob = "";
if (!empty($response->dob()->display))
{
    $dob = $response->dob()->display ;
}
else
{
    $dob = "Not Found" ;
}
$currentEmployment = "" ;
if (!empty($response->person->jobs))
{
    $currentEmployment = $response->person->jobs[0]->display ;
}
else
{
    $currentEmployment = "Not Found" ;
}




$new_url = get_tiny_url($imageLink);

$employment = explode(',' , $currentEmployment) ;

$xml = '<Leads>
            <row no="1">
            <FL val="Layout">2999761000000091055</FL>
            <FL val="Pipl Name">'.$name.'</FL>
            <FL val="Pipl Date of Birth">'.$dob.'</FL>
            <FL val="Pipl Image Link">'.$new_url.'</FL>
            <FL val="Pipl Current Employment">'.$employment[0].'</FL>
            </row>
            </Leads>' ;


$getRequest = "https://crm.zoho.com/crm/private/xml/Leads/updateRecords?" ;
$getRequest_param = "authtoken=".$authtoken."&scope=crmapi&id=".$id."&xmlData=".$xml;
curlCall($getRequest,$getRequest_param) ;

//$xml = '<Leads>
//            <row no="1">
//            <FL val="Layout">2999761000000091055</FL>
//            <FL val="Last Name">Apple</FL>
//            <FL val="Email">apple@test.com</FL>
//            </row>
//            </Leads>' ;
//
//$getRequest = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords?" ;
//$getRequest_param = "authtoken=".$authtoken."&scope=crmapi&xmlData=".$xml;
//curlCall($getRequest,$getRequest_param) ;





?>
