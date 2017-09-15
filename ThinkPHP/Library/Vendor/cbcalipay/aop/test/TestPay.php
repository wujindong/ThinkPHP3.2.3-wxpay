<?php

require_once "../AopClient.php";
require_once "../request/AlipayTradeWapPayRequest.php";
$aop = new AopClient ();
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2016031701220519';
$aop->rsaPrivateKeyFilePath ='rsa_private_key.pem';
$aop->alipayPublicKey='alipay_public_key.pem';
$aop->apiVersion = '1.0';
$aop->postCharset='UTF-8';
$aop->format='json';
$request = new AlipayTradeWapPayRequest ();

$data=array(
    "body"=>"note7手機大甩卖",
    "subject"=>"手机大甩卖",
    "out_trade_no"=>"20160914132623",
    "timeout_express"=>"90m",
    "total_amount"=>"9.00",
    "product_code"=>"QUICK_WAP_PAY"
);

$request->setBizContent(json_encode($data));



$result = $aop->pageExecute ($request);
file_put_contents("../log.txt",$result."\n",FILE_APPEND);
echo $result;