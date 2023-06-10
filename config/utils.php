<?php

class Utils
{
    public static function deleteFile($filepath)
    {
        return unlink($filepath);
    }

    public static function convertCamelString($string)
    {
        $string = ucwords(strtolower($string));

        foreach (array('-', '\'') as $delimiter) {
            if (strpos($string, $delimiter) !== false) {
                $string = implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
            }
        }

        $string = preg_replace('/\s+/', '', $string);
        return $string;
    }

    public static function getCurrentDate($format = "")
    {
        if ($format = "") {
            return time();
        } else {
            return date($format, time());
        }
    }
}
