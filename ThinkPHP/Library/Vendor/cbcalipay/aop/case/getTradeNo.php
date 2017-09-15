<?php
error_reporting(0);
require_once "../AopClient.php";
require_once "../request/AlipaySystemOauthTokenRequest.php";
require_once "../request/AlipayTradeCreateRequest.php";
require_once './config.php';
$aop = new AopClient ();
$aop->gatewayUrl = GATEWAYURL;
$aop->appId =APPID;
$aop->rsaPrivateKeyFilePath =RSAPRIVATEKEYFILEPATH;
$aop->alipayPublicKey=ALIPAYPUBLICKEY;
$aop->apiVersion =APIVESION;
$aop->postCharset=POSTCHARSET;
$aop->format=FORMAT;
$request = new AlipaySystemOauthTokenRequest();
$request ->setGrantType("authorization_code");
$request ->setCode($_REQUEST['auth_code']);
$result = $aop->execute ($request);



$data=(array)$result;
$oauth_token_response=(array)$data['alipay_system_oauth_token_response'];

var_dump($oauth_token_response);

$request_create_trade = new AlipayTradeCreateRequest ();

$orderInfo=array(
    'out_trade_no'=>'20150320010101001',
    'total_amount'=>'0.01',
    'subject'=>'一只小黄鸭',
    'buyer_id'=>$oauth_token_response['user_id']
);

var_dump($orderInfo);
$request_create_trade->setBizContent(json_encode($orderInfo));
$results = $aop->execute($request_create_trade); 
var_dump($results);
 
$responseNode = str_replace(".", "_", $request_create_trade->getApiMethodName()) . "_response";
echo "############################\n";

$resultCode = $results->$responseNode->code;
var_dump($responseNode);
if(!empty($resultCode)&&$resultCode == 10000){
echo "成功";
} else {
echo "失败";
}

?>