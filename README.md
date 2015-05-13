# cookie-csrf

####改写一个 laravel 的 csrf filter。原来的的方式需要将token放到表单字段里一起提交。比较麻烦。 重新改写一个 cookie-csrf filter， 功能如下：
#####1. 将 token 放在cookie里 (cookie_csrf_token)。 不需要每个表单都加上token字段，更方便使用。
#####2. token值使用后马上重新生成一个新token，可以有效防止重复提交。


#Install

添加 ``` "hardywen/cookie-csrf": "v0.1" ``` 到 composer.json 里

运行 ``` composer update ``` 命令安装


#Config
默认的配置是：
```php
    'route' => '*',

    'method' => array(
        'post',
        'put',
        'delete'
    )
``` 
即所有路径的 post,put,delete 方法都进行 cookie-csrf 过滤。

你可以运行 ``` php artisan config:publish hardywen/cookie-csrf``` 复制出配置文件，然后改按你的需要来配置。

当```route=>''```，则不自动使用cookie-csrf， 你可以自己按需要去手动调用  cookie-csrf 过滤。


#Others

除了服务端进行防止重复提交之外，前台也应该用js防止重复提交表单的设置。例如防止jQuery ajax重复提交可以按以下方式配置：
```js
//setup ajax default options
        var formSubmitting = false; // 防止ajax重复提交
        $.ajaxSetup({
            beforeSend: function () {
                if (formSubmitting) {
                    return false;
                } else {
                    formSubmitting = !formSubmitting;
                }
            },
            complete: function (xhr, status) {
                formSubmitting = !formSubmitting;
            }
        });
```
jQuery的ajax配置，在每次进行ajax之前需要判断一下是否正在处理表单，如果formSubmitting为true是，不会再提交。等ajax完成后，再将formSubmitting改回false。

防止直接提交表单的方式如下(点击submit按钮后将其disable,就不能再次点击了)：
```js
//防止重复提交表单
        $("form").submit(function () {
            $(":submit", this).attr("disabled", "disabled");
        });
        ```
