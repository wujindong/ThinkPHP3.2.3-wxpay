<?php
error_reporting(0);
require_once "./config.php";
$info=$_GET['info'];
$tag=$_GET['tag'];
setcookie("tag",$tag);
setcookie("info",$info);
$redirect_url="http://www.eshenghuo365.com/cbcalipay/aop/case/getUserInfo.php";
$url="https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".APPID."&scope=auth_base&redirect_uri=".$redirect_url;
header("location:".$url);