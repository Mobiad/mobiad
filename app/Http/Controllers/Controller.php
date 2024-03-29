<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validate(Request $request)
    {
        $validator = Validator::make(
            $data = $request->only(array_filter([
                'field',
                $request->input('country_name')
            ])),
            $rules = [
                'field' => Arr::wrap($request->input('parameters')),
            ]
        );

        try {
            return response()->json([
                'request' => $data,
                'rules' => $rules,
                'passes' => $validator->passes(),
                'message' => $validator->errors()->get('field') ?: '',
                'exception' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'request' => $data,
                'rules' => $rules,
                'passes' => false,
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
        }
    }
}
