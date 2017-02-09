<?php

namespace Drupal\term_csv_tree_import\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\term_csv_tree_import\Controller\ImportController;

/**
 * Class DefaultForm.
 *
 * @package Drupal\term_csv_tree_import\Form
 */
class DefaultForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['input'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Input'),
      '#description' => $this->t('Enter in the form of: <pre>"element","subelement","subsubelement"</pre>'),
    );

    $form['vocabulary'] = array(
      '#type' => 'select',
      '#title' => $this->t('Taxonomy'),
      '#options' => taxonomy_vocabulary_get_names(),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Import'),

    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $import = new ImportController(
      $form_state->getValue('input'),
      $form_state->getValue('vocabulary')
    );
    $import->execute();
  }

}
