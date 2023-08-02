<?php

namespace Ivannofick\Laravelminifyobfuscate\Helpers;

/*
 * This file is Helpers Minify Html
 *
 * (c) ivannofick <ivannofick@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class HelpersHtml
{
    /*
    |--------------------------------------------------------------------------
    | Replace Function
    |--------------------------------------------------------------------------
    |
    | This method is responsible for minifying the HTML code by removing unnecessary
    | whitespaces, line breaks, and extra spaces. It uses regular expressions to find
    | and replace patterns that match such whitespaces in the given HTML code.
    | The result is a minified version of the HTML code with reduced whitespace.
    |
    */
    public static function replace($view)
    {
        // Regular expressions to match different types of whitespaces and line breaks.
        $search = array(
            // Match whitespaces following a closing tag (greater-than sign) and remove them.
            '/\>[^\S ]+/s',
            
            // Match whitespaces preceding an opening tag (less-than sign) and remove them.
            '/[^\S ]+\</s',
            
            // Match multiple consecutive whitespaces or line breaks and replace them with a single space.
            '/(\s)+/s'
        );

        // Replacements for the matched patterns in the HTML code.
        $replace = array(
            '>',
            '<',
            '\\1'
        );

        // Perform regular expression replacements on the input HTML code.
        $minifyCode = trim(preg_replace($search, $replace, $view));

        // Return the minified version of the HTML code.
        return $minifyCode;
    }
}
