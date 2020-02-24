<?php

namespace Drupal\emat_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'CMSBrandingBlock' block.
 *
 * @Block(
 *  id = "emat_cms_branding_block",
 *  admin_label = @Translation("HHS/CMS Branding Block"),
 * )
 */
class CMSBrandingBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'description' => '',
      'facebook_url' => '',
      'twitter_url' => '',
      'linkedin_url' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'text_format',
      '#format' => 'plain_text',
      '#allowed_formats' => ['plain_text'],
      '#title' => $this->t('Description'),
      '#default_value' => $this->configuration['description']['value'],
      '#weight' => '0',
    ];
    $form['facebook_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook Link URL'),
      '#default_value' => $this->configuration['facebook_url'],
      '#weight' => '1',
    ];
    $form['twitter_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Twitter Link URL'),
      '#default_value' => $this->configuration['twitter_url'],
      '#weight' => '2',
    ];
    $form['linkedin_url'] = [
      '#type' => 'url',
      '#title' => $this->t('LinkedIn Link URL'),
      '#default_value' => $this->configuration['linkedin_url'],
      '#weight' => '3',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['description'] = $form_state->getValue('description');
    $this->configuration['facebook_url'] = $form_state->getValue('facebook_url');
    $this->configuration['twitter_url'] = $form_state->getValue('twitter_url');
    $this->configuration['linkedin_url'] = $form_state->getValue('linkedin_url');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['description'] = [
      '#type' => 'processed_text',
      '#text' => $this->configuration['description']['value'],
      '#format' => $this->configuration['description']['format'],
    ];
    $build['facebook_url'] = $this->configuration['facebook_url'];
    $build['twitter_url'] = $this->configuration['twitter_url'];
    $build['linkedin_url'] = $this->configuration['linkedin_url'];
    return $build;
  }

}
