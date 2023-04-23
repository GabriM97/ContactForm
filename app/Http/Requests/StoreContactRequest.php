<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // any logic to prevent the authenticated user to perform this request should go here.

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
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'message' => 'required|max:255',
            'email' => 'email',
        ];
    }

    /**
     * Populate the email field with the authenticated user email in case is not passed.
     *
     * @param  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // fill email
            if (!$this->filled('email')) {
                $this->merge(['email' => Auth::user()->email]);
            }
        });
    }

    /**
     * Return a BAD_REQUEST Response containing the error messages in case of failed validation. 
     *
     * @param  Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'errors' => $errors,
                'code' => Response::HTTP_BAD_REQUEST
            ]),
            Response::HTTP_BAD_REQUEST
        );
    }
}
