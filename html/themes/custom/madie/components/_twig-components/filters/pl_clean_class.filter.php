<?php
/**
 * @file
 * Add "clean_class" filter for Pattern Lab.
 *
 * Bring Drupal filters in just so Pattern Lab doesn't bork.
 */
use Twig\TwigFilter;

$filter = new TwigFilter('clean_class', function ($string) {
  return $string;
});
