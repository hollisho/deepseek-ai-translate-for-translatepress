<?php

namespace hollisho\helpers;

use Closure;
use PhpOption\Option;

class EnvHelper
{
    /**
     * Indicates if the putenv adapter is enabled.
     *
     * @var bool
     */
    protected static $putenv = true;

    /**
     * The environment repository instance.
     *
     * @var Dotenv\Repository\RepositoryInterface|null
     */
    protected static $repository;

    /**
     * Enable the putenv adapter.
     *
     * @return void
     */
    public static function enablePutenv()
    {
        static::$putenv = true;
        static::$repository = null;
    }

    /**
     * Disable the putenv adapter.
     *
     * @return void
     */
    public static function disablePutenv()
    {
        static::$putenv = false;
        static::$repository = null;
    }

    /**
     * Get the environment repository instance.
     *
     * @return RepositoryInterface
     */
    public static function getRepository()
    {
        if (static::$repository === null) {
            // 检查 phpdotenv 版本
            if (method_exists(\Dotenv\Repository\RepositoryBuilder::class, 'createWithDefaultAdapters')) {
                // 5.x 版本
                $builder = \Dotenv\Repository\RepositoryBuilder::createWithDefaultAdapters();

                if (static::$putenv) {
                    $builder = $builder->addAdapter(\Dotenv\Repository\Adapter\PutenvAdapter::class);
                }
            } else {
                // 4.x 版本
                $builder = \Dotenv\Repository\RepositoryBuilder::create()
                    ->withReaders([
                        new \Dotenv\Repository\Adapter\EnvConstAdapter(),
                    ])
                    ->withWriters([
                        new \Dotenv\Repository\Adapter\EnvConstAdapter(),
                        new \Dotenv\Repository\Adapter\PutenvAdapter(),
                    ]);
            }

            static::$repository = $builder->immutable()->make();
        }

        return static::$repository;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Option::fromValue(static::getRepository()->get($key))
            ->map(function ($value) {
                switch (strtolower($value)) {
                    case 'true':
                    case '(true)':
                        return true;
                    case 'false':
                    case '(false)':
                        return false;
                    case 'empty':
                    case '(empty)':
                        return '';
                    case 'null':
                    case '(null)':
                        return;
                }

                if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
                    return $matches[2];
                }

                return $value;
            })
            ->getOrCall(function () use ($default) {
                return static::value($default);
            });
    }

    public static function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}