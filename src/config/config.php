<?php

return array(

    //路由白名单，pattern通过的就调用此filter,用于设定哪些链接调用
    'white_list' => array(
        '*'
    ),

    //路由黑名单，pattern通过的就【不】调用此filter，用于排除哪些链接调用
    'black_list' => array(
        'orders/notify/*' // 支付回调之类的外站调用本站接口链接
    ),

    'method' => array(
        'post',
        'put',
        'delete'
    )

);