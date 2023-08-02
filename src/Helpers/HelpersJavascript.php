<?php
namespace Ivannofick\Laravelminifyobfuscate\Helpers;

/*
 * This file is Helpers Obfuscate Javascript
 *
 * (c) ivannofick <ivannofick@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class HelpersJavascript
{
    /*
    |--------------------------------------------------------------------------
    | Replace Function
    |--------------------------------------------------------------------------
    |
    | This method is responsible for replacing certain patterns in a given JavaScript code.
    | The method utilizes regular expressions to remove unnecessary whitespaces, comments,
    | and semicolons to minify the JavaScript code.
    |
    */
    public function replace($value, $addSemicolon = true)
    {
        if ($addSemicolon) {
            $value = $this->addSemicolon($value);
        }
        $search = [
            // Regular expression to match and remove comments, whitespaces, and new lines.
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',

            // Regular expression to remove unnecessary whitespaces, semicolons, and comments around code elements.
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',

            // Regular expression to remove multiple consecutive semicolons followed by a closing brace.
            '#;+\}#',

            // Regular expression to remove unnecessary quotes from object keys.
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',

            // Regular expression to convert JavaScript object key access using brackets to dot notation.
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i',
        ];

        // Replace the matched patterns with corresponding replacements.
        $replace = [
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3',
        ];

        // Apply the regular expression replacement to the given value and trim the result.
        return trim(preg_replace($search, $replace, $value));
    }

    /*
    |--------------------------------------------------------------------------
    | Obfuscate Function
    |--------------------------------------------------------------------------
    |
    | This method takes a JavaScript code as input and returns an obfuscated version of it.
    | It converts the given code into an array of character codes (ord values) and generates
    | a template using eval to recreate the original code using String.fromCharCode method.
    | Then, it applies the 'replace' method to the template, resulting in obfuscated code.
    |
    */
    public function obfuscate($value)
    {
        $key = $this->encryptKey();
        $ords = [];
    
        // Convert each character in the input value to its ASCII code and store in the 'ords' array.
        for ($i = 0; $i < strlen($value); $i++) {
            // Add the key to the ASCII code before storing it in the 'ords' array.
            $ords[] = ord($value[$i]) + $key;
        }
    
        // Create a template using eval to generate obfuscated code based on the 'ords' array.
        $template = sprintf('
        eval(((_, __, ___, ____, _____, ______, _______) => {
            ______[___](x => _______[__](String[____](x - %d)));
            return _______[_](_____)
        })("join", "push", "forEach", "fromCharCode", "", %s, []))
    
        ', $key, json_encode($ords));
        
        // Apply the 'replace' method to the template, resulting in the obfuscated code.
        return $this->replace($template);
    }
    
    protected function encryptKey()
    {        
        $key = config('laravelobfuscate.laravel_obfuscate_secure_key');
        return strlen(md5(base64_encode($key)));
    }


    /*
    |--------------------------------------------------------------------------
    | addSemicolon Function
    |--------------------------------------------------------------------------
    | This function is used by the 'obfuscate' method to add semicolons (;) to appropriate code lines.
    | Before adding semicolons, this function also performs several preprocessing steps on the code:
    | - Removing comments from the code, both single-line comments (//) and multi-line comments .
    | - Removing newline characters from strings enclosed in backticks (``).
    | Additionally, this function applies several regular expression patterns to each code line to determine if
    | the line qualifies for semicolon insertion or not. If the line does not qualify, the function will keep
    | the line unchanged in the final result. Certain special conditions are also checked to determine if a line
    | should have a semicolon added based on the following line.
    |    
    */
  
    protected function addSemicolon($value)
    {
        $value = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/', '', $value);
        $value = preg_replace_callback('/(`[\S\s]*?[^\\\`]`)/', function ($m) {
            return preg_replace('/\n+/', '', $m[1]);
        }, $value);
        
        $result = [];
        $code = explode("\n", trim($value));

        $patternRegex = [
            '#(?:({|\[|\(|,|;|=>|\:|\?|\.|\+|\=))$#',
            '#^\s*$#',
            '#^(do|else)$#',
        ];

        $loop = 0;

        foreach ($code as $line) {
            $loop++;
            $add = false;
            $shouldInsert = true;

            foreach ($patternRegex as $pattern) {
                $match = preg_match($pattern, trim($line));
                $shouldInsert = $shouldInsert && (bool) !$match;
            }

            if ($shouldInsert) {
                $i = $loop;

                while (true) {
                    if ($i >= count($code)) {
                        $add = true;
                        break;
                    }
                    $c = trim($code[$i]);
                    $i++;
                    if (!$c) {
                        continue;
                    }
                    $add = true;
                    $regex = ['#^(\?|\:|\+|\=|,|\.|{|}|\)|\])#'];
                    foreach ($regex as $r) {
                        $add = $add && (bool) !preg_match($r, $c);
                    }

                    if ($add) {
                        if (preg_match('#(?:\\})$#', trim($line)) && preg_match("#^(else|elseif|else\s*if|catch)#", $c)) {
                            $add = false;
                        }
                    }

                    break;
                }
            }

            if ($add) {
                $result[] = sprintf('%s;', $line);
            } else {
                $result[] = $line;
            }
        }
        return join("\n", $result);
    }
}
