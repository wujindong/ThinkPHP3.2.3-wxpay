<?php
namespace Common\Controller;
use Think\Controller;
class AlipayBaseController extends Controller{
    const GATEWAYURL = 'https://openapi.alipay.com/gateway.do';
    const APPID = '';//填写APPID
    const APIVESION = '1.0';
    const POSTCHARSET = 'UTF-8';
    const FORMAT = 'json';
    protected $privatekey='';
    protected $publickey='';
    protected $notifyurl='';

    public function _initialize(){
        //设置私钥和支付宝公钥（注：在服务器端生成公钥和私钥，把公钥上传到支付宝获取到支付宝公钥）
        $this->privatekey="";//填写私钥路径
        $this->publickey="";//填写支付宝公钥路径
        $this->notifyurl="";//填写异步回调地址
        vendor("cbcalipay.aop.AopClient");
        vendor("cbcalipay.aop.request.AlipaySystemOauthTokenRequest");
        vendor("cbcalipay.aop.request.AlipayTradeCreateRequest");
        vendor("cbcalipay.aop.request.AlipayTradeWapPayRequest");
    }
}