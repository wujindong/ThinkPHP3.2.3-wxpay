<?php

error_reporting(0);
require_once "./config.php";
$redirect_url="http://www.eshenghuo365.com/cbcalipay/aop/case/getTradeNo.php";
$url="https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".APPID."&scope=auth_base&redirect_uri=".$redirect_url;
header("location:".$url);