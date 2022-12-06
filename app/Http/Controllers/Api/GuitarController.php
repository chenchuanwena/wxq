<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\MpService;
use App\Models\Mp;
use App\Models\MpFan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class GuitarController extends Controller
{
  /**
   * 微信对接接口
   * @param string $api_token
   * @param Request $request
   * @return string|\Symfony\Component\HttpFoundation\Response
   */
  public function getQrcode(string $api_token, Request $request)
  {
    $echoStr = $request->input('echostr', '');  //服务器对接
    try {
      $mp = Mp::where('api_token', $api_token)->first();
      $openid = $request->input('openid', '');
      $app = mp_app($mp->app_id, $mp->app_secret, $mp->valid_token, $mp->encodingaeskey);
      $access_token = $app->access_token->getToken();
      $access_token['access_token'];
      return config('wechat.defaults');
    } catch (\Exception $exception) {
      mark_error_log($exception);
      $response = $echoStr != '' ? $echoStr : MpService::DEFAULT_RETURN;
    }
    return $response;
  }

  /**
   * 获取微信token
   * @param $api_token
   * @return \Illuminate\Http\JsonResponse
   */
  public function accessTokenGet($api_token)
  {
    $mp = Mp::where('api_token', $api_token)->first();

    try {
      $app = mp_app($mp->app_id, $mp->app_secret, $mp->valid_token, $mp->encodingaeskey);
      $accessToken = $app->access_token->getToken();
      return Response::json([
        'code' => 0,
        'msg' => '请求成功',
        'data' => $accessToken
      ]);
    } catch (\Psr\SimpleCache\InvalidArgumentException $exception) {
      return Response::json([
        'code' => 400,
        'msg' => '配置有误'
      ]);
    } catch (\Exception $exception) {
      mark_error_log($exception);
      return Response::json([
        'code' => 400,
        'msg' => '配置有误'
      ]);
    }
  }
}
