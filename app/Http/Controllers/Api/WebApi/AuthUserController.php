<?php

namespace App\Http\Controllers\Api\WebApi;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;
use Log;

class AuthUserController extends BaseController {
    public function authenticate(Request $request): JsonResponse {
//        Log::info("authenticate");
        $validator = Validator::make($request->all(), ['user_id' => 'required', 'credential' => 'required', 'user_type' => 'required']);
        if ($validator->fails()) {
            return $this->returnData('AuthUser，请检查字段');
        }
        $data = array_map('intval', $validator->validated());
        return $this->returnData('Authenticate user succeed!', 'success', 200, [
            'user_id' =>"afadds",
            'email' => "aaa@aa.com",
            'level' => 1,
        ]);
    }
}
