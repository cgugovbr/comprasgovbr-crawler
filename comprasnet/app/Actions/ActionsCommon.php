<?php

namespace Comprasnet\App\Actions;

use Illuminate\Support\Arr;

class ActionsCommon {

    /**
     * Helper para extrai dados de um array
     *
     * @param $array
     * @param ...$keys
     * @return string|null
     */
    public static function validaDataArray($array, ...$keys) : string|null
    {
        foreach ($keys as $key) {
            $array_dot = Arr::dot($array);
            if (isset($array_dot[$key]) && !is_array($array_dot[$key]) && $array_dot[$key] <> '') {
                return $array_dot[$key];
            }
        }

        return null;
    }
}