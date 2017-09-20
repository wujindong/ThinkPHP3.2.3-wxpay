<?php
namespace Common\Controller;
use Think\Controller;
class WxpayBaseController extends Controller{
    public function _initialize(){
        ini_set('date.timezone','Asia/Shanghai');
        Vendor('weixin.WxPayApi');
        Vendor('weixin.JsApiPay');
        Vendor('weixin.WxPayNotify');
        Vendor("weixin.NativePay");
    }
}