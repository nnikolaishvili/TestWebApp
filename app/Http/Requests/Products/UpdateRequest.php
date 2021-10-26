<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'status' => 'required|boolean',
            'image_file' => 'sometimes|image|max:' . Product::MAX_IMAGE_SIZE
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'image_file.max' => 'The :attribute must not be greater than 5 megabytes',
        ];
    }
}
