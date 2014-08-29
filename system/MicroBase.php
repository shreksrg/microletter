<?php


/**
 * Gets the application start timestamp.
 */
defined('Micro_BEGIN_TIME') or define('Micro_BEGIN_TIME', microtime(true));
/**
 * This constant defines whether the application should be in debug mode or not. Defaults to false.
 */
defined('Micro_DEBUG') or define('Micro_DEBUG', false);
/**
 * This constant defines how much call stack information (file name and line number) should be logged by Micro::trace().
 * Defaults to 0, meaning no backtrace information. If it is greater than 0,
 * at most that number of call stacks will be logged. Note, only user application call stacks are considered.
 */
defined('Micro_TRACE_LEVEL') or define('Micro_TRACE_LEVEL', 0);
/**
 * This constant defines whether exception handling should be enabled. Defaults to true.
 */
defined('Micro_ENABLE_EXCEPTION_HANDLER') or define('Micro_ENABLE_EXCEPTION_HANDLER', true);
/**
 * This constant defines whether error handling should be enabled. Defaults to true.
 */
defined('Micro_ENABLE_ERROR_HANDLER') or define('Micro_ENABLE_ERROR_HANDLER', true);
/**
 * Defines the Micro framework installation path.
 */
defined('Micro_PATH') or define('Micro_PATH', dirname(__FILE__));
/**
 * Defines the Zii library installation path.
 */
defined('Micro_ZII_PATH') or define('Micro_ZII_PATH', Micro_PATH . DIRECTORY_SEPARATOR . 'zii');

/**
 * MicroBase is a helper class serving common framework functionalities.
 *
 * Do not use MicroBase directly. Instead, use its child class {@link Micro} where
 * you can customize methods of MicroBase.

 */
class MicroBase
{
    /**
     * @var array class map used by the Micro autoloading mechanism.
     * The array keys are the class names and the array values are the corresponding class file paths.
     * @since 1.1.5
     */
    public static $classMap = array();
    /**
     * @var boolean whether to rely on PHP include path to autoload class files. Defaults to true.
     * You may set this to be false if your hosting environment doesn't allow changing the PHP
     * include path, or if you want to append additional autoloaders to the default Micro autoloader.
     * @since 1.1.8
     */
    public static $enableIncludePath = true;

    private static $_aliases = array('system' => Micro_PATH, 'zii' => Micro_ZII_PATH); // alias => path
    private static $_imports = array(); // alias => class name or directory
    private static $_includePaths; // list of include paths
    private static $_app;
    private static $_logger;


    /**
     * @return string the version of Micro framework
     */
    public static function getVersion()
    {
        return '1.1.15';
    }

    /**
     * Creates a Web application instance.
     * @param mixed $config application configuration.
     * If a string, it is treated as the path of the file that contains the configuration;
     * If an array, it is the actual configuration information.
     * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
     * which should point to the directory containing all application logic, template and data.
     * If not, the directory will be defaulted to 'protected'.
     * @return CWebApplication
     */
    public static function createWebApplication($config = null)
    {
        return self::createApplication('CWebApplication', $config);
    }

    /**
     * Creates a console application instance.
     * @param mixed $config application configuration.
     * If a string, it is treated as the path of the file that contains the configuration;
     * If an array, it is the actual configuration information.
     * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
     * which should point to the directory containing all application logic, template and data.
     * If not, the directory will be defaulted to 'protected'.
     * @return CConsoleApplication
     */
    public static function createConsoleApplication($config = null)
    {
        return self::createApplication('CConsoleApplication', $config);
    }

    /**
     * Creates an application of the specified class.
     * @param string $class the application class name
     * @param mixed $config application configuration. This parameter will be passed as the parameter
     * to the constructor of the application class.
     * @return mixed the application instance
     */
    public static function createApplication($class, $config = null)
    {
        return new $class($config);
    }

    /**
     * Returns the application singleton or null if the singleton has not been created yet.
     * @return CApplication the application singleton, null if the singleton has not been created yet.
     */
    public static function app()
    {
        return self::$_app;
    }

