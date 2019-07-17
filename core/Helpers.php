<?php

namespace core;

class Helpers
{
    // преобразование xml результатов боя в ассоциативный массив
    public static function xml2array ( $xmlObject, $out = array () )
    {
        foreach ((array) $xmlObject as $index => $node) {
            if ($index == '@attributes') {
                return $node;
            }

            $out = (is_object($node)) ? self::xml2array($node) : $node;
        }

        return $out;
    }
}