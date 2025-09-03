<?php

namespace App\Http\Requests;

use App\Http\Requests\RegisterStudentWithoutIdRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Course\RegisterStudentRequest;

class CreateAndRegisterStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // نتحقق من صلاحيات RegisterStudentRequest لأنه يحتوي على الشروط
        return (new RegisterStudentRequest())->authorize();
    }

    public function rules(): array
    {
        // القواعد من الملفات الأصلية
        $studentRules = (new StoreStudentRequest())->rules();
        $registrationRules = (new RegisterStudentWithoutIdRequest())->rules();

        // نضيف prefix لكل مجموعة
        $prefixedStudentRules = [];
        foreach ($studentRules as $key => $rule) {
            $prefixedStudentRules["student.$key"] = $rule;
        }

        $prefixedRegistrationRules = [];
        foreach ($registrationRules as $key => $rule) {
            $prefixedRegistrationRules["registration.$key"] = $rule;
        }

        return array_merge($prefixedStudentRules, $prefixedRegistrationRules);
    }

    public function messages(): array
    {
        return array_merge(
            (new StoreStudentRequest())->messages(),
            (new RegisterStudentRequest())->messages()
        );
    }
}
