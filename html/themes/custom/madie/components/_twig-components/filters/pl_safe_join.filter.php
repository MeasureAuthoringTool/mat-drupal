<?php
/**
 * @file
 * Add "safe_join" filter for Pattern Lab.
 *
 * Bring Drupal filters in just so Pattern Lab doesn't bork.
 */
use Twig\TwigFilter;

$filter = new TwigFilter('safe_join', function ($string) {
  return $string;
});
