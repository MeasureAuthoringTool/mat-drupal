<?php
/**
 * @file
 * Add "render" filter for Pattern Lab.
 *
 * Bring Drupal filters in just so Pattern Lab doesn't bork.
 */
use Twig\TwigFilter;

$filter = new TwigFilter('render', function ($string) {
  return $string;
});
