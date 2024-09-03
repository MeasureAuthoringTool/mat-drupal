<?php
/**
 * @file
 * Add "link" function for Pattern Lab.
 */

use Twig\TwigFunction;

$function = new TwigFunction('link', function ($string) {
  return $string;
});
