<?php

namespace Maize\Saml2Sp\Support;

class UrlUtils
{
    public static function sanitizeUrl(?string $url): ?string
    {
        if (is_null($url)) {
            return null;
        }

        $url = urldecode($url);

        if (! parse_url($url, PHP_URL_SCHEME)) {
            $url = 'http://'.$url;
        }

        return $url;
    }

    public static function getUrlDomain(?string $url): ?string
    {
        if (is_null($url)) {
            return null;
        }

        $url = self::sanitizeUrl($url);
        $url = parse_url($url, PHP_URL_HOST);
        $url = explode('.', $url);
        $url = array_slice($url, -2);

        return implode('.', $url);
    }
}
