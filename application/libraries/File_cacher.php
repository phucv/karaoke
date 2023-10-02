<?php

/**
 * Created by Giangndm.
 * User: Giangndm
 *
 * Class File_cacher
 */
class File_cacher {
    public static function cache_set($model, $key, $val) {
        $val = var_export($val, TRUE);
        // HHVM fails at __set_state, so just use object cast for now
        $val = str_replace('stdClass::__set_state', '(object)', $val);
        // Write to temp file first to ensure atomicity
        $tmp = APPPATH . "cache/.$model-$key." . uniqid('', TRUE) . '.tmp';
        file_put_contents($tmp, '<?php $val = ' . $val . ';', LOCK_EX);
        rename($tmp, APPPATH . "cache/$model-$key");
    }

    public static function cache_get($model, $key) {
        @include APPPATH . "cache/$model-$key";
        return isset($val) ? $val : NULL;
    }

    public static function cache_remove($model, $key) {
        $path =  APPPATH . "cache/$model-$key";
        return @unlink($path);
    }
}