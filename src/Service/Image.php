<?php


namespace App\Service;
/**
 * Class ImageUploaderOption
 * @package App\Service
 */
class Image
{
    private $width;
    private $height;
    private $prefix;
    private $path;
    private $url;

    /**
     * Image constructor.
     * @param $width
     * @param $height
     * @param $prefix
     * @param $path
     * @param $url
     */
    public function __construct(int $width, int $height, string $prefix, string $path, string $url)
    {
        $this->width = $width;
        $this->height = $height;
        $this->prefix = $prefix;
        $this->path = $path;
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param array $options
     * @return Image
     */
    public static function fromArray(array $options)
    {
        return new self($options['width'], $options['height'], $options['prefix'], $options['path'], $options['url']);
    }
}