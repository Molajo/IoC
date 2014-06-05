<?php
/**
 * Map Factory Method Namespaces
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\MapInterface;

/**
 * Map Factory Method Namespaces
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0.0
 */
class MapFactories implements MapInterface
{
    /**
     * Factories Folders
     *
     * @var     array
     * @since  1.0.0
     */
    protected $folders = array();

    /**
     * IoC Factory Method Namespaces - lookup table
     *
     * @var     array
     * @since  1.0.0
     */
    protected $adapter_namespace_prefix = 'Molajo\Factories';

    /**
     * Service Aliases
     *
     * @var     array
     * @since  1.0.0
     */
    protected $adapter_aliases = array();

    /**
     * Factory Method Alias Filename
     *
     * @var    string
     * @since  1.0.0
     */
    protected $adapter_alias_filename;

    /**
     * Constructor
     *
     * @param  array  $folders
     * @param  string $adapter_namespace_prefix
     * @param  null   $adapter_alias_filename
     *
     * @since  1.0.0
     */
    public function __construct(
        array $folders = array(),
        $adapter_namespace_prefix = 'Molajo\Factories',
        $adapter_alias_filename = null
    ) {
        $this->folders                  = $folders;
        $this->adapter_namespace_prefix = $adapter_namespace_prefix;
        $this->adapter_aliases          = array();

        if ($adapter_alias_filename === null) {
            $this->adapter_alias_filename = __DIR__ . '/Files/Output/FactoryMethodAliases.json';
        } else {
            $this->adapter_alias_filename = $adapter_alias_filename;
        }
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  MapFactories
     * @since  1.0.0
     */
    public function createMap()
    {
        $this->MapFactories();

        ksort($this->adapter_aliases);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents(
                $this->adapter_alias_filename,
                json_encode(
                    $this->adapter_aliases,
                    JSON_PRETTY_PRINT
                )
            );
        } else {
            file_put_contents(
                $this->adapter_alias_filename,
                json_encode($this->adapter_aliases)
            );
        }

        return $this;
    }

    /**
     * Map IoCC Dependency Injection Handler Namespaces
     *
     *
     * @since  1.0.0
     * @return  $this
     */
    protected function MapFactories()
    {
        if (is_array($this->folders) && count($this->folders) > 0) {
        } else {
            return $this;
        }

        foreach ($this->folders as $folder) {

            $temp = $this->getFolders($folder);

            if (is_array($temp) && count($temp) > 0) {
                foreach ($temp as $product_name => $adapter_namespace_namespace) {
                    $this->adapter_aliases[$product_name]
                        = $adapter_namespace_namespace;
                }
            }
        }

        ksort($this->adapter_aliases);

        return $this;
    }

    /**
     * Get Folders
     *
     * @param   string $adapter_folder
     *
     * @since  1.0.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @return  array
     */
    protected function getFolders($adapter_folder)
    {
        if (is_dir($adapter_folder)) {
        } else {
            throw new RuntimeException
            ('Container: getFolders Failed. Folder does not exist ' . $adapter_folder);
        }

        $temp_folders = array();

        $temp = array_diff(scandir($adapter_folder), array('.', '..'));

        foreach ($temp as $item) {
            if (is_dir($adapter_folder . '/' . $item)) {
                $temp_folders[$item] = $this->adapter_namespace_prefix . '\\' . $item;
            }
        }

        return $temp_folders;
    }
}
