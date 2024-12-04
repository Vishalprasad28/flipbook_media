<?php

namespace Drupal\flipbook_media\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'flipbook_media' formatter.
 *
 * @FieldFormatter(
 *   id = "flipbook_media_formatter",
 *   label = @Translation("FlipBook Media Formatter"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class FlipBookMediaFormatter extends FlipBookMediaFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'action_text' => '',
      'default_turtl_id' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      /** @var \Drupal\Core\Field\FieldItemInterface $item */
      if ($item->isEmpty()) {
        continue;
      }

      $elements[$delta] = [
        '#theme' => 'media_flipbook',
        '#url' => $item->value,
        '#action_text' => $this->getSetting('action_text'),
        '#default_turtl_id' => $this->getSetting('default_turtl_id'),
      ];
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return parent::settingsForm($form, $form_state) + [
      'action_text' => [
        '#type' => 'textfield',
        '#title' => $this->t('Action Text'),
        '#default_value' => $this->getSetting('action_text'),
      ],
      'default_turtl_id' => [
        '#type' => 'textfield',
        '#title' => $this->t('Action Text'),
        '#default_value' => $this->getSetting('default_turtl_id'),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Action text: %action_text', [
      '%action_text' => $this->getSetting('action_text'),
    ]);
    $summary[] = $this->t('Default Turtl Id: %default_turtl_id', [
      '%default_turtl_id' => $this->getSetting('default_turtl_id'),
    ]);
    return $summary;
  }

}
