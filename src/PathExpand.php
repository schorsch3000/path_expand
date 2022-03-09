<?php
namespace PathExpand;
use function array_pop;
use function array_unshift;
use function getcwd;
use function strpos;
use function substr;

class PathExpand
{
    public function PathExpand($path, $resolveRelative = true)
    {
        if (substr($path, 0, 2) === "~/") {
            $path = $_SERVER["HOME"] . substr($path, 1);
        }
        if ($path === "~") {
            $path = $_SERVER["HOME"];
        }

        if (true === $resolveRelative) {
            $resolveRelative = getcwd();
        }
        if (false !== $resolveRelative && "/" !== substr($path, 0, 1)) {
            $path = $resolveRelative . "/" . $path;
        }
        do {
            $was = $path;
            $path = str_replace("/./", "/", $path);
            $path = str_replace("//", "/", $path);
            $changed = $path !== $was;
        } while ($changed);
        do {
            $was = $path;
            if (false !== strpos($path, "../")) {
                $oldPath = explode("/", $path);
                $path = [];
                while (count($oldPath)) {
                    $element = array_pop($oldPath);
                    if ($element === "..") {
                        array_pop($oldPath);
                        continue;
                    }
                    array_unshift($path, $element);
                }
                $path = implode("/", $path);
            }
            $changed = $path !== $was;
        } while ($changed);
        return $path;
    }
}
