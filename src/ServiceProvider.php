<?php

namespace Tungltdev\fakerUserSudoSu;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if ($this->configExists() && $this->tldIsAllowed()) {
            $this->app->register(RouteServiceProvider::class);
        }
    }

    public function boot()
    {
//        $this->publishes([
//            __DIR__ . '/../resources/assets/compiled' => public_path('faker_user/'),
//        ], 'public');

        $this->publishes([
            __DIR__.'/../config/faker_user.php' => config_path('faker_user.php')
        ], 'config');

        if ($this->configExists() && $this->tldIsAllowed()) {
            $this->registerViews();
        }
    }
    
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'faker_user');

        // Add an inline view composer for the user-selector
        View::composer('faker_user::user-selector', function ($view) {
            $sudosu = App::make(FakerUserSudoSu::class);
            $guard = Config::get('faker_user.guard','web');

            $view->with([
                'users' => $sudosu->getUsers(),
                'hasSudoed' => $sudosu->hasSudoed(),
                'originalUser' => $sudosu->getOriginalUser(),
                'currentUser' => auth($guard)->user()
            ]);
        });   
    }

    protected function tldIsAllowed()
    {
        $requestTld = $this->getRequestTld();
        $allowedTlds = Config::get('faker_user.allowed_tlds');

        return in_array($requestTld, $allowedTlds);
    }

    protected function getRequestTld()
    {
        $requestHost = parse_url(Request::url())['host'];
        $exploded = explode('.', $requestHost);
        $requestTld = end($exploded);

        return $requestTld;
    }

    protected function configExists()
    {
        return is_array(Config::get('faker_user.allowed_tlds'));
    }
}
