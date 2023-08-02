<?php

namespace Ivannofick\Laravelminifyobfuscate\Middleware;

use Ivannofick\Laravelminifyobfuscate\Helpers\HelpersHtml;
use Ivannofick\Laravelminifyobfuscate\Middleware\MinifyBladeOutput;
use Ivannofick\Laravelminifyobfuscate\Helpers\HelpersJavascript;


class MinifyHtml extends MinifyBladeOutput
{
    /**
     * Apply minification and JavaScript obfuscation to the 'script' tags in the HTML document.
     * This method first minifies the HTML document by removing any unnecessary white spaces and newlines,
     * then it processes the JavaScript code within the 'script' tags, and finally, it applies obfuscation
     * to the JavaScript code to make it harder to understand for human readers.
     *
     * @return string The modified HTML document with minified and obfuscated JavaScript code.
     */
    protected function apply()
    {
        $minifyTagJavascript = $this->minifyTagJavascript('script');
        $html = HelpersHtml::replace($minifyTagJavascript);
        $this->loadDom($html, true);
        return trim(static::$dom->saveHtml());

    }
    
    /**
     * Minify and apply JavaScript obfuscation to the given HTML tag.
     * This method finds all elements with the specified HTML tag (e.g., 'script') in the document,
     * minifies their content by removing unnecessary white spaces and newlines, and then applies
     * JavaScript obfuscation to the code to make it harder to understand for human readers.
     *
     * @param string $tag The HTML tag to be minified and obfuscated (e.g., 'script').
     * @return string The modified HTML document with minified and obfuscated JavaScript code for the specified tag.
     */
    protected function minifyTagJavascript($tag)
    {
        $getTagJs = $this->getTagDomHtml($tag);
        $javascript = new HelpersJavascript();
        foreach ($getTagJs as $el) {
            $value = $javascript->replace($el->nodeValue);
            $el->nodeValue = '';
            $el->appendChild(static::$dom->createTextNode($value));
            if ($el->hasAttribute('ignore-code-obfuscate')) {
                continue;
            }
        }
        return static::$dom->saveHtml();
    }
}
