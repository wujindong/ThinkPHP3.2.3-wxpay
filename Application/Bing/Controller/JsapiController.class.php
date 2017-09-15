<?php
namespace Bing\Controller;
use Think\Controller;
class JsapiController extends Controller {
    public function index(){
        ini_set('date.timezone','Asia/Shanghai');
        Vendor('weixin.WxPayApi');
        Vendor('weixin.JsApiPay');

        //①、获取用户openid
        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();
        if(isset($openId)){
            cookie("openId",$openId);
        }else{
            $openId=cookie("openId");
        }

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        $this->assign("jsApiParameters",$jsApiParameters);
        $this->display("Jsapi/index");
    }
}