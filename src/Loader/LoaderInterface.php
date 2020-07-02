<?php


namespace Scraphp\Loader;


interface LoaderInterface
{
    public function openUrl(string $url);

    public function getText(string $selector): array;

    public function getIterativeText(string $selector4Iteration, array $subSelectionsIndexedByNames): array;

    public function getInnerHTML(string $selector): array;

    public function getAttr(string $selector, string $attribute): array;
}
