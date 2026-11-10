<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom URL Helper Extension for AlphaMindz
 *
 * Automatically appends ?nocache=1 to dynamic routes (student, admin, superadmin, auth, cart, etc.)
 * to force edge proxies (Sucuri/Cloudproxy/CDNs) to bypass cached HTML and always serve fresh user data.
 */

if ( ! function_exists('append_nocache_param'))
{
    function append_nocache_param($url)
    {
        // Don't modify empty URLs or javascript/hash links
        if (empty($url) || $url === '#' || strpos($url, 'javascript:') === 0 || strpos($url, 'mailto:') === 0 || strpos($url, 'tel:') === 0) {
            return $url;
        }

        // Don't add to static files (images, css, js, pdf, zip, etc.)
        if (preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|webp|pdf|zip|mp4|webm|woff|woff2|ttf|eot)(\?.*)?$/i', $url)) {
            return $url;
        }

        // Check if URL already has nocache
        if (strpos($url, 'nocache=') !== FALSE) {
            return $url;
        }

        // Append query parameter
        $separator = (strpos($url, '?') !== FALSE) ? '&' : '?';
        return $url . $separator . 'nocache=1';
    }
}

if ( ! function_exists('site_url'))
{
    function site_url($uri = '', $protocol = NULL)
    {
        $url = get_instance()->config->site_url($uri, $protocol);
        return append_nocache_param($url);
    }
}

if ( ! function_exists('current_url'))
{
    function current_url()
    {
        $CI =& get_instance();
        $url = $CI->config->site_url($CI->uri->uri_string());
        return append_nocache_param($url);
    }
}

if ( ! function_exists('redirect'))
{
    function redirect($uri = '', $method = 'auto', $code = NULL)
    {
        if ( ! preg_match('#^(\w+:)?//#i', $uri))
        {
            $uri = site_url($uri);
        }
        else
        {
            $uri = append_nocache_param($uri);
        }

        // IIS environment likely? Use 'refresh' for better compatibility
        if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
        {
            $method = 'refresh';
        }
        elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
        {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
            {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
                    ? 303
                    : 307;
            }
            else
            {
                $code = 302;
            }
        }

        switch ($method)
        {
            case 'refresh':
                header('Refresh:0;url='.$uri);
                break;
            default:
                header('Location: '.$uri, TRUE, $code);
                break;
        }
        exit;
    }
}
