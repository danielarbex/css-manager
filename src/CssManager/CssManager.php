<?php

namespace danielarbex;

/**
 * Class CssManager
 * CssManager is a class for manage the css files
 *
 * @package danielarbex
 */
class CssManager
{
    /**
     * @var
     */
    public $css;

    /**
     * @var
     */
    public $parsed;

    /**
     * @param $string
     * @param bool $overwrite
     * @return $this
     */
    public function loadCss($string, $overwrite = false)
    {
        if ($overwrite) {
            $this->css = $string;
        } else {
            $this->css .= $string;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function cssToArray()
    {
        $css = $this->css;
        $css = preg_replace('/\/\*.*?\*\//ms', '', $css);
        $css = preg_replace('/([^\'"]+?)(\<!--|--\>)([^\'"]+?)/ms', '$1$3', $css);
        preg_match_all('/@import.+?\);|@.+?\}[^\}]*?\}/ms', $css, $blocks);
        array_push($blocks[0], preg_replace('/@.+?\}[^\}]*?\}/ms', '', $css));
        $ordered = [];

        foreach ($blocks[0] as $i => $block) {
            if (substr($block, 0, 6) === '@media') {
                $ordered_key = preg_replace('/^(@media[^\{]+)\{.*\}$/ms', '$1', $block);
                $ordered_value = preg_replace('/^@media[^\{]+\{(.*)\}$/ms', '$1', $block);
            } elseif (substr($block, 0, 1) === '@') {
                $ordered_key = $block;
                $ordered_value = $block;
            } else {
                $ordered_key = 'main';
                $ordered_value = $block;
            }

            $ordered[$ordered_key] = preg_split(
                '/([^\'"\{\}]*?[\'"].*?(?<!\\\)[\'"][^\'"\{\}]*?)[\{\}]|([^\'"\{\}]*?)[\{\}]/',
                trim($ordered_value, " \r\n\t"),
                -1,
                PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE
            );
        }

        foreach ($ordered as $key => $val) {
            $new = [];
            for ($i = 0; $i<count($val); $i++) {
                $selector = trim($val[$i], " \r\n\t");

                if (!empty($selector)) {
                    if (!isset($new[$selector])) {
                        $new[$selector] = [];
                    }

                    $rules = @explode(';', $val[++$i]);
                    foreach ($rules as $rule) {
                        $rule = trim($rule, " \r\n\t");
                        if (!empty($rule)) {
                            $rule = array_reverse(explode(':', $rule));
                            $property = trim(array_pop($rule), " \r\n\t");
                            $value = implode(':', array_reverse($rule));

                            if (!isset($new[$selector][$property]) ||
                                !preg_match('/!important/', $new[$selector][$property])) {
                                $new[$selector][$property] = $value;
                            } elseif (preg_match('/!important/', $new[$selector][$property]) &&
                                preg_match('/!important/', $value)) {
                                $new[$selector][$property] = $value;
                            }
                        }
                    }
                }
            }
            $ordered[$key] = $new;
        }
        $this->parsed = $ordered;

        return $this;
    }


    /**
     * @return string
     */
    public function arrayToCss()
    {
        if ($this->parsed) {
            $output = '';
            foreach ($this->parsed as $media => $content) {
                if (substr($media, 0, 6) === '@media') {
                    $output .= $media . " {\n";
                    $prefix = "\t";
                } else {
                    $prefix = "";
                }

                foreach ($content as $selector => $rules) {
                    $output .= $prefix.$selector . " {\n";
                    foreach ($rules as $property => $value) {
                        $output .= $prefix."\t".$property.': '.$value;
                        $output .= ";\n";
                    }
                    $output .= $prefix."}\n\n";
                }
                if (substr($media, 0, 6) === '@media') {
                    $output .= "}\n\n";
                }
            }
            return $output;
        }
    }
}
