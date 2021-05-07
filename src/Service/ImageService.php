<?php


namespace App\Service;


use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService extends AbstractController
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }


    /**
     * @param UploadedFile $file
     * @param Image $image
     * @return string
     * @throws FileException
     */
    public function upload(UploadedFile $file, Image $image)
    {
        $name = uniqid() . '.' . $file->guessExtension();
        $file->move($image->getPath(), $name);
        $this->resize($name, $image);
        return $name;
    }

    /**
     * @param $name
     * @param Image $image
     */
    public function resize($name, Image $image)
    {
        $fullpath = $image->getPath() . $name;
        list($iwidth, $iheight) = getimagesize($fullpath);
        $ratio = $iwidth / $iheight;
        $width = $image->getWidth();
        $height = $image->getHeight();
        $prefix = $image->getPrefix();
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }
        $img = $this->imagine->open($fullpath);
        $img->resize(new Box($width, $height))->save($image->getPath() . $prefix . $name);
    }

    /**
     * @param $imageName
     * @param Image $image
     * @return string
     */
    public function getImageURL($imageName, Image $image)
    {
        return $image->getUrl() . $image->getPrefix() . $imageName;
    }
}