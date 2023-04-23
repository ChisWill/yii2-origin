Yii2个人定制型模板
===============================

在Yii2原有基础上
增加了一些试用于中小站点快速开发的特性，固化了各种常用功能的使用方式
使用自定义的代码生成工具替代GII，固化了代码生成的一些参数，为了更方便的生成能直接使用的代码
修改了一些内置方法的特性，使之能更快速调用
增加了一个后台管理模块，实现了Yii2内置的rbac功能
集成了Workerman，可以直接搭建一个socket服务器，支持服务器推送功能
内嵌了一个简单的oa模块
内嵌了一个手册模块
......

DIRECTORY STRUCTURE
-------------------

```
api
    components/          包含API项目的基础组件
    config/              包含API项目的配置文件
    controllers/         包含API项目的控制器
    models/              包含API项目的模型
    web/                 包含API项目的入口脚本
common
    actions/             包含公共的action
    assets/              包含公共的静态资源包
    behaviors/           包含公共的behavior
    classes/             包含公共的个人封装类库
    components/          包含公共的组件
    config/              contains shared configurations
    functions/           包含公共的函数库
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    static/              包含公共的静态资源
    traits/              包含公共的trait
    widgets/             包含公共的widget
console
    components/          包含控制台的组件
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    jar/                 包含控制台执行的jar程序
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
    servers/             包含基于Workerman的Socket.Io与Http推送服务端
frontend
    assets/              contains application assets such as JavaScript and CSS
    components/          包含前端项目的组件
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    migrations/          包含前端项目的迁移记录
    models/              contains frontend-specific model classes
    modules/             包含前端项目的模块
    runtime/             contains files generated during runtime
    themes/              包含前端项目的主题
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```
