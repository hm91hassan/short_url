<?php

namespace App\Http\Requests;

use App\Models\Shortner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ShortnerFV extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'long_url' => 'required|unique:shortners,long_url,' . $this->id,
        ];
    }
    public function messages()
    {
        $exists = Shortner::where('long_url', $this->long_url)->first();
        $exists_url = ($exists)?url($exists->short_url):null;

        return [
            'long_url.required' => ['Please Enter Your URL', 'url' => $exists_url] ,
            'long_url.unique' => ['The URL has already been taken.', 'url' => $exists_url],
        ];
    }
}