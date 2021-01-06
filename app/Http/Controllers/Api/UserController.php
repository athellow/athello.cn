<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WXBizDataCrypt;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $cocde = $request->input('code');

        $data = [
            'status' => 0,
            'data' => [
                'user_id' => 1,
                'session_key' => 'a'.$cocde
            ]
        ];
        
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $code = $request->input('code');
        $encryptedData = $request->input('encryptedData');
        $iv = $request->input('iv');

        if ($code != '') {
            $appid = config('main.wechat.appid');
            $secret = config('main.wechat.appsecret');
            $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
            $html = file_get_contents($url);
            $obj = json_decode($html);

            if(isset($obj->errcode)){
                // 获取用户信息失败
                return $html;
            }else{

                $arrlist = ['openid' => $obj->openid, 'session_key' => $obj->session_key];
                
                /**
                 * 解密用户敏感数据
                 *
                 * @param encryptedData 明文,加密数据
                 * @param iv            加密算法的初始向量
                 * @param code          用户允许登录后，回调内容会带上 code（有效期五分钟），开发者需要将 code 发送到开发者服务器后台，使用code 换取 session_key api，将 code 换成 openid 和 session_key
                 * @return
                 */

                $pc = new WXBizDataCrypt($appid, $arrlist['session_key']);

                $errCode = $pc->decryptData($encryptedData, $iv, $data);
                $data  = json_decode($data);//$data 包含用户所有基本信息
                $arrlist['time'] = time();
                $arrlist['city'] = $data->city;//城市-市
                $arrlist['country'] = $data->country;//国家
                $arrlist['gender'] = $data->gender;//性别
                $arrlist['language'] = $data->language;//语言
                $arrlist['nickName'] = $data->nickName;//昵称
                $arrlist['avatarUrl'] = $data->avatarUrl;//头像
                $arrlist['province'] = $data->province;//城市-省份
                //判断获取信息是否成功
                if ($errCode != 0) {
                    return response()->json([
                        'status' => 1,
                        'msg' => $errCode
                    ]);
                }
                //存入数据库
                

                return response()->json([
                    'status' => 0,
                    'data' => $data
                ]);
            }
        }else{
            return response()->json([
                'status' => 1,
                'msg' => 'code为空'
            ]);
        }
    }
}
