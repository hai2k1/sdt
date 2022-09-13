<?php

namespace Botble\Bycode;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('bycodes');
        Schema::dropIfExists('bycodes_translations');
    }
}
