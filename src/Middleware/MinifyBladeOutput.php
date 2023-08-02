<?php

namespace Ivannofick\Laravelminifyobfuscate\Middleware;

use Closure;
use DOMDocument;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/*
 * This file is Minify Blade Gengerator
 *
 * (c) ivannofick <ivannofick@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class MinifyBladeOutput
{
    // The DOMDocument instance for parsing and modifying HTML content.
    protected static $dom;

    // Abstract method that needs to be implemented by subclasses to apply specific obfuscation rules.
    abstract protected function apply();

    /*
    |--------------------------------------------------------------------------
    | Middleware Handle Method
    |--------------------------------------------------------------------------
    |
    | This method is called when the middleware is executed. It handles the logic
    | of applying the obfuscation process to the response content if the execution
    | conditions are met.
    |
    */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if ($this->executionProcess($request, $response)) {
            if ($response->headers->get('content-type') === 'text/html; charset=UTF-8') {
                $content = $response->getContent();
                $this->loadDom($content);
                $response->setContent($this->apply());
            }
        }
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | Execution Process
    |--------------------------------------------------------------------------
    |
    | Check various conditions to determine if the obfuscation process should be executed.
    | If the 'laravel_obfuscate_conditional' configuration is set to false, or the response
    | type is JsonResponse, BinaryFileResponse, or StreamedResponse, or the current route
    | is in the list of ignored routes, then obfuscation will not be applied.
    |
    */
    protected function executionProcess($request, $response)
    {
        if (!config('laravelobfuscate.laravel_obfuscate_conditional')) {
            return false;
        }
        if (
            $response instanceof JsonResponse
            || $response instanceof BinaryFileResponse
            || $response instanceof StreamedResponse
        ) {
            return false;
        }

        if (!$this->ignoredRoutes($request, $response)) {
            return false;
        }
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Load DOM Document
    |--------------------------------------------------------------------------
    |
    | Load the HTML content into the DOMDocument for manipulation.
    | If a DOMDocument instance already exists and the 'force' parameter is false,
    | it won't reload the content.
    |
    */
    protected function loadDom($html, bool $force = false)
    {
        if (static::$dom instanceof DOMDocument) {
            if (!$force) {
                return;
            }
        }

        static::$dom = new DOMDocument();
        @static::$dom->loadHTML($html, LIBXML_HTML_NODEFDTD | LIBXML_SCHEMA_CREATE);
    }

    /*
    |--------------------------------------------------------------------------
    | Get DOM Elements by Tag Name
    |--------------------------------------------------------------------------
    |
    | Get DOM elements based on the provided tag name.
    | It retrieves the elements from the loaded DOMDocument instance.
    | Elements with the 'ignore-code-obfuscate' attribute are excluded.
    |
    */
    protected function getTagDomHtml($tags)
    {
        $result = [];
        $element = static::$dom->getElementsByTagName($tags);
        foreach ($element as $el) {
            $value = $el->nodeValue;
            if ($this->isEmptyDom($value)) {
                continue;
            }
            $result[] = $el;
        }
        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Check if DOM Element is Empty
    |--------------------------------------------------------------------------
    |
    | Check if the value of a DOM element is empty or contains only whitespace.
    |
    */
    protected function isEmptyDom($value)
    {
        return preg_match("/^\s*$/", $value);
    }

    /*
    |--------------------------------------------------------------------------
    | Append DOM Elements with Backup Content
    |--------------------------------------------------------------------------
    |
    | Replace the content of selected DOM elements with backup content.
    | The 'append' method is used to restore the original content of specific elements
    | after the obfuscation process has been applied.
    |
    */
    protected function append($function, $tags, $backup)
    {
        $index = 0;
        foreach ($this->{$function}($tags) as $el) {
            $el->nodeValue = '';
            $el->appendChild(static::$dom->createTextNode($backup[$index]->nodeValue));
            $index++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Check if the Current Route Should be Ignored
    |--------------------------------------------------------------------------
    |
    | Check if the current route should be ignored based on the list of ignored routes
    | specified in the 'ignore_route' configuration. If the current route matches any
    | of the ignored patterns, the obfuscation process will not be applied.
    |
    */
    protected function ignoredRoutes($request, $response)
    {
        $ignoredRoutes = config('laravelobfuscate.ignore_route');
        foreach ($ignoredRoutes as $route) {
            if ($request->is($route)) {
                return false;
            }
        }
        return true;
    }
}
