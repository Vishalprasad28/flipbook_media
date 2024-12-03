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
        // Formatters know how to convert the URL into a default name string.
        $formatter_class = $this->getFormatterClass($media);
        return $formatter_class::deriveMediaDefaultNameFromUrl($source_value);

      default:
        return parent::getMetadata($media, $attribute_name);
    }
  }

  /**
   * Figure out the formatter class to be used on a given media entity.
   *
   * @param \Drupal\media\MediaInterface $media
   *   The media entity we are interested in.
   *
   * @return string
   *   The FQN of the formatter class configured in the `default` media display
   *   for the media source field.
   */
  public function getFormatterClass(MediaInterface $media) {
    $field_definition = $this->getSourceFieldDefinition($media->bundle->entity);

    // @todo There is probably a better way for this class to figure out what
    // formatter class is being used.
    /** @var EntityViewDisplayInterface $display */
    $display = EntityViewDisplay::load('media.' . $media->bundle() . '.default');
    $components = $display->getComponents();
    $formatter_config = $components[$field_definition->getName()] ?? [];
    if (empty($formatter_config['settings']['formatter_class'])) {
      throw new \LogicException('Error');
    }

    // See MediaRemoteFormatterBase::defaultSettings() for where this is
    // defined/enforced.
    return $formatter_config['settings']['formatter_class'];
  }

}
