<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        // 默认生成
        // switch($this->method())
        // {
        //     // CREATE
        //     case 'POST':
        //     {
        //         return [
        //             // CREATE ROLES
        //         ];
        //     }
        //     // UPDATE
        //     case 'PUT':
        //     case 'PATCH':
        //     {
        //         return [
        //             // UPDATE ROLES
        //         ];
        //     }
        //     case 'GET':
        //     case 'DELETE':
        //     default:
        //     {
        //         return [];
        //     };
        // }
        
        return [
            'content' => 'required|min:2',
        ];
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
