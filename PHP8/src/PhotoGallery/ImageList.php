<?php

namespace Coachdenis\IdempotencyKata\PhotoGallery;

/* Indexes by imageName. Array elements carry properties from their commands (Create/Update) */
interface ImageList extends \ArrayAccess
{
}