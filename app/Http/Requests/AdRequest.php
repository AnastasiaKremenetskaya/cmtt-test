<?php

namespace App\Http\Requests;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class AdRequest
{
    /**
     * AdController constructor
     *
     * @param array $data
     * @return Validation
     */
    public function validate(array $data): Validation
    {
        $validator = new Validator();
        $validation = $validator->make($data, $this->getRules());
        $validation->setMessages([
                                     'banner:regex' => 'Invalid Banner link'
                                 ]);
        $validation->validate();

        return $validation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return string[]
     *
     * @psalm-return array{text: 'required', price: 'required|integer|min:0', limit: 'required|integer|min:0', banner: 'max:255|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'}
     */
    private function getRules(): array
    {
        $urlRegex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        return [
            'text' => 'required',
            'price' => 'required|integer|min:0',
            'limit' => 'required|integer|min:0',
            'banner' => 'max:255|regex:' . $urlRegex,
        ];
    }
}
