<?php

if (! function_exists('mb_split')) {
    /**
     * Polyfill mb_split for PHP WASM builds without oniguruma.
     * Supports bracket expressions, \w \W \s \S \d \D, and quantifiers.
     */
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        $converted = preg_replace_callback(
            '/\\\\[wWsSdD]|\{[0-9]+(,[0-9]*)?\}/',
            function ($m) {
                return match ($m[0]) {
                    '\\w' => '[[:alnum:]_]',
                    '\\W' => '[^[:alnum:]_]',
                    '\\s' => '[[:space:]]',
                    '\\S' => '[^[:space:]]',
                    '\\d' => '[0-9]',
                    '\\D' => '[^0-9]',
                    default => $m[0],
                };
            },
            $pattern
        );

        $delimited = '~' . str_replace('~', '\~', $converted) . '~u';

        return $limit > 0
            ? preg_split($delimited, $string, $limit)
            : preg_split($delimited, $string);
    }
}