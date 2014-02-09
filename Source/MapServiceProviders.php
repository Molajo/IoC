<?php
/**
 * Map Service Provider Namespaces
 *
 * @package    Molajo
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 */
namespace Molajo\IoC;

use CommonApi\Exception\RuntimeException;
use CommonApi\Resource\MapInterface;

/**
 * Map Service Provider Namespaces
 *
 * @author     Amy Stephen
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright  2014 Amy Stephen. All rights reserved.
 * @since      1.0
 */
class MapServiceProviders implements MapInterface
{
    /**
     * Services Folders
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_folders = array();

    /**
     * IoC Service Provider Namespaces - lookup table
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_namespace_prefix = 'Molajo\Service';

    /**
     * Service Aliases
     *
     * @var     array
     * @since   1.0
     */
    protected $service_provider_aliases = array();

    /**
     * Service Provider Alias Filename
     *
     * @var    string
     * @since  1.0
     */
    protected $service_provider_alias_filename;

    /**
     * Constructor
     *
     * @param  array  $service_provider_folders
     * @param  string $service_provider_namespace_prefix
     * @param  null   $service_provider_alias_filename
     *
     * @since  1.0
     */
    public function __construct(
        array $service_provider_folders = array(),
        $service_provider_namespace_prefix = 'Molajo\Service',
        $service_provider_alias_filename = null
    ) {
        $this->service_provider_folders          = $service_provider_folders;
        $this->service_provider_namespace_prefix = $service_provider_namespace_prefix;
        $this->service_provider_aliases          = array();

        if ($service_provider_alias_filename === null) {
            $this->service_provider_alias_filename = __DIR__ . '/Files/Output/ServiceProviderAliases.json';
        } else {
            $this->service_provider_alias_filename = $service_provider_alias_filename;
        }
    }

    /**
     * Create resource map of folder/file locations and Fully Qualified Namespaces
     *
     * @return  object
     * @since   1.0
     */
    public function createMap()
    {
        $this->MapServiceProviders();

        ksort($this->service_provider_aliases);

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            file_put_contents(
                $this->service_provider_alias_filename,
                json_encode(
                    $this->service_provider_aliases,
                    JSON_PRETTY_PRINT
                )
            );
        } else {
            file_put_contents(
                $this->service_provider_alias_filename,
                json_encode($this->service_provider_aliases)
            );
        }

        return $this;
    }

    /**
     * Map IoCC Dependency Injection Handler Namespaces
     *
     * @param   array $service_provider_folders
     *
     * @since   1.0
     * @return  $this
     */
    protected function MapServiceProviders()
    {
        if (is_array($this->service_provider_folders) && count($this->service_provider_folders) > 0) {
        } else {
            return $this;
        }

        $services = array();

        foreach ($this->service_provider_folders as $folder) {

            $temp = $this->getServiceProviderFolders($folder);

            if (is_array($temp) && count($temp) > 0) {
                foreach ($temp as $service_name => $service_provider_namespace_namespace) {
                    $this->service_provider_aliases[$service_name]
                        = $service_provider_namespace_namespace;
                }
            }
        }

        ksort($this->service_provider_aliases);

        return $this;
    }

    /**
     * Get IoC Handler Folders
     *
     * @param   string $service_provider_folder
     *
     * @since   1.0
     * @throws  \CommonApi\Exception\RuntimeException
     * @return  array
     */
    protected function getServiceProviderFolders($service_provider_folder)
    {
        if (is_dir($service_provider_folder)) {
        } else {
            throw new RuntimeException
            ('Container: getServiceProviderFolders Failed. Folder does not exist ' . $service_provider_folder);
        }

        $temp_folders = array();

        $temp = array_diff(scandir($service_provider_folder), array('.', '..'));

        foreach ($temp as $item) {
            if (is_dir($service_provider_folder . '/' . $item)) {
                $temp_folders[$item] = $this->service_provider_namespace_prefix . '\\' . $item;
            }
        }

        return $temp_folders;
    }
}
