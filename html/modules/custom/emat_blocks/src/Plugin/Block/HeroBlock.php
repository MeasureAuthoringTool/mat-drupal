<?php

namespace Drupal\emat_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'HeroBlock' block.
 *
 * @Block(
 *  id = "emat_hero_block",
 *  admin_label = @Translation("Hero block"),
 * )
 */
class HeroBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['lead'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Lead text'),
      '#description' => $this->t('Short text to appear under the title.'),
      '#default_value' => $this->configuration['lead'],
      '#rows' => 2,
      '#cols' => 80,
      '#weight' => '1',
    ];

    $form['cta'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('CTA Fields'),
      '#weight' => '2',
    ];

    $form['cta']['cta_box_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA Box Title'),
      '#default_value' => $this->configuration['cta_box_title'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['cta']['cta_box_description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('CTA Box Description'),
      '#default_value' => $this->configuration['cta_box_description'],
      '#weight' => '1',
    ];

    $form['cta']['cta_primary_link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA Primary Link Text'),
      '#default_value' => $this->configuration['cta_primary_link_text'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '2',
    ];
    $form['cta']['cta_primary_link_url'] = [
      '#type' => 'url',
      '#title' => $this->t('CTA Primary Link URL'),
      '#default_value' => $this->configuration['cta_primary_link_url'],
      '#weight' => '3',
    ];

    $form['cta']['cta_secondary_link_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CTA Secondary Link Text'),
      '#default_value' => $this->configuration['cta_secondary_link_text'],
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '4',
    ];
    $form['cta']['cta_secondary_link_url'] = [
      '#type' => 'url',
      '#title' => $this->t('CTA Secondary Link URL'),
      '#default_value' => $this->configuration['cta_secondary_link_url'],
      '#weight' => '5',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['lead'] = $form_state->getValue('lead');
    $cta_values = $form_state->getValue('cta');
    $this->configuration['cta_box_title'] = $cta_values['cta_box_title'];
    $this->configuration['cta_box_description'] = $cta_values['cta_box_description'];
    $this->configuration['cta_primary_link_text'] = $cta_values['cta_primary_link_text'];
    $this->configuration['cta_primary_link_url'] = $cta_values['cta_primary_link_url'];
    $this->configuration['cta_secondary_link_text'] = $cta_values['cta_secondary_link_text'];
    $this->configuration['cta_secondary_link_url'] = $cta_values['cta_secondary_link_url'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'emat_hero_block';
    $build['lead'] = $this->configuration['lead'];
    $build['cta_box_title'] = $this->configuration['cta_box_title'];
    $build['cta_box_description'] = $this->configuration['cta_box_description'];
    $build['cta_primary_link_text'] = $this->configuration['cta_primary_link_text'];
    $build['cta_primary_link_url'] = $this->configuration['cta_primary_link_url'];
    $build['cta_secondary_link_text'] = $this->configuration['cta_secondary_link_text'];
    $build['cta_secondary_link_url'] = $this->configuration['cta_secondary_link_url'];

    return $build;
  }

}