    /**
     * Stores the application instance in the class static member.
     * This method helps implement a singleton pattern for CApplication.
     * Repeated invocation of this method or the CApplication constructor
     * will cause the throw of an exception.
     * To retrieve the application instance, use {@link app()}.
     * @param CApplication $app the application instance. If this is null, the existing
     * application singleton will be removed.
     * @throws CException if multiple application instances are registered.
     */
    public static function setApplication($app)
    {
        if (self::$_app === null || $app === null)
            self::$_app = $app;
        else
            throw new CException('Micro application can only be created once.');
    }

    /**
     * Imports a class or a directory.
     *
     * Importing a class is like including the corresponding class file.
     * The main difference is that importing a class is much lighter because it only
     * includes the class file when the class is referenced the first time.
     *
     * Importing a directory is equivalent to adding a directory into the PHP include path.
     * If multiple directories are imported, the directories imported later will take
     * precedence in class file searching (i.e., they are added to the front of the PHP include path).
     *
     * Path aliases are used to import a class or directory. For example,
     * <ul>
     *   <li><code>application.components.GoogleMap</code>: import the <code>GoogleMap</code> class.</li>
     *   <li><code>application.components.*</code>: import the <code>components</code> directory.</li>
     * </ul>
     *
     * The same path alias can be imported multiple times, but only the first time is effective.
     * Importing a directory does not import any of its subdirectories.
     *
     * Starting from version 1.1.5, this method can also be used to import a class in namespace format
     * (available for PHP 5.3 or above only). It is similar to importing a class in path alias format,
     * except that the dot separator is replaced by the backslash separator. For example, importing
     * <code>application\components\GoogleMap</code> is similar to importing <code>application.components.GoogleMap</code>.
     * The difference is that the former class is using qualified name, while the latter unqualified.
     *
     * Note, importing a class in namespace format requires that the namespace corresponds to
     * a valid path alias once backslash characters are replaced with dot characters.
     * For example, the namespace <code>application\components</code> must correspond to a valid
     * path alias <code>application.components</code>.
     *
     * @param string $alias path alias to be imported
     * @param boolean $forceInclude whether to include the class file immediately. If false, the class file
     * will be included only when the class is being used. This parameter is used only when
     * the path alias refers to a class.
     * @return string the class name or the directory that this alias refers to
     * @throws CException if the alias is invalid
     */
    public static function import($alias, $forceInclude = false)
    {
        if (isset(self::$_imports[$alias])) // previously imported
            return self::$_imports[$alias];

        if (class_exists($alias, false) || interface_exists($alias, false))
            return self::$_imports[$alias] = $alias;

        if (($pos = strrpos($alias, '\\')) !== false) // a class name in PHP 5.3 namespace format
        {
            $namespace = str_replace('\\', '.', ltrim(substr($alias, 0, $pos), '\\'));
            if (($path = self::getPathOfAlias($namespace)) !== false) {
                $classFile = $path . DIRECTORY_SEPARATOR . substr($alias, $pos + 1) . '.php';
                if ($forceInclude) {
                    if (is_file($classFile))
                        require($classFile);
                    else
                        throw new CException("Alias $alias is invalid. Make sure it points to an existing PHP file and the file is readable.");
                    self::$_imports[$alias] = $alias;
                } else
                    self::$classMap[$alias] = $classFile;
                return $alias;
            } else {
                // try to autoload the class with an autoloader
                if (class_exists($alias, true))
                    return self::$_imports[$alias] = $alias;
                else
                    throw new CException("Alias $alias is invalid. Make sure it points to an existing PHP file and the file is readable.");

            }
        }

        if (($pos = strrpos($alias, '.')) === false) // a simple class name
        {
            if ($forceInclude && self::autoload($alias))
                self::$_imports[$alias] = $alias;
            return $alias;
        }

        $className = (string)substr($alias, $pos + 1);
        $isClass = $className !== '*';

        if ($isClass && (class_exists($className, false) || interface_exists($className, false)))
            return self::$_imports[$alias] = $className;

        if (($path = self::getPathOfAlias($alias)) !== false) {
            if ($isClass) {
                if ($forceInclude) {
                    if (is_file($path . '.php'))
                        require($path . '.php');
                    else
                        throw new CException("Alias $alias is invalid. Make sure it points to an existing PHP file and the file is readable.");
                    self::$_imports[$alias] = $className;
                } else
                    self::$classMap[$className] = $path . '.php';
                return $className;
            } else // a directory
            {
                if (self::$_includePaths === null) {
                    self::$_includePaths = array_unique(explode(PATH_SEPARATOR, get_include_path()));
                    if (($pos = array_search('.', self::$_includePaths, true)) !== false)
                        unset(self::$_includePaths[$pos]);
                }

                array_unshift(self::$_includePaths, $path);

                if (self::$enableIncludePath && set_include_path('.' . PATH_SEPARATOR . implode(PATH_SEPARATOR, self::$_includePaths)) === false)
                    self::$enableIncludePath = false;

                return self::$_imports[$alias] = $path;
            }
        } else
            throw new CException("Alias $alias  is invalid. Make sure it points to an existing directory or file.");
    }

