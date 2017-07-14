<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbf1ada629ee0e1866e3a86bc30e98b89
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '65fec9ebcfbb3cbb4fd0d519687aea01' => __DIR__ . '/..' . '/danielstjules/stringy/src/Create.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Yaml\\' => 23,
            'Stringy\\' => 8,
        ),
        'N' => 
        array (
            'NumberToWords\\' => 14,
        ),
        'L' => 
        array (
            'League\\Csv\\' => 11,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Stringy\\' => 
        array (
            0 => __DIR__ . '/..' . '/danielstjules/stringy/src',
        ),
        'NumberToWords\\' => 
        array (
            0 => __DIR__ . '/..' . '/kwn/number-to-words/src',
        ),
        'League\\Csv\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/csv/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbf1ada629ee0e1866e3a86bc30e98b89::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbf1ada629ee0e1866e3a86bc30e98b89::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
