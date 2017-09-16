<?php
/**
 * 使用本接口需要开通当面付
 */
namespace Bing\Controller;
use Think\Controller;
use Think\Log;

class AlipayJsapiController extends Controller {


    const GATEWAYURL = 'https://openapi.alipay.com/gateway.do';
    const APPID = '';//填写APPID
    const APIVESION = '1.0';
    const POSTCHARSET = 'UTF-8';
    const FORMAT = 'json';
    private $privatekey='';
    private $publickey='';

    public function _initialize(){
        //设置私钥和支付宝公钥（注：在服务器端生成公钥和私钥，把公钥上传到支付宝获取到支付宝公钥）
        $this->privatekey="";//填写公钥文件路径
        $this->publickey="";//填写私钥文件路径
        vendor("cbcalipay.aop.AopClient");
        vendor("cbcalipay.aop.request.AlipaySystemOauthTokenRequest");
        vendor("cbcalipay.aop.request.AlipayTradeCreateRequest");
    }

    public function index() {
        $redirect_url="http://".$_SERVER['HTTP_HOST'].U("Bing/AlipayJsapi/authorize");
        $url="https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=".self::APPID."&scope=auth_base&redirect_uri=".$redirect_url;
        redirect($url);
    }

    /**
     * 获取用户授权信息
     */
    public  function authorize(){
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey =$this->publickey;
        $request = new \AlipaySystemOauthTokenRequest();
        $request->setGrantType("authorization_code");
        $request->setCode($_REQUEST['auth_code']);
        $result = $aop->execute($request);
        $data = (array) $result;
        $oauth_token_response = (array) $data['alipay_system_oauth_token_response'];
//        Log::write(var_export($oauth_token_response,true));
        cookie("user_id",$oauth_token_response['user_id']);
        $this->display("alipayJsapi/index");
    }


    /**
     * 创建订单
     * @param $buyer_id
     */
    public  function create_trade(){
        $buyer_id=cookie("user_id");
        $aop = new \AopClient();
        $aop->appId = self::APPID;
        $aop->rsaPrivateKeyFilePath = $this->privatekey;
        $aop->alipayPublicKey = $this->publickey;
        $request_create_trade = new \AlipayTradeCreateRequest();

        $orderInfo = array(
            'out_trade_no' => date("YmdHis"),
            'total_amount' =>I('get.total_amount'),
            'subject' =>I('get.subject'),
            'buyer_id' =>$buyer_id
        );
        $request_create_trade->setBizContent(json_encode($orderInfo));
        $results = (array)$aop->execute($request_create_trade);
        Log::write(var_export($results,true));
        $responseNode =(array)$results['alipay_trade_create_response'];
//        Log::write(var_export($responseNode,true));
        $data['code']=$responseNode['code'];
        $data['msg']=$responseNode['sub_code'];
        if (!empty($responseNode['code']) && $responseNode['code'] == 10000) {
            //成功返预订单号
            $data['trade_no']=$responseNode['trade_no'];
            $this->ajaxReturn($data);
        } else {
            //失败返回相关状态
           $this->ajaxReturn($data);
        }
    }
}
