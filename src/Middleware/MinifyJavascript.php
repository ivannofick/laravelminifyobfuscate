<?php

namespace Ivannofick\Laravelminifyobfuscate\Middleware;

use Ivannofick\Laravelminifyobfuscate\Helpers\HelpersJavascript;
use Ivannofick\Laravelminifyobfuscate\Middleware\MinifyBladeOutput;

class MinifyJavascript extends MinifyBladeOutput
{
    /**
     * Apply JavaScript obfuscation to script tags in the HTML document.
     * This method iterates through all the script tags found in the document,
     * manipulates the JavaScript code within the tags, and applies obfuscation
     * to the code to make it harder to understand for human readers.
     *
     * @return string The modified HTML document with obfuscated JavaScript code.
     */
    protected function apply()
    {
        $getTagJs = $this->getTagDomHtml('script');
        $javascript = new HelpersJavascript();
        foreach ($getTagJs as $el) {
            $value = $javascript->replace($el->nodeValue);
            if ($el->hasAttribute('ignore-code-obfuscate')) {
                continue;
            }
            $value = $javascript->obfuscate($value);
            $el->nodeValue = '';
            $el->appendChild(static::$dom->createTextNode($value));

        }
        return static::$dom->saveHtml();
    }
}