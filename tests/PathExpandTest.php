<?php
use PathExpand\PathExpand;
require_once __DIR__ . "/../vendor/autoload.php";
class PathExpandTest extends \Codeception\Test\Unit
{
    public function testJustTilde()
    {
        $pe = new PathExpand();
        self::assertEquals($_SERVER["HOME"], $pe->PathExpand("~"));
    }
    public function testStartingWithTilde1()
    {
        $pe = new PathExpand();
        self::assertEquals($_SERVER["HOME"] . "/", $pe->PathExpand("~/"));
    }
    public function testStartingWithTilde2()
    {
        $pe = new PathExpand();
        self::assertEquals(
            $_SERVER["HOME"] . "/foo/bar",
            $pe->PathExpand("~/foo/bar")
        );
    }
    public function testSimpleResolveRelative()
    {
        $pe = new PathExpand();
        self::assertEquals(getcwd() . "/foo/bar", $pe->PathExpand("./foo/bar"));
    }
    public function testSimpleResolveRelativeNonPWD()
    {
        $pe = new PathExpand();
        self::assertEquals(
            "/tmp/foo/bar",
            $pe->PathExpand("./foo/bar", "/tmp")
        );
    }
    public function testSimpleKeepRelative()
    {
        $pe = new PathExpand();
        self::assertEquals("foo/bar", $pe->PathExpand("foo/bar", false));
    }

    public function testRemoveMultipleSlashes()
    {
        $pe = new PathExpand();
        self::assertEquals("/foo/bar", $pe->PathExpand("/////foo/////bar"));
    }
    public function testRemoveSlashDotSlash()
    {
        $pe = new PathExpand();
        self::assertEquals(
            "/foo/bar",
            $pe->PathExpand("/././././foo/./././/bar")
        );
    }

    public function testRemoveSlashDotDotSlash()
    {
        $pe = new PathExpand();
        self::assertEquals("/bar", $pe->PathExpand("/foo/../bar"));
    }
}
