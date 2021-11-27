<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
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
            'text' => 'string|min:5|max:255',
            'user_name' => 'string|min:5|max:5',
            'post_id' => 'exists:posts,id',
            'referenced_comment_id' => 'exists:comments,id|nullable'
        ];
    }
}
