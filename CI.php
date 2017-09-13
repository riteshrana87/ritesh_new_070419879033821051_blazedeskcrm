<?php
/**
 * Part of CodeIgniter Doctrine
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-doctrine
 *
 * Based on http://codeinphp.github.io/post/codeigniter-tip-accessing-codeigniter-instance-outside/
 * Thanks!
 */

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');


/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}


$system_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'system';
$application_folder = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'application';
$doc_root           = '';

if (realpath($system_path) !== false) {
    $system_path = realpath($system_path) . '/';
}
$system_path = rtrim($system_path, '/') . '/';

//define('BASEPATH', str_replace("\\", "/", $system_path));
//define('FCPATH',   $doc_root . '/');
//define('APPPATH',  $application_folder . '/');
//define('VIEWPATH', $application_folder . '/views/');

////==================================================

// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE)
{
    $system_path = $_temp.'/';
}
else
{
    // Ensure there's a trailing slash
    $system_path = rtrim($system_path, '/').'/';
}


// Is the system path correct?
if ( ! is_dir($system_path))
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3); // EXIT_CONFIG
}

/*
* -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
* -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// Path to the system folder
	define('BASEPATH', str_replace('\\', '/', $system_path));

	// Path to the front controller (this file)
	define('FCPATH', dirname(__FILE__).'/');

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	// The path to the "application" folder
	if (is_dir($application_folder))
    {
        if (($_temp = realpath($application_folder)) !== FALSE)
        {
            $application_folder = $_temp;
        }

        define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
    }
    else
    {
        if ( ! is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
        {
            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
            echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
            exit(3); // EXIT_CONFIG
        }

        define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
    }

	// The path to the "views" folder
	if ( ! is_dir($view_folder))
    {
        if ( ! empty($view_folder) && is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
        {
            $view_folder = APPPATH.$view_folder;
        }
        elseif ( ! is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
        {
            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
            echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
            exit(3); // EXIT_CONFIG
        }
        else
        {
            $view_folder = APPPATH.'views';
        }
    }

	if (($_temp = realpath($view_folder)) !== FALSE)
    {
        $view_folder = $_temp.DIRECTORY_SEPARATOR;
    }
    else
    {
        $view_folder = rtrim($view_folder, '/\\').DIRECTORY_SEPARATOR;
    }

	define('VIEWPATH', $view_folder);

//====================================================

require(BASEPATH . 'core/Common.php');

if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
    require(APPPATH . 'config/' . ENVIRONMENT . '/constants.php');
} else {
    require(APPPATH . 'config/constants.php');
}

$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);

if (extension_loaded('mbstring')) {
    define('MB_ENABLED', TRUE);
    // mbstring.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('mbstring.internal_encoding', $charset);
    // This is required for mb_convert_encoding() to strip invalid characters.
    // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
    mb_substitute_character('none');
} else {
    define('MB_ENABLED', FALSE);
}

// There's an ICONV_IMPL constant, but the PHP manual says that using
// iconv's predefined constants is "strongly discouraged".
if (extension_loaded('iconv')) {
    define('ICONV_ENABLED', TRUE);
    // iconv.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('iconv.internal_encoding', $charset);
} else {
    define('ICONV_ENABLED', FALSE);
}

require(BASEPATH . 'core/Controller.php');

$GLOBALS['CFG'] = & load_class('Config', 'core');
$GLOBALS['UNI'] = & load_class('Utf8', 'core');
$GLOBALS['SEC'] = & load_class('Security', 'core');
$GLOBALS['LANG'] = & load_class('Lang','core');
$GLOBALS['Router'] = & load_class('Router','core');
//$GLOBALS['CTRL'] = & load_class('Controller','core');


//load_class('Loader', 'core');
//load_class('Router', 'core');
//load_class('Input',  'core');
//load_class('Lang',   'core');
//load_class('',   'core');

//require_once BASEPATH.'core/CodeIgniter.php';
//require(BASEPATH . 'core/Controller.php');

function &get_instance()
{
    return CI_Controller::get_instance();
}

return new CI_Controller();