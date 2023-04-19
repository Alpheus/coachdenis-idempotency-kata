<?php

namespace Coachdenis\IdempotencyKata\PhotoGallery;

class CreateImage
{
    public function __construct(public \DateTimeImmutable $createdAt,
                                public string $imageName,
                                public string $imageBlob)
    {
    }
}