<?php
/**
 * Configuration Data Object Dependency Injector
 *
 * @package   Molajo
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 */
namespace Molajo\Services\ConfigurationData;

use Molajo\IoC\Handler\CustomInjector;
use Molajo\IoC\Api\InjectorInterface;
use Molajo\IoC\Exception\InjectorException;



/**
 * Configuration Data Object Dependency Injector
 *
 * @author    Amy Stephen
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 * @copyright 2013 Amy Stephen. All rights reserved.
 * @since     1.0
 */
class ConfigurationDataInjector extends CustomInjector implements InjectorInterface
{
    /**
     * Constructor
     *
     * @since   1.0
     */
    public function __construct()
    {
        $this->service_namespace        = 'Molajo\\Application\\Configuration\\ConfigurationData';
        $this->store_instance_indicator = true;
    }

    /**
     * On Before Startup for Configuration - runs after class instantiation
     *
     * @return  $this
     * @since   1.0
     */
    public function onAfterServiceInstantiate()
    {
        $this->service_instance->set('frontcontroller_instance', $this->frontcontroller_instance);

        /** Create temporary instance for purpose of reading XML */
        $xml           = 'Molajo\\Application\\Configuration\\Handler\\Xml';
        $configuration = new $xml();

        $this->getFieldProperties($configuration);
    }

    /**
     * Retrieve and load valid properties for fields, data models and data objects
     *
     * @return  object
     * @since   1.0
     * @throws  InjectorException
     */
    protected function getFieldProperties($configuration)
    {
        $xml = $configuration->getFile('Application', 'Fields');

        if ($xml === false) {
            throw new InjectorException
            ('IoC Injector Configuration: getFieldProperties '
                . 'File Model Type: Application Model_name: Fields not found.');
        }

        $this->loadFieldProperties($xml, 'dataobjecttypes', 'dataobjecttype', 'valid_dataobject_types');
        $this->loadFieldPropertiesWithAttributes(
            $xml,
            'dataobjectattributes',
            'dataobjectattribute',
            'valid_dataobject_attributes'
        );
        $this->loadFieldProperties($xml, 'modeltypes', 'modeltype', 'valid_model_types');
        $this->loadFieldPropertiesWithAttributes($xml, 'modelattributes', 'modelattribute', 'valid_model_attributes');
        $this->loadFieldProperties($xml, 'datatypes', 'datatype', 'valid_data_types');
        $this->loadFieldProperties($xml, 'queryelements', 'queryelement', 'valid_queryelements_attributes');

        $list = $this->service_instance->get('valid_queryelements_attributes');

        foreach ($list as $item) {
            $field = explode(',', $item);
            $this->loadFieldProperties($xml, $field[0], $field[1], $field[2]);
        }

        $datalistsArray = array();
        $datalistsArray = $this->loadDatalists($datalistsArray, VENDOR_MOLAJO_FOLDER . '/Application/Model/Datalist');
        $datalistsArray = array_unique($datalistsArray);

        $this->service_instance->set('valid_datalists', $datalistsArray);

        return;
    }

    /**
     * loadFieldProperties
     *
     * @param   string $xml
     * @param   string $plural
     * @param   string $singular
     * @param   string $parameter_name
     *
     * @return  bool
     * @since   1.0
     */
    protected function loadFieldProperties($xml, $plural, $singular, $parameter_name)
    {
        if (isset($xml->$plural->$singular)) {
        } else {
            return false;
        }

        $types = $xml->$plural->$singular;
        if (count($types) === 0) {
            return false;
        }

        $typeArray = array();
        foreach ($types as $type) {
            $typeArray[] = (string)$type;
        }

        $this->service_instance->set($parameter_name, $typeArray);

        return true;
    }

    /**
     * loadFieldPropertiesWithAttributes
     *
     * @param   string $xml
     * @param   string $plural
     * @param   string $singular
     * @param   string $parameter_name
     *
     * @return  bool
     * @since   1.0
     */
    protected function loadFieldPropertiesWithAttributes($xml, $plural, $singular, $parameter_name)
    {
        if (isset($xml->$plural->$singular)) {
        } else {
            return false;
        }

        $typeArray        = array();
        $typeDefaultArray = array();
        foreach ($xml->$plural->$singular as $type) {
            $typeArray[]                             = (string)$type['name'];
            $typeDefaultArray[(string)$type['name']] = (string)$type['default'];
        }

        $this->service_instance->set($parameter_name, $typeArray);
        $this->service_instance->set($parameter_name . '_defaults', $typeDefaultArray);

        return true;
    }

    /**
     * loadDatalists
     *
     * @param   string $datalistsArray
     * @param   string $folder
     *
     * @return  array
     * @since   1.0
     * @throws  InjectorException
     */
    protected function loadDatalists($datalistsArray, $folder)
    {
        try {

            $dirRead = dir($folder);

            $path = $dirRead->path;

            while (false !== ($entry = $dirRead->read())) {
                if (is_dir($path . '/' . $entry)) {
                } else {
                    $datalistsArray[] = substr($entry, 0, strlen($entry) - 4);
                }
            }

            $dirRead->close();

        } catch (InjectorException $e) {
            throw new InjectorException
            ('IoC Injector Configuration: loadDatalists cannot find Datalists file for folder: ' . $folder);
        }

        return $datalistsArray;
    }
}
