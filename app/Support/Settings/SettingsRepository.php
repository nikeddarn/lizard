<?php
/**
 * Settings repository.
 */

namespace App\Support\Settings;


use Illuminate\Cache\CacheManager;

class SettingsRepository
{
    // using cache storage
    const CACHE_STORAGE = 'file';

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * SettingsRepository constructor.
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Get property from cache if exists or from config as default.
     *
     * @param string $key
     * @return mixed
     */
    public function getProperty(string $key)
    {
        return $this->cacheManager->store(self::CACHE_STORAGE)->get($key, config($key));
    }

    /**
     * Set property forever to cache.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setProperty(string $key, $value)
    {
        $this->cacheManager->store(self::CACHE_STORAGE)->forever($key, $value);
    }
}
