<?php


namespace App\Twig;


use App\Service\Image;
use App\Service\ImageService;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigImageExtension extends AbstractExtension
{
    private $imageService;
    private $parameters;

    public function __construct(ImageService $imageService, ContainerBagInterface $parameters)
    {
        $this->imageService = $imageService;
        $this->parameters = $parameters;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getImageUrl', [$this, 'getURL'])
        ];
    }

    public function getURL(string $name, string $imageType)
    {
        $image = Image::fromArray($this->parameters->get($imageType));
        if (strpos($name, 'https') !== false) {
            return $name;
        }
        return $this->imageService->getImageURL($name, $image);
    }
}