<?php

namespace Botble\Historypayments\Models;

use Botble\Base\Models\BaseModel;

class HistorypaymentsTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'historypayments_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'historypayments_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
