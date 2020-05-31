<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Factory as ValidationFactory;

class CrawlerDataFormatRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'messages' => $validator->errors()->all()
            ], 200)
        );
    }

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
            'page' => 'between:1,3',
            'tipo_veiculo' => 'string|in:carro,moto,caminhao',
            'ano_veiculo_min' => 'digits:4',
            'ano_veiculo_max' => 'digits:4',
            'preco_veiculo_min' => 'between:4,8',
            'preco_veiculo_max' => 'between:4,8',
        ];
    }

    public function messages()
    {
        return [
            'page.between' => 'page deve ser um inteiro com no máximo 3 dígitos',
            'tipo_veiculo.in' => 'tipo_veiculo deve ser: carro, moto ou caminhao',
            'ano_veiculo_min.digits' => 'ano_veiculo_min deve ser um inteiro de 4 dígitos',
            'ano_veiculo_max.digits' => 'ano_veiculo_max deve ser um inteiro de 4 dígitos',
            'preco_veiculo_min.between' => 'preco_veiculo_min deve ser um inteiro de no mínimo 4 e máximo 8 dígitos',
            'preco_veiculo_max.between' => 'preco_veiculo_max deve ser um inteiro de no mínimo 4 e máximo 8 dígitos',
        ];
    }
}
