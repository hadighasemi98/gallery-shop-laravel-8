<?php

namespace App\Http\Requests\admin\products;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>  'required|min:3|max:128', 
            'price'         =>  'required|min:3|numeric',
            'description'   =>  'required|min:3',
            'thumbnail_url' =>  'required|image|mimes:png,jpg,jpeg',
            'demo_url'      =>  'required|image|mimes:png,jpg,jpeg',
            'source_url'    =>  'required|image|mimes:png,jpg,jpeg',
            'category_id'   =>  'required|exists:categories,id',
        ];
    }
}
