<?php

namespace App\Http\Requests;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;

class AdRequest
{
    /**
     * AdController constructor
     *
     * @param $data
     * @return Validation
     */
    public function validate($data): Validation
    {
        $validator = new Validator();
        $validation = $validator->make($data, $this->getRules());
        $validation->validate();

        return $validation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private function getRules(): array
    {
        return [
            'text' => 'required',
            'price' => 'required|integer|min:0',
            'limit' => 'required|integer|min:0',
            'banner' => 'nullable|max:255',
        ];
    }
}
