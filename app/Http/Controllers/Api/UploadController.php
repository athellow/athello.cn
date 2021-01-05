<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UploadController extends Controller
{

    public function do(Request $request)
    {
        $data = [
            'status' => 1,
            'data' => ''
        ];

        $file = $request->file('file');

        if ($file->isValid()) {
            $original_name = $file->getClientOriginalName();        // 文件原名
            $file_type = $file->getClientOriginalExtension();       // 获取文件后缀
            $file_path = $file->getRealPath();                      // 获取文件临时存放位置
            $type = $file->getClientMimeType();                     // image/jpeg
            $size =$file->getSize();

            $file_name = date('Y') . '/'. date('m-d_His_') . uniqid() . '.' . $file_type;       // 定义文件名

            $bool = Storage::disk('public')->put($file_name, file_get_contents($file_path));

            $data = [
                'status' => 0,
                'data' => $file_name                                // 文件路径
            ];
        }
        
        return response()->json($data);
    }
}
