<?php
namespace Common\Controller;
use Think\Controller;
class AlipayBaseController extends Controller{
    const GATEWAYURL = 'https://openapi.alipay.com/gateway.do';
    const APPID = '2017080808092324';
    const APIVESION = '1.0';
    const POSTCHARSET = 'UTF-8';
    const FORMAT = 'json';
    protected $privatekey='';
    protected $publickey='';

    public function _initialize(){
        //设置私钥和支付宝公钥（注：在服务器端生成公钥和私钥，把公钥上传到支付宝获取到支付宝公钥）
        $this->privatekey="http://".$_SERVER['HTTP_HOST'].__ROOT__."/ThinkPHP/Library/Vendor/cbcalipay/aop/case/key/rsa_private_key.pem";
        $this->publickey="http://".$_SERVER['HTTP_HOST'].__ROOT__."/ThinkPHP/Library/Vendor/cbcalipay/aop/case/key/alipay_public_key.pem";
        vendor("cbcalipay.aop.AopClient");
        vendor("cbcalipay.aop.request.AlipaySystemOauthTokenRequest");
        vendor("cbcalipay.aop.request.AlipayTradeCreateRequest");
        vendor("cbcalipay.aop.request.AlipayTradeWapPayRequest");
    }
}