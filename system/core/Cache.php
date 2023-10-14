<?php
namespace Core;
use Interfaces\BasicStaticInterface;
use Core\App;
class Cache implements BasicStaticInterface{
    public static $duration = 0;//0 = unlimited
    public static $userFolder = false;
    
    function __construct(){}
    
    /**
     * It keeps the personal runtime data - each cache is to a unique user
     */
    public static function set($k, $v){
        $file = CACHE_PATH.self::getUserFolder().$k;
        self::saveCachFile($file, $v);
    }

    public static function get($k){
        $file = CACHE_PATH.self::getUserFolder().$k;
        return self::getCacheFile($file);
    }

    public static function delete($k){
        $file = CACHE_PATH.self::getUserFolder().$k;
        self::deleteCacheFile($file);
    }
    
    //Clear the cache of a unique user - (if logged) is the user ID in the db (else) is a random uniqId
    public static function clear($userId = false){
        $folder = CACHE_PATH.self::getUserFolder($userId);
        self::clearCacheFolder($folder);
    }

    //clear the cache to all users
    public static function clearAll(){
        self::clearCacheFilesAllUsers(CACHE_PATH);
    }
    
    public static function getFileName($file){
        $extension = getConfig('cache_extension') ? ".".getConfig('cache_extension') : "";
        return $file.$extension;
    }

    public static function getUserFolder($userId = false, $duration = false){
        $duration = $duration ?: getConfig('cache_duration');
        switch($duration){
            case 'hour':
                $duration = date('Y-m-d-h');
                break;
            default:
            case 'day':
                $duration = date('Y-m-d');
                break;
            case 'week':
                $duration = date('Y-W');
                break;
            case 'month':
                $duration = date('Y-m');
                break;
            case 'year':
                $duration = date('Y');
                break;
        }
        $folder = ($userId ?: (App::getUserId() ? "USER_".App::getUserId()."_" : '_NULL_')).$duration.'/';
        return $folder;
    }

    //common functions from cache
    //the content is save as json and return its
    /**
     * @fname = path/filename
     */
    public static function saveCachFile($fname, $content = []){
        $fname = self::getFileName($fname);
        $content = ['cache' => $content];
        switch(getConfig('cache_engine')){
            case 'json':
                $content = json_encode($content);
                break;
            default:
            case 'serialize':
                $content = serialize($content);
                break;
        }
        $dir = dirname($fname);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        file_put_contents($fname, $content);

    }

    public static function getCacheFile($fname){
        $fname = self::getFileName($fname);
        if(!file_exists($fname))
            return false;
        $content = file_get_contents($fname);
        
        switch(getConfig('cache_engine')){
            case 'json':
                $content = json_decode($content, true);
                break;
            default:
            case 'serialize':
                $content = unserialize($content);
                break;
        }
        return $content['cache'];
    }

    public static function deleteCacheFile($k){
        $fname = self::getFileName($k);
        if(file_exists($fname)){
            @unlink($fname);
        }
    }

    public static function clearCacheFolder($folder){
        $files = glob($folder . '*');
        foreach($files as $file)
            self::deleteCacheFile($file);

        if (is_dir($folder))
            @rmdir($folder);
    }

    public static function clearCacheFilesAllUsers($path){
        $folders = scandir($path);
        $folders = array_filter($folders, function ($i) use ($path) {
            return is_dir($path . $i) && $i !== '.' && $i !== '..';
        });
        foreach($folders as $f)
            self::clearCacheFolder($path.$f.'/');
    }
}