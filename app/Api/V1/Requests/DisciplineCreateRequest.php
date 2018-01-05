<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class DisciplineCreateRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.discipline_create.validation_rules');
    }
}