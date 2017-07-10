<?php
/**
 * Created by PhpStorm.
 * User: ebay
 * Date: 2017/7/8
 * Time: 11:29
 */

return [
    // 数据库类型
    'DB_TYPE'   => 'mysql',
    // 数据库连接地址
    'DB_HOST'   => 'localhost',
    // 数据库名
    'DB_NAME'   => 'ebay',
    // 数据库用户名
    'DB_USER'   => 'root',
    // 数据库密码
    'DB_PWD'    => 'root',
    // 数据库端口
    'DB_PORT'   => 3306,
    // 数据库前缀
    'DB_PREFIX' => 'eb_',
    // 数据库编码
    'DB_CHARSET'=> 'utf8',
    // 是否开启调试模式
    'DB_DEBUG'  =>  TRUE,


    /* Cookie设置 */
    'COOKIE_EXPIRE'         => 3600,    // Coodie有效期
    'COOKIE_DOMAIN'         => '',      // Cookie有效域名
    'COOKIE_PATH'           => '/',     // Cookie路径
    'COOKIE_PREFIX'         => '',      // Cookie前缀 避免冲突



    /* 数据缓存设置 */
    'DATA_CACHE_TIME' => -1,      // 数据缓存有效期
    'DATA_CACHE_COMPRESS'   => false,   // 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK' => false,   // 数据缓存是否校验缓存
    'DATA_CACHE_TYPE' => 'File', // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite| Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       => TEMP_PATH,// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR' => false,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       => 1,        // 子目录缓存级别


    /* 分页设置 */
    'PAGE_ROLLPAGE'         => 5,      // 分页显示页数
    'PAGE_LISTROWS'         => 20,     // 分页每页显示记录数


    /* SESSION设置 */
    'SESSION_AUTO_START'    => true,    // 是否自动开启Session
    // 内置SESSION类可用参数
    //'SESSION_NAME'          => '',      // Session名称
    //'SESSION_PATH'          => '',      // Session保存路径
    //'SESSION_CALLBACK'      => '',      // Session 对象反序列化时候的回调函数



    /* 模板引擎设置 */
    'TMPL_ENGINE_TYPE' => 'Think',     // 默认模板引擎 以下设置仅对使用Think模板引擎有效
    'TMPL_TEMPLATE_SUFFIX' => '.html',     // 默认模板文件后缀
    'TMPL_CACHFILE_SUFFIX' => '.php',      // 默认模板缓存后缀
    'TMPL_PARSE_STRING'     => '',          // 模板引擎要自动替换的字符串，必须是数组形式。
    'TMPL_L_DELIM'          => '<{',   // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          => '}>',   // 模板引擎普通标签结束标记



    /* 邮件设置 */
    //邮箱服务器
    'relay_host' => 'smtp.qq.com',
    //邮箱端口
    'smtp_port' => 25,
    //邮箱用户名
    'user' => '576970513@qq.com',
    //邮箱密码
    'pass' => 'PENG15808278683',
    //邮件主题
    'title' => '【易趣邮件】'
];