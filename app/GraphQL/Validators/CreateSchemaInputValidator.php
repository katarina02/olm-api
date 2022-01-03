<?php

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

class CreateSchemaInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('schemas', 'name')
            ],
            'device_type_id' => [
                'required',
                'exists:device_types,id',
            ],
            'software_id' => [
                'required',
                'exists:software,id',
            ],
            'note' => [
                'string'
            ],
            'arguments' => [
                'array',
            ],
            'arguments.*.name' => [
                'required',
                'max:255',
            ],
            'arguments.*.label' => [
                'required',
                'max:255',
            ],
            'arguments.*.default_value' => [
                'nullable',
                'numeric',
            ],
            'arguments.*.row' => [
                'integer',
            ],
            'arguments.*.order' => [
                'integer',
            ],
            'arguments.*.options' => [
                'array',
            ],
            'arguments.*.options.*.name' => [
                'required',
                'max:255',
            ],
            'arguments.*.options.*.value' => [
                'required',
                'numeric',
            ],
        ];
    }
}
