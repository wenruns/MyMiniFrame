#中间件的实现

###第一步：实现中间件
     参考ExampleMiddle.php实现中间件。
###第二步：注册中间件
    在Middleware.php中的register方法中注册，注册方式是对键值对；
    键：表示该中间件的简称，
    值：是该中间件的实现类。
###第三步：使用中间件
    在路由中使用group方法中使用，例如：
    Route::group([
        'middleware' => 键, 
    ], function(Route $route){
    });