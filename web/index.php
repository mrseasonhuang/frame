<?php
//简化分割符号/ 兼容window和linux都可以用
defined('DS') or define('DS',DIRECTORY_SEPARATOR);

//__FILE__--该文件的url  dirname() 该文件所在目录url  realpath(url.'/../') 该文件所在目录的上级目录——根目录
defined('ROOT_PATH') or define('ROOT_PATH',realpath(dirname(__FILE__).DS.'..'.DS).DS);

defined('APP_PATH') or define('APP_PATH',ROOT_PATH.'application'.DS);
defined('CONF_PATH') or define('CONF_PATH',ROOT_PATH.'config'.DS);
defined('FRAME_PATH') or define('FRAME_PATH',ROOT_PATH.'frame'.DS);
defined('STATIC_PATH') or define('STATIC_PATH',ROOT_PATH.'web'.DS.'static'.DS);
defined('LOG_PATH') or define('LOG_PATH',ROOT_PATH.'runtime'.DS);

//加载框架运行基本的文件
require_once CONF_PATH.'config.php';
require_once FRAME_PATH.'Init.php';
require_once FRAME_PATH.'Method.php';
require_once FRAME_PATH.'Log.php';

spl_autoload_register('\frame\Init::autoload');

\frame\LogFactory::load(LOG_REQUEST)->formatLog();

\frame\Init::start();