# CssManager

<img title="Build Status Images" src="https://travis-ci.org/danielarbex/css-manager.svg">

CssManager is a class for easy management of class in css files with php.

## Use

### cssToArray()
```
/**
 * @var $cssContent 
 * .class_name {
 *  width: 100%;
 * }
 */
$cssContent = file_get_contents(__DIR__ . '/css/css.css');
$cssManager->loadCss($cssContent)
           ->cssToArray();
           
/**
 * array(1) {
 *  ["main"]=>
 *      array(1) {
 *      [".class_name"]=>
 *          array(1) {
 *          ["width"]=>
 *              string(5) " 100%"
 *          }
 *      }
 * }
 */
var_dump($cssManager->parsed);
```

### arrayToCss()
```
/**
 * @var $cssContent 
 * .class_name {
 *  width: 100%;
 * }
 */
$cssContent = file_get_contents(__DIR__ . '/css/css.css');
$cssManager->loadCss($cssContent)
           ->cssToArray();
           
/**
 * string(32) ".class_name {
 *  width:  100%;
 * }
 */
var_dump($cssManager->parsed);
```

### Changing property
```
/**
 * @var $cssContent 
 * .class_name {
 *  width: 100%;
 * }
 */
$cssContent = file_get_contents(__DIR__ . '/css/css.css');
$cssManager->loadCss($cssContent)
           ->cssToArray();
           
$cssManager->parsed['main']['.class_name']['float'] = ' left';
           
/**
 * string(47) ".class_name {
 *  width:  100%;
 *  float:  left;
 * }
 */
var_dump($cssManager->arrayToCss());
```


License
----

MIT

Copyright (c) 2015 danielarbex

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.