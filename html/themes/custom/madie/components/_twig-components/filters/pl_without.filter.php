<?php
/**
 * @file
 * Add "without" filter for Pattern Lab.
 *
 * Bring Drupal filters in just so Pattern Lab doesn't bork.
 */
use Twig\TwigFilter;

$filter = new TwigFilter('without', function ($string) {
  return $string;
});
