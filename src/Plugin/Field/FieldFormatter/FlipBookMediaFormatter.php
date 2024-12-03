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
  public static function getUrlRegexPattern() {
    return '/^https:\/\/insights\.ltts\.com\/story\/.*$/';
  }

  /**
   * {@inheritdoc}
   */
  public static function getValidUrlExampleStrings(): array {
    return [
      'https://insights.ltts.com/story/industrial-products-service-portfolio/',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'action_text' => '',
      ] + parent::defaultSettings();
  }


  /**
   * {@inheritdoc}
   */
  public static function deriveMediaDefaultNameFromUrl($url) {
    $matches = [];
    $pattern = static::getUrlRegexPattern();
    preg_match_all($pattern, $url, $matches);
    if (!empty($matches[1][0])) {
      return self::t('FlipBook from @url', [
        '@url' => $url,
      ]);
    }
    return parent::deriveMediaDefaultNameFromUrl($url);
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
      $matches = [];
      $pattern = static::getUrlRegexPattern();
      preg_match_all($pattern, $item->value, $matches);
      if (empty($matches[1][0])) {
        continue;
      }
      $elements[$delta] = [
        '#theme' => 'media_flipbook',
        '#url' => $matches[1][0],
        '#action_text' => $this->getSetting('action_text'),
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
    return $summary;
  }

}
