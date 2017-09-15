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
$request = new AlipayTradeCreateRequest ();

$request->setBizContent("{" .
"    \"out_trade_no\":\"20150320010101001\"," .
"    \"seller_id\":\"2088102146225135\"," .
"    \"total_amount\":88.88," .
"    \"discountable_amount\":8.88," .
"    \"undiscountable_amount\":80.00," .
"    \"buyer_logon_id\":\"15901825620\"," .
"    \"subject\":\"Iphone6 16G\"," .
"    \"body\":\"Iphone6 16G\"," .
"    \"buyer_id\":\"2088102146225135\"," .
"      \"goods_detail\":[{" .
"                \"goods_id\":\"apple-01\"," .
"        \"alipay_goods_id\":\"20010001\"," .
"        \"goods_name\":\"ipad\"," .
"        \"quantity\":1," .
"        \"price\":2000," .
"        \"goods_category\":\"34543238\"," .
"        \"body\":\"特价手机\"," .
"        \"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
"        }]," .
"    \"operator_id\":\"Yx_001\"," .
"    \"store_id\":\"NJ_001\"," .
"    \"terminal_id\":\"NJ_T_001\"," .
"    \"extend_params\":{" .
"      \"sys_service_provider_id\":\"2088511833207846\"," .
"      \"hb_fq_num\":\"3\"," .
"      \"hb_fq_seller_percent\":\"100\"" .
"    }," .
"    \"timeout_express\":\"90m\"," .
"    \"royalty_info\":{" .
"      \"royalty_type\":\"ROYALTY\"," .
"        \"royalty_detail_infos\":[{" .
"                    \"serial_no\":1," .
"          \"trans_in_type\":\"userId\"," .
"          \"batch_no\":\"123\"," .
"          \"out_relation_id\":\"20131124001\"," .
"          \"trans_out_type\":\"userId\"," .
"          \"trans_out\":\"2088101126765726\"," .
"          \"trans_in\":\"2088101126708402\"," .
"          \"amount\":0.1," .
"          \"desc\":\"分账测试1\"," .
"          \"amount_percentage\":\"100\"" .
"          }]" .
"    }," .
"    \"alipay_store_id\":\"2016041400077000000003314986\"," .
"    \"sub_merchant\":{" .
"      \"merchant_id\":\"19023454\"" .
"    }" .
"  }");
$result = $aop->execute ( $request);

$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
$resultCode = $result->$responseNode->code;
if(!empty($resultCode)&&$resultCode == 10000){
echo "成功";
} else {
echo "失败";
}