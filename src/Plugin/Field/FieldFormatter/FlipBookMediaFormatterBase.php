<?php

namespace Drupal\flipbook_media\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\flipbook_media\Plugin\media\Source\FlipBook;
use Drupal\media\Entity\MediaType;

/**
 * Base class for FlipBook Media formatters.
 */
abstract class FlipBookMediaFormatterBase extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'formatter_class' => static::class,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // Only allow choosing this formatter if the media type is configured to
    // use the "Media Remote" source plugin.
    $entity_type_id = $field_definition->getTargetEntityTypeId();
    if ($entity_type_id === 'media') {
      $bundle = $field_definition->getTargetBundle();
      if (!empty($bundle)) {
        $media_type = MediaType::load($bundle);
        if (!empty($media_type)) {
          $source = $media_type->getSource();
          if ($source && ($source instanceof FlipBook)) {
            return TRUE;
          }
        }
      }
    }
    return FALSE;
  }

}