    /**
     * Translates an alias into a file path.
     * Note, this method does not ensure the existence of the resulting file path.
     * It only checks if the root alias is valid or not.
     * @param string $alias alias (e.g. system.web.CController)
     * @return mixed file path corresponding to the alias, false if the alias is invalid.
     */
    public static function getPathOfAlias($alias)
    {
        if (isset(self::$_aliases[$alias]))
            return self::$_aliases[$alias];
        elseif (($pos = strpos($alias, '.')) !== false) {
            $rootAlias = substr($alias, 0, $pos);
            if (isset(self::$_aliases[$rootAlias]))
                return self::$_aliases[$alias] = rtrim(self::$_aliases[$rootAlias] . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, substr($alias, $pos + 1)), '*' . DIRECTORY_SEPARATOR);

        }
        return false;
    }

    /**
     * Create a path alias.
     * Note, this method neither checks the existence of the path nor normalizes the path.
     * @param string $alias alias to the path
     * @param string $path the path corresponding to the alias. If this is null, the corresponding
     * path alias will be removed.
     */
    public static function setPathOfAlias($alias, $path)
    {
        if (empty($path))
            unset(self::$_aliases[$alias]);
        else
            self::$_aliases[$alias] = rtrim($path, '\\/');
    }

    /**
     * Class autoload loader.
     * This method is provided to be invoked within an __autoload() magic method.
     * @param string $className class name
     * @return boolean whether the class has been loaded successfully
     */
    public static function autoload($className)
    {
        // use include so that the error PHP file may appear
        if (isset(self::$classMap[$className]))
            include(self::$classMap[$className]);
        elseif (isset(self::$_coreClasses[$className]))
            include(Micro_PATH . self::$_coreClasses[$className]);
        else {
            // include class file relying on include_path
            if (strpos($className, '\\') === false) // class without namespace
            {
                if (self::$enableIncludePath === false) {
                    foreach (self::$_includePaths as $path) {
                        $classFile = $path . DIRECTORY_SEPARATOR . $className . '.php';
                        if (is_file($classFile)) {
                            include($classFile);
                            if (Micro_DEBUG && basename(realpath($classFile)) !== $className . '.php')
                                throw new CException("Class name $className does not match class file");
                            break;
                        }
                    }
                } else
                    include($className . '.php');
            } else // class name with namespace in PHP 5.3
            {
                $namespace = str_replace('\\', '.', ltrim($className, '\\'));
                if (($path = self::getPathOfAlias($namespace)) !== false)
                    include($path . '.php');
                else
                    return false;
            }
            return class_exists($className, false) || interface_exists($className, false);
        }
        return true;
    }

    /**
     * Registers a new class autoloader.
     * The new autoloader will be placed before {@link autoload} and after
     * any other existing autoloaders.
     * @param callback $callback a valid PHP callback (function name or array($className,$methodName)).
     * @param boolean $append whether to append the new autoloader after the default Micro autoloader.
     * Be careful using this option as it will disable {@link enableIncludePath autoloading via include path}
     * when set to true. After this the Micro autoloader can not rely on loading classes via simple include anymore
     * and you have to {@link import} all classes explicitly.
     */
    public static function registerAutoloader($callback, $append = false)
    {
        if ($append) {
            self::$enableIncludePath = false;
            spl_autoload_register($callback);
        } else {
            spl_autoload_unregister(array('MicroBase', 'autoload'));
            spl_autoload_register($callback);
            spl_autoload_register(array('MicroBase', 'autoload'));
        }
    }

    /**
     * @var array class map for core Micro classes.
     * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
     * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
     */
    private static $_coreClasses = array(
       // 'ccc' => '/core/cccc.php',

    );
}

spl_autoload_register(array('MicroBase', 'autoload'));

