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
        vendor("cbcalipay.aop.request.AlipayTradeCloseRequest");
        vendor("cbcalipay.aop.request.AlipayTradeRefundRequest");
        vendor("cbcalipay.aop.request.AlipayTradeFastpayRefundQueryRequest");
        vendor("cbcalipay.aop.request.AlipayDataDataserviceBillDownloadurlQueryRequest");
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

    /**
     * 关闭交易
     * @param $trade_no
     * @param $out_trade_no
     * @param $operator_id
     */
    public function tradeClose($trade_no,$out_trade_no,$operator_id){
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $requst_trade_clode= new \AlipayTradeCloseRequest();
        $data=array(
            'trade_no'=>$trade_no,
            'out_trade_no'=>$out_trade_no,
            'operator_id'=>$operator_id
        );

        $requst_trade_clode->setBizContent(json_encode($data));
        $result=(array)$aop->execute($requst_trade_clode);
        $closeResponse=(array)$result['alipay_trade_close_response'];

        if($closeResponse['code']=='10000'){
            //成功
        }else{
            //失败
        }
    }

    /**
     * 交易退款
     * @param $trade_no
     * @param $out_trade_no
     * @param $refund_amount 退款金额
     */
    public function tradeRefund($trade_no,$out_trade_no,$refund_amount){
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $request_trade_refund=new \AlipayTradeRefundRequest();
        $data=array(
            'trade_no'=>$trade_no,
            'out_trade_no'=>$out_trade_no,
            'refund_amount'=>$refund_amount
        );
        $request_trade_refund->setBizContent(json_encode($data));
        $result=(array)$aop->execute($request_trade_refund);
        $refundResponse=(array)$result['alipay_trade_refund_response'];

        if($refundResponse['code']=='10000'){
            //成功
        }else{
            //失败
        }

    }


    /**
     * 交易退款查询
     * @param $trade_no
     * @param $out_trade_no
     * @param $out_request_no
     */
    public function tradeRefunQuery($trade_no,$out_trade_no,$out_request_no){
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $request_trade_refund_query=new \AlipayTradeFastpayRefundQueryRequest();
        $data=array(
            'trade_no'=>$trade_no,
            'out_trade_no'=>$out_trade_no,
            'out_request_no'=>$out_request_no
        );
        $request_trade_refund_query->setBizContent(json_encode($data));
        $result=(array)$aop->execute($request_trade_refund_query);
        $refundQueryResponse=(array)$result['alipay_trade_fastpay_refund_query_response'];

        if($refundQueryResponse['code']=='10000'){
            //成功
        }else{
            //失败
        }

    }


    /**
     * 查询对账单下载地址
     * @param $bill_type :trade,signcustomer trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单
     * @param $bill_date :账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
     * @return $bill_download_url 账单下载地址链接，获取连接后30秒后未下载，链接地址失效
     */
    public  function downLoadUrlQuery($bill_type,$bill_date){
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $request_download_url_query=new \AlipayDataDataserviceBillDownloadurlQueryRequest();
        $data=array(
            'bill_type'=>$bill_type,
            'bill_date'=>$bill_date
        );
        $request_download_url_query->setBizContent(json_encode($data));
        $result=(array)$aop->execute($request_download_url_query);
        $downLoadUrlResponse=(array)$result['alipay_data_dataservice_bill_downloadurl_query_response'];
        if($downLoadUrlResponse['code']=='10000'){
            //成功
            return $downLoadUrlResponse['bill_download_url'];

        }else{
            //失败
        }
    }




}