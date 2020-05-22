#1、钩子

##（1）app运行之前的钩子--BeforeApp.php

##（2）app运行之后的钩子--AfterApp.php

##（3）方法执行之前的钩子--BeforeMethod.php

##（4）方法执行之后的钩子--AfterMethod.php

#2、使用方法

参考ExampleHook.php实现需要执行的钩子类，然后在需要执行的位置注册该钩子类;
注意：框架会根据注册的排序顺序执行。
