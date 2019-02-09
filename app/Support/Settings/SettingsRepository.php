<?php
/**
 * Settings repository.
 */

namespace App\Support\Settings;


use Illuminate\Cache\CacheManager;

class SettingsRepository
{
    /**
     * @var string
     */
    const CACHE_STORAGE = 'file';
    /**
     * @var string
     */
    const KEY_PREFIX = 'settings.';

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
        // add prefix for all settings keys
        $storageKey = self::KEY_PREFIX . $key;

        return $this->cacheManager->store(self::CACHE_STORAGE)->get($storageKey, config($key));
    }

    /**
     * Set property forever to cache.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setProperty(string $key, $value)
    {
        // add prefix for all settings keys
        $storageKey = self::KEY_PREFIX . $key;

        $this->cacheManager->store(self::CACHE_STORAGE)->forever($storageKey, $value);
    }
}
