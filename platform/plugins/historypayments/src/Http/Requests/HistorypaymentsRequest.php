<?php

namespace Botble\Historypayments\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class HistorypaymentsRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_bank_account'   => 'required',
            'bank_id'   => 'required',
            'bank_name'   => 'required',
            'money'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
