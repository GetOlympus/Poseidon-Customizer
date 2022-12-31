<?php

namespace GetOlympus\Poseidon\Base;

use GetOlympus\Poseidon\Utils\Helpers;

/**
 * Base trait
 *
 * @package    OlympusPoseidonCustomizer
 * @subpackage Base
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.2
 *
 */

trait BaseTrait
{
    /**
     * @var mixed
     */
    protected $model;

    /**
     * Enqueue scripts and styles.
     *
     * @param  string  $path
     * @param  string  $folder
     *
     * @return string
     */
    public static function copyFile($path, $folder) : string
    {
        // Update details
        $basename = basename($path);
        $source   = rtrim(dirname($path), S);
        $target   = OL_POSEIDON_PATH.'app'.S.'assets'.S.$folder;

        // Update file path on dist accessible folder
        Helpers::copyFile($source, $target, $basename);

        // Return file uri
        return esc_url(OL_POSEIDON_URI.'/'.$folder.'/'.$basename);
    }

    /**
     * Retrieve class details.
     *
     * @return array
     */
    protected function getClass() : array
    {
        // Retrieve path to Resources and shortname's class
        $class = new \ReflectionClass(get_class($this));

        // Return a simple array
        return [
            'name'      => $class->getShortName(),
            'path'      => $class->getFileName(),
            'resources' => OL_POSEIDON_PATH.'src'.S.'Poseidon'.S.'Resources',
            'root'      => OL_POSEIDON_PATH,
        ];
    }

    /**
     * Gets the value of instance.
     *
     * @return static
     */
    public static function getInstance() : self
    {
        // Get instance
        return new static(false, false, []);
    }

    /**
     * Gets the model.
     *
     * @return mixed
     */
    public function getModel()
    {
        // Get model
        return $this->model;
    }
}
