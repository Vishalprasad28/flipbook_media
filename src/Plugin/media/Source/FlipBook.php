<?php

namespace Drupal\flipbook_media\Plugin\media\Source;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\media\MediaInterface;
use Drupal\media\MediaSourceBase;

/**
 * A media source plugin for non-OEmbed remote media assets.
 *
 * @MediaSource(
 *   id = "flipbook",
 *   label = @Translation("FlipBook"),
 *   allowed_field_types = {"string"},
 *   default_thumbnail_filename = "flipbook-default.webp",
 *   forms = {
 *     "media_library_add" = "Drupal\flipbook_media\Form\FlipBookForm"
 *   }
 * )
 */
class FlipBook extends MediaSourceBase {

  /**
   * Key for "Name" metadata attribute.
   *
   * @var string
   */
  const METADATA_ATTRIBUTE_NAME = 'name';

  /**
   * {@inheritdoc}
   */
  public function getMetadataAttributes() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getMetadata(MediaInterface $media, $attribute_name) {
    $source_value = $this->getSourceFieldValue($media);
    // If the source field is not required, it may be empty.
    switch ($attribute_name) {
      case 'default_name':
        return 'Flipbook for: ' . $source_value;

      default:
        return parent::getMetadata($media, $attribute_name);
    }
  }

}
