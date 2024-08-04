<?php

namespace App\Tools;

class Utils
{
    /**
     * Groups an associative array by a given key.
     *
     * @param array $array The array to group.
     * @param string $key The key to group by.
     * @return array The grouped array.
     */
    public static function groupBy(array $array, string $key): array
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
