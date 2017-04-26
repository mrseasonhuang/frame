<?php
namespace config;

//当前使用的环境，1.debug - 本机测试环境  2. test - 测试机环境 3.online - 线上正式环境
defined('ENV') or define('ENV','online');

//是否在页面展示错误，0不展示，1展示
defined('SHOW_ERROR') or define('SHOW_ERROR',0);