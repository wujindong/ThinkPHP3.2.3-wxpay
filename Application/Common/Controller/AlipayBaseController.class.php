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
        vendor("cbcalipay.aop.request.AlipayTradeQueryRequest");
    }

    /**
     * 交易结果查询
     * @param $out_trade_no
     * @param $trade_no
     */
    public  function tradeQuery($out_trade_no,$trade_no){


        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $request_trade_query=new \AlipayTradeQueryRequest();
        $data=array(
            "out_trade_no"=>$out_trade_no,
            "trade_no"=>$trade_no
        );

        $request_trade_query->setBizContent(json_encode($data));
        $callbackJsonString=(array)$aop->execute($request_trade_query);

        $trade_data=(array)$callbackJsonString['alipay_trade_query_response'];
        if($trade_data['code']=='10000') {
            if ($trade_data['trade_status'] == 'WAIT_BUYER_PAY') {
                //交易创建，等待买家付款
            } else if ($trade_data['trade_status'] == 'TRADE_CLOSED') {
                //未付款交易超时关闭，或支付完成后全额退款
            } else if ($trade_data['trade_status'] == 'TRADE_SUCCESS') {
                //交易支付成功
            } else if ($trade_data['trade_status'] === 'TRADE_FINISHED') {
                //交易结束，不可退款
            }
        }else{
            //接口调用失败

        }
    }




}