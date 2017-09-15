<?php
error_reporting(0);
require_once "../AopClient.php";
require_once "../request/AlipaySystemOauthTokenRequest.php";
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
$tag=$_COOKIE['tag'];
$info=$_COOKIE['info'];

if($tag=="qrpay") {

    echo "<script type='text/javascript'>window.location='http://www.eshenghuo365.com/cbcmsf/{$tag}.php?info={$info}&user_id={$oauth_token_response["user_id"]}'</script>";
}else{
    echo "<script type='text/javascript'>window.location='http://www.eshenghuo365.com/cbcmsf/index.php?info={$info}&user_id={$oauth_token_response["user_id"]}'</script>";

}

?>