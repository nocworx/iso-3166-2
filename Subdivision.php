<?php

namespace L91\ISO_3166_2;

use stdClass;

class Subdivision
{
    /**
     * @return string
     */
    protected static function getSubdivisionFolderPath() : string
    {
        return __DIR__ . '/subdivisions';
    }
    
    /**
     * @param string $countryCode
     *
     * @return string
     */
    protected static function getSubdivisionFilePath(string $countryCode) : string
    {
        return self::getSubdivisionFolderPath() . '/' . $countryCode . '.json';
    }
    
    /**
     * @param string $countryCode
     * @param string $locale
     *
     * @return string
     */
    protected static function getSubdivisionLocaleFilePath(string $countryCode, string $locale) : string
    {
        return self::getSubdivisionFolderPath() . '/' . $countryCode . '/' . $locale . '.json';
    }
    
    /**
     * @param string $path
     * @param bool $assoc
     *
     * @return array|stdClass
     */ 
    protected static function loadJsonFile(string $path, bool $assoc = false) : array|stdClass
    {
        $data = array();
        
        if (! $assoc) {
            $data = new \stdClass();
        }
        
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, $assoc);
        }
        
        return $data;
    }
    
    /**
     * @param string $countryCode
     * @param ?string $locale
     *
     * @return array|stdClass
     */
    protected static function loadSubdivisionFile(string $countryCode, ?string $locale = null) : array|stdClass
    {
        $data = self::loadJsonFile(self::getSubdivisionFilePath($countryCode));
        
        if ($locale) {
            $data = array_merge(
                $data,
                self::loadJsonFile(self::getSubdivisionLocaleFilePath($countryCode, $locale))
            );
        }
        
        return $data;
    }

    /**
     * @param string $countryCode
     * @param string $locale
     *
     * @return array|stdClass
     */
    public static function getSubdivisions(string $countryCode, string $locale = null) : array|stdClass
    {
        return self::loadSubdivisionFile(
            strtolower($countryCode), 
            $locale ? strtolower($locale) : null
        );
    }
}
