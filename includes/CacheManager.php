<?php
/**
 * CacheManager Class
 * Lightweight Caching System with Redis support and automatic File-Cache fallback.
 */
class CacheManager {
    private static $cacheDir;
    private static $redis = null;
    private static $useRedis = false;

    /**
     * Initialize Cache Settings
     */
    public static function init() {
        self::$cacheDir = __DIR__ . '/../cache/';
        
        if (!file_exists(self::$cacheDir)) {
            @mkdir(self::$cacheDir, 0755, true);
            // Protect cache directory from direct web access
            @file_put_contents(self::$cacheDir . '.htaccess', "Deny from all\n");
        }

        // Check if Redis extension is loaded and server is running
        if (class_exists('Redis')) {
            try {
                $redis = new Redis();
                if (@$redis->connect('127.0.0.1', 6379, 1)) {
                    self::$redis = $redis;
                    self::$useRedis = true;
                }
            } catch (Exception $e) {
                self::$useRedis = false;
            }
        }
    }

    /**
     * Get cached item by key
     *
     * @param string $key
     * @return mixed|null Returns cached data or null if expired/missing
     */
    public static function get($key) {
        self::init();

        if (self::$useRedis && self::$redis) {
            $data = self::$redis->get($key);
            return $data !== false ? json_decode($data, true) : null;
        }

        $filepath = self::$cacheDir . md5($key) . '.cache';
        if (!file_exists($filepath)) {
            return null;
        }

        $content = @file_get_contents($filepath);
        if (!$content) {
            return null;
        }

        $item = json_decode($content, true);
        if (!is_array($item) || !isset($item['expire']) || !isset($item['data'])) {
            @unlink($filepath);
            return null;
        }

        if (time() > $item['expire']) {
            @unlink($filepath);
            return null;
        }

        return $item['data'];
    }

    /**
     * Store item in cache with TTL (Time To Live in seconds)
     *
     * @param string $key
     * @param mixed $data
     * @param int $ttl Default 300 seconds (5 minutes)
     * @return bool
     */
    public static function set($key, $data, $ttl = 300) {
        self::init();

        if (self::$useRedis && self::$redis) {
            return self::$redis->setex($key, $ttl, json_encode($data));
        }

        $filepath = self::$cacheDir . md5($key) . '.cache';
        $item = array(
            'expire' => time() + $ttl,
            'data'   => $data
        );

        return @file_put_contents($filepath, json_encode($item)) !== false;
    }

    /**
     * Remove a single cached key
     *
     * @param string $key
     * @return bool
     */
    public static function forget($key) {
        self::init();

        if (self::$useRedis && self::$redis) {
            return self::$redis->del($key) > 0;
        }

        $filepath = self::$cacheDir . md5($key) . '.cache';
        if (file_exists($filepath)) {
            return @unlink($filepath);
        }
        return true;
    }

    /**
     * Flush all cached items
     */
    public static function flush() {
        self::init();

        if (self::$useRedis && self::$redis) {
            return self::$redis->flushDB();
        }

        $files = glob(self::$cacheDir . '*.cache');
        if ($files) {
            foreach ($files as $file) {
                @unlink($file);
            }
        }
        return true;
    }
}
?>
