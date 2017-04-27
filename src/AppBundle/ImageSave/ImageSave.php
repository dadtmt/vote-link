<?php
namespace AppBundle\ImageSave;

use Doctrine\ORM\Mapping as ORM;
use GuzzleHttp\Client;
use Imagine\Image\Box;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ImageSave
{
    public static function save($url)
    {
        try {
            // Generate a version 5 (name-based and hashed with SHA1) UUID object
            $uuid5 = Uuid::uuid5(Uuid::NAMESPACE_DNS, $url);
            $filename = $uuid5->toString();
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $client = new Client();
            $client->request('GET', $url, ['sink' => "../var/image/".$filename.".".$ext]);

            $imagine = new \Imagine\Gd\Imagine();
            $image = $imagine->open("../var/image/".$filename.".".$ext);
            $image->resize(new Box(200, 200))->save("../web/image/".$filename.".".$ext);

            return 'image/'.$filename.'.'.$ext;
        } catch (\Exception $e) {
            // Some dependency was not met. Either the method cannot be called on a
            // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
            return null;
        }
    }
}
