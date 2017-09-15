var KEY = '30819d300d06092a864886f70d010101050003818b0030818702818100977897db8430954408e273' +
    '9c167c62806786374a1ae16fbebe28decad8ddac5edbbb438ba15e816700809ba27cbca499fc35192143a7481' +
    '1dda4b61f562706a617e3c70b6c75677ceb6ff650ace1b2a4eb54b2cb683335b998fc2e11e62a1ea086e91dc54f5bbcd38a' +
    '71a8c4311bfa5d7809763633330e5b3c5b40846ae9137d020113';
var MERCHANTID = '105320583990045';//商户代码
var POSID = '000844099';//商户柜台代码
var BRANCHID = '322000000';//分行代码
var CURCODE = '01';//交易币种
var PUB32TR2 = KEY.slice(KEY.length - 30, KEY.length);//公钥后30位
var GATEWAY = 'W1Z1S2';//网关类型
var THIRDAPPINFO = 'comccbpay' + MERCHANTID + 'apppay';//客户端标识
var MER_REFERER = 'www.eshenghuo365.com';//商户域名
var bankURL = 'https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?';//银行网址
var TYPE = "1";
var TXCODE = '520100';//交易代码
var REMARK1 = '';
var REMARK2 = '';
var CLIENTIP = '';
var TIMEOUT = '';

//二维码支付类型
var RETURNTYPE = '1';
var qrcodeBankURL = "https://ibsbjstar.ccb.com.cn/CCBIS/ccbMain?CCB_IBSVersion=V6";

/**
 * @龙支付
 * @param PAYMENT 支付金额
 * @param ORDERID 订单号
 * @param REGINFO
 * @param PROINFO
 */
function cbcPay(PAYMENT, ORDERID, REGINFO, PROINFO) {

    var tmp = 'MERCHANTID=' + MERCHANTID + '&POSID=' + POSID + '&BRANCHID=' + BRANCHID + '&ORDERID=' + ORDERID + '&PAYMENT=' + PAYMENT + '&CURCODE=' + CURCODE + '&TXCODE=' + TXCODE + '&REMARK1=' + REMARK1 + '&REMARK2=' + REMARK2;
    var newTmp = tmp + '&TYPE=' + TYPE + '&PUB=' + PUB32TR2 + '&GATEWAY=' + GATEWAY + '&CLIENTIP=' + CLIENTIP + '&REGINFO=' + escape(REGINFO) + '&PROINFO=' + escape(PROINFO) + '&REFERER=' + MER_REFERER + "&THIRDAPPINFO=" + THIRDAPPINFO;
    var temp_New1 = tmp + '&TYPE=' + TYPE + '&GATEWAY=' + GATEWAY + '&CLIENTIP=' + CLIENTIP + '&REGINFO=' + escape(REGINFO) + '&PROINFO=' + escape(PROINFO) + '&REFERER=' + MER_REFERER + "&THIRDAPPINFO=" + THIRDAPPINFO;
    var strMD5 = hex_md5(newTmp);
    var payURL = bankURL + temp_New1 + '&MAC=' + strMD5;

    // console.log(payURL);
    window.location = payURL;


}


/**
 * @龙支付-二维码支付
 * @param PAYMENT 支付金额
 * @param ORDERID 订单号
 * @returns {string}
 */
function qrcodePay(PAYMENT, ORDERID) {
    tmp = 'MERCHANTID=' + MERCHANTID + '&POSID=' + POSID + '&BRANCHID=' + BRANCHID + '&ORDERID=' + ORDERID;
    tmp += '&PAYMENT=' + PAYMENT + '&CURCODE=' + CURCODE + '&TXCODE=' + TXCODE + '&REMARK1=' + REMARK1;
    tmp += '&REMARK2=' + REMARK2 + '&RETURNTYPE=' + RETURNTYPE + '&TIMEOUT=' + TIMEOUT;
    tmp0 = tmp;
    tmp += '&PUB=' + PUB32TR2;
    var URL = qrcodeBankURL + '&' + tmp0 + '&MAC=' + hex_md5(tmp);
    return URL;
}