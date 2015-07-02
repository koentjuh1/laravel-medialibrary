<?php

namespace Spatie\MediaLibrary\UrlGenerator;

use Spatie\MediaLibrary\Exceptions\UrlCouldNotBeDeterminedException;

class LocalUrlGenerator extends BaseUrlGenerator implements UrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     *
     * @throws UrlCouldNotBeDeterminedException
     */
    public function getUrl()
    {

        //nog disk path voor storage path zetten

        if (! string($this->getStoragePath())->startsWith(public_path())) {
            throw new UrlCouldNotBeDeterminedException('The storage path is not part of the public path');
        }

        if (is_null($this->conversion)) {
            return $this->getBaseMediaDirectory().'/'.$this->media->file_name;
        }

        return $this->getBaseMediaDirectory().'/conversions/'.$this->conversion->getName().'.'.$this->conversion->getResultExtension($this->media->getExtension());
    }

    /**
     * Get the directory where all files of the media item are stored.
     *
     * @return string
     */
    protected function getBaseMediaDirectory()
    {
        $baseDirectory = string($this->getStoragePath())->replace(public_path(), '').'/'.$this->media->id;

        return $baseDirectory;
    }

    /**
     * Get the path where the whole medialibrary is stored.
     *
     * @return string
     */
    protected function getStoragePath()
    {
        $diskRootPath = $this->config->get('filesystems.disks.'.$this->config->get('laravel-medialibrary.filesystem').'.root');

        return realpath($diskRootPath);
    }
}