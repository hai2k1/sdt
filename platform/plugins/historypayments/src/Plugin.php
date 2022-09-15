<?php

namespace Botble\Historypayments;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('historypayments');
        Schema::dropIfExists('historypayments_translations');
    }
}
