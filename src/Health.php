<?php

namespace LibrenmsApiClient;

/**
 * LibreNMS API Health.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2023, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       0.0.2
 */
class Health extends Common
{
    /**
     * Get a list of overall health graphs available.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @return array|null Array of stdClass Objects
     */
    public function getTypes(int|string $hostname): ?array
    {
        return $this->getHealth($hostname);
    }

    /**
     * Get a list of health graphs based on provided class.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     * @param string     $type     device_current, device_dbm, device_state,
     *                             device_temperature, device_voltage, device_processor,
     *                             device_storage, device_mempool
     *
     * @return array|null Array of stdClass Objects
     */
    public function getSensors(int|string $hostname, string $type): ?array
    {
        return $this->getHealth($hostname, $type);
    }

    /**
     * Get the health sensors information based on ID.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @return array|null Array of stdClass Objects
     */
    public function getValue(int|string $hostname, string $type, int $sensor_id): ?array
    {
        return $this->getHealth($hostname, $type, $sensor_id);
    }

    /**
     * Get a particular health class graph for a device.
     *
     * - If you provide a sensor_id as well then a single sensor graph will be provided.
     * - If no sensor_id value is provided then you will be sent a stacked sensor graph.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @return array|null Array ['type'=>'image/png','src'=>'image source']
     *
     * @see https://docs.librenms.org/API/Devices/#get_health_graph
     */
    public function getGraph(int|string $hostname, string $type, int $sensor_id = null): ?array
    {
        $device = $this->getDevice($hostname);
        if (!isset($device)) {
            return null;
        }
        $url = $this->curl->getApiUrl("/devices/$device->device_id/graphs/health");

        if (isset($type)) {
            $url .= "/$type";
        }
        if (isset($sensor_id)) {
            $url .= "/$sensor_id";
        }
        $response = $this->curl->get($url);

        if (!isset($response['image'])) {
            return null;
        }

        if (0 === count($response)) {
            return null;
        }

        return $response['image'];
    }

    /**
     * Check if device has health stats.
     */
    public function hasHealth(int|string $hostname): bool
    {
        $result = $this->getHealth($hostname);
        if (!isset($result)) {
            return false;
        }
        if (!count($result) > 0) {
            return false;
        }

        return true;
    }

    /**
     * This function allows to do three things:.
     *
     * - Get a list of overall health graphs available.
     * - Get a list of health graphs based on provided class.
     * - Get the health sensors information based on ID.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @see https://docs.librenms.org/API/Devices/#list_available_health_graphs
     */
    private function getHealth(int|string $hostname, string $type = null, string $sensor_id = null): ?array
    {
        $device = $this->getDevice($hostname);
        if (!isset($device)) {
            return null;
        }

        $url = $this->curl->getApiUrl("/devices/$device->device_id/health");

        if (isset($type)) {
            $url .= "/$type";
        }
        if (isset($sensor_id)) {
            $url .= "/$sensor_id";
        }
        $response = $this->curl->get($url);

        if (!isset($response['graphs'])) {
            return null;
        }

        if (0 === count($response['graphs'])) {
            return null;
        }

        return $response['graphs'];
    }
}
