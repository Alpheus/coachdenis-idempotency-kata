<?php

namespace Tests\Lesson01;

use Coachdenis\IdempotencyKata\PhotoGallery\CreateImage;
use Coachdenis\IdempotencyKata\PhotoGallery\PhotoGallery;
use Coachdenis\IdempotencyKata\PhotoGallery\SolutionPhotoGallery;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DuplicateInsert extends TestCase
{
    protected PhotoGallery $gallery;

    /**
     * @todo Start here, remove this function.
     */
    #[Before]
    protected function Instructions(): void
    {
        $this->markTestSkipped();
    }

    #[Before]
    protected function Gallery(): void
    {
//        $this->gallery = new SolutionPhotoGallery(); // Replace with your implementation
    }

    #[Test]
    public function Accidental_FastDoubleClick_OnCreate_ShouldSave_Once()
    {
        $createImage = self::THE_BANANA_IMAGE();
        $createImage2 = clone $createImage;
        $createImage3 = self::RANDOM_IMAGE();

        $this->gallery->saveImage($createImage);
        $this->gallery->saveImage($createImage2);

        Assert::assertCount(1, $this->gallery->list());

        $this->gallery->saveImage($createImage3);

        Assert::assertCount(2, $this->gallery->list());

        Assert::assertArrayHasKey($createImage->imageName, $this->gallery->list());
        Assert::assertArrayHasKey($createImage3->imageName, $this->gallery->list());
    }

    #[Test]
    public function Rapid_Save_On_Two_Different_Images_Is_Not_Accidental()
    {
        $createImage = self::THE_BANANA_IMAGE();
        $createImage2 = self::RANDOM_IMAGE();

        $this->gallery->saveImage($createImage);
        $this->gallery->saveImage($createImage2);

        Assert::assertCount(2, $this->gallery->list());
    }

    #[Test]
    public function Delayed_Double_Send_Should_Not_Interfere_With_Update()
    {
        $createImage = self::THE_BANANA_IMAGE();
        $updateImage = self::UPDATE_BANANA_IMAGE();

        $this->gallery->saveImage($createImage);
        $this->gallery->saveImage($updateImage);

        $createImage2 = self::THE_BANANA_IMAGE(); // Updated time stamp
        $this->gallery->saveImage($createImage2);

        Assert::assertCount(1, $this->gallery->list());
        Assert::assertArrayHasKey($createImage->imageName, $this->gallery->list());

        $appliedCreate = $this->gallery->list()[$createImage->imageName];

        Assert::assertEquals($appliedCreate->createdAt, $createImage->createdAt);
    }

    private static function THE_BANANA_IMAGE(): CreateImage
    {
        $createdAt = new \DateTimeImmutable();
        $imageName = 'IMG_001.jpg';
        $imageBlob = base64_encode(file_get_contents(__DIR__ . '/../assets/cute-banana.jpg'));

        return new CreateImage($createdAt, $imageName, $imageBlob);
    }

    private static function RANDOM_IMAGE(): CreateImage
    {
        $createdAt = new \DateTimeImmutable();
        $imageName = self::RANDOM_STRING(15);
        $imageBlob = base64_encode(self::RANDOM_STRING(1024));

        return new CreateImage($createdAt, $imageName, $imageBlob);
    }

    private static function RANDOM_STRING($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private static function UPDATE_BANANA_IMAGE()
    {
        $createdAt = new \DateTimeImmutable();
        $imageName = 'IMG_001.jpg';
        $imageBlob = base64_encode(self::RANDOM_STRING(1024));

        return new CreateImage($createdAt, $imageName, $imageBlob);
    }
}