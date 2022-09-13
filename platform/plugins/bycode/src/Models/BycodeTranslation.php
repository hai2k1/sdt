<?php

namespace Botble\Bycode\Models;

use Botble\Base\Models\BaseModel;

class BycodeTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bycodes_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'bycodes_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
