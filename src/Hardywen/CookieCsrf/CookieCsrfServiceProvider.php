<?php namespace Hardywen\CookieCsrf;

use Illuminate\Support\ServiceProvider;

class CookieCsrfServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('hardywen/cookie-csrf');

        $config = $this->app->config->get('cookie-csrf::config');

        //根据config配置哪些route及提交方法需要调用cookie-csrf
        $this->app->router->when('*', 'cookie-csrf', $config['method']);

        //将csrf token 放进 cookie里
        \Cookie::queue('cookie_csrf_token', csrf_token());

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        //创建 cookie-csrf filter
        $app->router->filter('cookie-csrf', function () {

            $config = $this->app->config->get('cookie-csrf::config');

            dd($this->match($config['white_list']) , !$this->match($config['black_list']));
            if ($this->match($config['white_list']) && !$this->match($config['black_list'])) {

                if (\Session::token() !== \Cookie::get('cookie_csrf_token')) {
                    throw new \Illuminate\Session\TokenMismatchException;
                }

                \Session::regenerateToken();//token用过一次后就重新生成，防止表单重复提交
            }

        });
    }

    private function match($pages){

        $current_route = $this->app->request->path();

        foreach($pages as $page){
            if(str_is($page,$current_route)){
                return true;
            }
        }

        return false;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('cookie-csrf');
    }

}
