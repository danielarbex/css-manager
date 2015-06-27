<?php

namespace danielarbex\tests;

use danielarbex\CssManager;
use PHPUnit_Framework_TestCase;

class CssManagerTest extends PHPUnit_Framework_TestCase
{
    public function testLoadCss()
    {
        $cssManager = new CssManager();

        $cssContent = file_get_contents(__DIR__ . '/css/css.css');
        $cssManager->loadCss($cssContent);

        $this->assertNotEmpty($cssManager->css);
    }

    public function testCssToArray()
    {
        $cssManager = new CssManager();

        $cssContent = file_get_contents(__DIR__ . '/css/css.css');
        $cssManager->loadCss($cssContent)
                   ->cssToArray();


        $this->assertArraySubset(['width' => ' 100%'], $cssManager->parsed['main']['.class_name']);
    }

    public function testChangeProperty()
    {
        $cssManager = new CssManager();

        $cssContent = file_get_contents(__DIR__ . '/css/css.css');
        $cssManager->loadCss($cssContent)
                   ->cssToArray();

        $cssManager->parsed['main']['.class_name']['float'] = ' left';

        $this->assertArraySubset(['width' => ' 100%', 'float' => ' left'], $cssManager->parsed['main']['.class_name']);
    }

    public function testArrayToCss()
    {
        $cssManager = new CssManager();

        $cssContent = file_get_contents(__DIR__ . '/css/css.css');
        $actual = $cssManager->loadCss($cssContent)
                             ->cssToArray()
                             ->arrayToCss();

        $this->assertNotEmpty($actual);
    }
}
