<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ilog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IlogController extends Controller
{

    public function index(Request $request)
    {
        $user_id = $request->input('user_id', 0);

        // $user = User::find($user_id);
        // $list = $user->ilogs()->where('is_draft', 0)->orderBy('published_at', 'desc')->simplePaginate(5);

        $list = Ilog::leftJoin('users','ilogs.user_id','users.id')
            ->select('ilogs.*', 'users.wechat_name', 'users.wechat_avatar')
            ->where(['users.id' => $user_id, 'ilogs.is_draft' => 0])->orderBy('ilogs.published_at', 'desc')
            ->simplePaginate(5);

        $data = [
            'status' => 0,
            'data' => $list ? $list->items() : []
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $message = [
            'user_id.required' => '用户异常',
            'content.required' => '请输入内容',
        ];
        $rule = [
            'user_id' => 'required',
            'content' => 'required',
        ];

        $res = Validator::make($request->input(), $rule, $message);
        if (!$res->passes()){
            return response()->json([
                'status' => 1,
                'data' => '',
                'msg' => $res->errors()->first()
            ]);
        }

        $result = Ilog::create(array_merge(['published_at'=>date('Y-m-d H:i:s')],$request->all()));
        if ($result){
            $data = [
                'status' => 0,
                'data' => ''
            ];
        }else{
            $data = [
                'status' => 1,
                'data' => '',
                'msg' => '发布失败'
            ];
        }

        return response()->json($data);
    }
}
