<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2c252c9f4b0c961670f2ab8d324f65de
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'IMSGlobal\\LTI\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'IMSGlobal\\LTI\\' => 
        array (
            0 => __DIR__ . '/..' . '/imsglobal/lti/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2c252c9f4b0c961670f2ab8d324f65de::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2c252c9f4b0c961670f2ab8d324f65de::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
