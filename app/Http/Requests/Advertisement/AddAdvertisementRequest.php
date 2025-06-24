<?php

namespace App\Http\Requests\Advertisement;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddAdvertisementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['Manager', 'Secretariat']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'body' => ['required', 'string'],
            'advertisable_id' => [ 'nullable','integer'],
            'advertisable_type' => [ 'nullable','string',  Rule::in([Teacher::class,Course::class])],
            'images'=>['required','array'],
            'images.*' =>['required','mimes:jpg,png,jpeg,gif,svg|max:2048','distinct'],
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->has('advertisable_type')) {
            $type = strtolower($this->input('advertisable_type'));

            $map = [
                'teacher' => \App\Models\Teacher::class,
                'course' => \App\Models\Course::class,
            ];

            if (isset($map[$type])) {
                $this->merge([
                    'advertisable_type' => $map[$type],
                ]);
            }
        }
    }
}
