<?php

namespace Botble\Bycode\Providers;

use Botble\Bycode\Models\Bycode;
use Illuminate\Support\ServiceProvider;
use Botble\Bycode\Repositories\Caches\BycodeCacheDecorator;
use Botble\Bycode\Repositories\Eloquent\BycodeRepository;
use Botble\Bycode\Repositories\Interfaces\BycodeInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class BycodeServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(BycodeInterface::class, function () {
            return new BycodeCacheDecorator(new BycodeRepository(new Bycode));
        });

        $this->setNamespace('plugins/bycode')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
                // Use language v2
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Bycode::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Bycode::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-bycode',
                'priority'    => 5,
                'name'        => 'Codes',
                'icon'        => 'fa fa-list',
                'permissions' => ['bycode.index'],
            ]);
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-bycode-bycode',
                'priority'    => 5,
                'parent_id'   => 'cms-plugins-bycode',
                'name'        => 'Mua code',
                'icon'        => 'fa fa-list',
                'url'         => route('bycode.index'),
                'permissions' => ['bycode.index'],
            ]);
            dashboard_menu()->registerItem([
                'id'          => 'historybycode',
                'priority'    => 6,
                'parent_id'   => 'cms-plugins-bycode',
                'name'        => 'Lịch sử mua code',
                'icon'        => 'fa fa-list',
                'url'         => route('bycode.historybycode.index'),
                'permissions' => ['historybycode.index'],
            ]);
        });
    }
}
