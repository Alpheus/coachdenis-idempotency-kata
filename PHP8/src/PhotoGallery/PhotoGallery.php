<?php

namespace Coachdenis\IdempotencyKata\PhotoGallery;

interface PhotoGallery
{
    public function saveImage(CreateImage|UpdateImage $image);

    public function list(): ImageList;
}