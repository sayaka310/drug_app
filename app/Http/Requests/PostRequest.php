<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $route = $this->route()->getName();

        $rule = [
            'discription' => 'required|string|max:50',
        ];

        if ($route === 'posts.store' ||
            ($route === 'posts.update' && $this->file('images'))) {
            $rule['image.*'] = 'required|file|image|mimes:jpeg,png';
        }

        return $rule;
    }
}
