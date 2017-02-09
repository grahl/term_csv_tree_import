<?php

namespace Drupal\term_csv_tree_import\Controller;

use Drupal\taxonomy\Entity\Term;

class ImportController {
  protected  $data;
  protected  $vocabulary;

  public function __construct($data, $vocabulary) {
    $this->vocabulary = $vocabulary;
    $parts = explode(PHP_EOL, $data);
    foreach ($parts as $part) {
      $this->data[] = str_getcsv($part);
    }
  }

  public function execute() {
    $processed = 0;
    foreach ($this->data as $row) {
      foreach ($row as $i => $element) {
        $term_existing = taxonomy_term_load_multiple_by_name($element, $this->vocabulary);
        if (count($term_existing) == 0 && !empty($element)) {
          $new_term = Term::create(['name' => $element, 'vid' => $this->vocabulary]);
          if ($i > 0) {
            for ($j = $i -1; $j >= 0; $j--) {
              $previous_term = $this->previousTerm($row[$j]);
              if ($previous_term) {
                $new_term->set('parent', ['target_id' => $previous_term->id()]);
                break;
              }
            }
          }
          $new_term->save();
          $processed++;
        }
      }
    }
    drupal_set_message(t('Imported @count terms.', ['@count' => $processed]));
  }

  private function previousTerm($name) {
    $previous_terms = taxonomy_term_load_multiple_by_name($name, $this->vocabulary);
    return array_shift($previous_terms);
  }
}