<?php

namespace Umeng;

use Umeng\openapi\APIId;
use Umeng\openapi\APIRequest;
use Umeng\openapi\ClientPolicy;
use Umeng\openapi\RequestPolicy;
use Umeng\openapi\SyncAPIClient;
use Umeng\uapp\UmengUappGetActiveUsersParam;
use Umeng\uapp\UmengUappGetActiveUsersResult;
use Illuminate\Support\Facades\Log;
use Umeng\openapi\OceanException;

/**
 * Umeng统计数据接口查询类
 */
class UmengClient
{

	/**
	 * 友盟key
	 */
	protected $umeng_apikey = '1257708';

	/**
	 * 友盟secret
	 */
    protected $umeng_apisecret = 'LomvYz3kNny';

	/**
	 * 友盟请求域名host
	 */
    protected $gateway = 'gateway.open.umeng.com';


    /**
     * 获取
     * @param $start_date
     * @param $end_date
     * @param $app_key
     * @param string $period_type
     * @return bool
     */
	public function getActiveUsers($start_date, $end_date, $app_key, $period_type = 'daily')
	{
		try {
		    // 请替换第一个参数apiKey和第二个参数apiSecurity
		    $clientPolicy = new ClientPolicy($this->umeng_apikey, $this->umeng_apisecret, $this->gateway);
		    $syncAPIClient = new SyncAPIClient( $clientPolicy );

		    $reqPolicy = new RequestPolicy();
		    $reqPolicy->httpMethod = "POST";
		    $reqPolicy->needAuthorization = false;
		    $reqPolicy->requestSendTimestamp = false;
		    // 测试环境只支持http
		    // $reqPolicy->useHttps = false;
		    $reqPolicy->useHttps = true;
		    $reqPolicy->useSignture = true;
		    $reqPolicy->accessPrivateApi = false;

		    // --------------------------构造参数----------------------------------

		    $param = new UmengUappGetActiveUsersParam();
		    $param->setAppkey($app_key);
		    $param->setStartDate($start_date);
		    $param->setEndDate($end_date);
		    $param->setPeriodType($period_type);


		    // --------------------------构造请求----------------------------------

		    $request = new APIRequest();
		    $apiId = new APIId("com.umeng.uapp", "umeng.uapp.getActiveUsers", 1 );
		    $request->apiId = $apiId;
		    $request->requestEntity = $param;

		    // --------------------------构造结果----------------------------------

		    $result = new UmengUappGetActiveUsersResult();

		    $syncAPIClient->send($request, $result, $reqPolicy );
		    $data = $result->getActiveUserInfoArray()->activeUserInfo;
		    return $data;

		    // ----------------------------example end-------------------------------------
		} catch ( OceanException $ex ) {
		    $error_msg  = 'Umeng：Exception occured with code[' . $ex->getErrorCode () . '] message [' . $ex->getMessage () . ']。';
		    Log::error($error_msg);
		}
		return false;
	}

}

