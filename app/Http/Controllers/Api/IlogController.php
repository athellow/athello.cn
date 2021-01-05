<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ilog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IlogController extends Controller
{

    public function index(Request $request)
    {
        $list = Ilog::where('is_draft', 0)->orderBy('published_at', 'desc')->simplePaginate(5);

        $data = [
            'status' => 0,
            'data' => $list->items()
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
