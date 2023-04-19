<?php

namespace Coachdenis\IdempotencyKata\PhotoGallery;

class UpdateImage
{
    public function __construct(public \DateTimeImmutable $updatedAt,
                                public string $imageName,
                                public string $imageBlob)
    {
    }
}