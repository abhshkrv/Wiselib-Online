<?php
// src/Ace/ProjectBundle/Document/ProjectFiles.php
namespace Ace\ProjectBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Ace\ProjectBundle\Repository\ProjectFileRepository")
 */
class ProjectFiles
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Collection
     */
    protected $files;

    /**
     * @MongoDB\Date
     */
    protected $filesTimestamp;
 
   /**
     * @MongoDB\Collection
     */
    protected $images;

    /**
     * @MongoDB\Collection
     */
    protected $sketches;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set files
     *
     * @param collection $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * Get files
     *
     * @return collection $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set images
     *
     * @param collection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Get images
     *
     * @return collection $images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set sketches
     *
     * @param collection $sketches
     */
    public function setSketches($sketches)
    {
        $this->sketches = $sketches;
    }

    /**
     * Get sketches
     *
     * @return collection $sketches
     */
    public function getSketches()
    {
        return $this->sketches;
    }

    /**
     * Set filesTimestamp
     *
     * @param date $filesTimestamp
     */
    public function setFilesTimestamp($filesTimestamp)
    {
        $this->filesTimestamp = $filesTimestamp;
    }

    /**
     * Get filesTimestamp
     *
     * @return date $filesTimestamp
     */
    public function getFilesTimestamp()
    {
        return $this->filesTimestamp;
    }

}
