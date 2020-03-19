<?php
use PHPDojo\Helpers\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{

    public function testStringEndsWithShouldBeOk()
    {
        $toSearch = "MyFancyString.theEnd";
        $end = "theEnd";
        $this->assertTrue(StringHelper::endsWith($toSearch, $end));
    }

    public function testStringNotEndsWithShouldNotBeOk()
    {
        $toSearch = "MyFancyString.theEnd";
        $end = "theEndd";
        $this->assertFalse(StringHelper::endsWith($toSearch, $end));
    }

    public function testStringNotEndsWithShouldNotBeOk2()
    {
        $toSearch = "MyFancyString.theEnd";
        $end = "theEn";
        $this->assertFalse(StringHelper::endsWith($toSearch, $end));
    }

    public function testStringSensitiveEndsWithShouldBeNotOk()
    {
        $toSearch = "MyFancyString.theEnd";
        $end = "TheEnd";
        $this->assertFalse(StringHelper::endsWith($toSearch, $end));
    }

    public function testStringSensitiveEndsWithShouldBeOk()
    {
        $toSearch = "MyFancyString.theEnd";
        $end = "TheEnd";
        $this->assertTrue(StringHelper::endsWith($toSearch, $end, false));
    }
}
