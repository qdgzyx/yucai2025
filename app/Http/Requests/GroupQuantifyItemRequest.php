<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupQuantifyItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'criteria' => 'required',
            'score' => 'required|integer',
            'type' => 'nullable|max:50'
        ];
    }
}