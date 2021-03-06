<?php

namespace App\Http\Requests;

use App\Constants\Attribute;

class PaisRequest extends Request
{
    /**
     * Determine se o usuário está autorizado a fazer essa solicitação.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Obtenha as regras de validação que se aplicam à solicitação.
     *
     * @return array
     */
    public function rules()
    {
        return [
            Attribute::NO_PAIS       => Attribute::REQUIRED,
            Attribute::SG_PAIS       => Attribute::REQUIRED,
            Attribute::NO_CONTINENTE => Attribute::REQUIRED,
        ];
    }

    /**
     * Obtenha atributos personalizados para erros do validador.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            Attribute::NO_PAIS       => 'Nome do páis',
            Attribute::SG_PAIS       => 'Sigla do páis',
            Attribute::NO_CONTINENTE => 'Continente do páis',
        ];
    }
}
