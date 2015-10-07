<?php
/**
 * Created by PhpStorm.
 * User: opsat73
 * Date: 14.09.15
 * Time: 22:45
 */

/**
 * Class Loader
 * this class help to find pathes where autoloader need to find classes
 *
 */
class Loader
{
    private static $nameSpaces = array();

    /**
     * this this method add directory where loader need to find classes
     *
     * for example, you have class /Foo/Boo/Aclass which placed in file /var/Foo/Boo/Aclass.php
     * you need add classpath with next parameters
     * key = 'Foo\\'
     * directory = '\\var\\Foo'
     *
     * @param $key root directory for classes
     * @param $nameSpace path to directory
     */
    public static function addNamespacePath($key, $nameSpace) {
        self::$nameSpaces[$key] = $nameSpace;
    }

    /**
     * method need for initialization Loader
     */
    public static function initLoader() {
        $loadFunction = '\Loader::loadClass';
        spl_autoload_register($loadFunction);
    }

    private static function loadClass($class) {
        $fragments = explode('\\', $class);
        $classSpace = $fragments[0].'\\';
        $classPath = self::$nameSpaces[$classSpace];
        $count = count($fragments);
        for ($i = 1; $i < $count; $i++) {
            $classPath = $classPath.'/'.$fragments[$i];
        }
        $classPath = $classPath.'.php';
        require_once($classPath);
    }
}
Loader::initLoader();
Loader::addNamespacePath("Framework\\",__DIR__);
?>