<?php

namespace Botble\Historypayments\Providers;

use Botble\Historypayments\Models\Historypayments;
use Illuminate\Support\ServiceProvider;
use Botble\Historypayments\Repositories\Caches\HistorypaymentsCacheDecorator;
use Botble\Historypayments\Repositories\Eloquent\HistorypaymentsRepository;
use Botble\Historypayments\Repositories\Interfaces\HistorypaymentsInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class HistorypaymentsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(HistorypaymentsInterface::class, function () {
            return new HistorypaymentsCacheDecorator(new HistorypaymentsRepository(new Historypayments));
        });

        $this->setNamespace('plugins/historypayments')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Historypayments::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Historypayments::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-historypayments',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/historypayments::historypayments.name',
                'icon'        => 'fa fa-list',
                'url'         => route('historypayments.index'),
                'permissions' => ['historypayments.index'],
            ]);
        });
    }
}
