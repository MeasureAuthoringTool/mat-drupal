<?php
/**
 * @file
 * Add "kint" function for Pattern Lab.
 */
use Twig\TwigFunction;

$function = new TwigFunction('kint', function ($string) {
  return $string;
});
