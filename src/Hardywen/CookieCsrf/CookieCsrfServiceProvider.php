<?php namespace Hardywen\CookieCsrf;

use Illuminate\Support\ServiceProvider;

class CookieCsrfServiceProvider extends ServiceProvider {

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
        $this->app->router->when($config['route'], 'cookie-csrf', $config['method']);

        //将csrf token 放进 cookie里
        \Cookie::queue('cookie_csrf_token',csrf_token());

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
        $app->router->filter('cookie-csrf', function()
        {
            if (\Session::token() !== \Cookie::get('cookie_csrf_token'))
            {
                \Session::regenerateToken(); //token用过一次后就重新生成，防止表单重复提交
                throw new \Illuminate\Session\TokenMismatchException;
            }

            \Session::regenerateToken();//token用过一次后就重新生成，防止表单重复提交

        });
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
