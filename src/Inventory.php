<?php

namespace LibrenmsApiClient;

/**
 * LibreNMS API Inventor.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2020, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       0.0.1
 */
class Inventory
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Get device inventory listing.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @see https://docs.librenms.org/API/Inventory/#get_inventory_for_device
     */
    public function listing(int|string $hostname): ?array
    {
        $url = $this->api->getApiUrl("/inventory/$hostname/all");
        $result = $this->api->get($url);

        if (!isset($result) || !isset($result['inventory'])) {
            return null;
        }

        return $result['inventory'];
    }

    /**
     * Get device inventory.
     *
     * @param int|string $hostname Hostname can be either the device hostname or id
     *
     * @see https://docs.librenms.org/API/Inventory/#get_inventory
     */
    public function get(int|string $hostname)
    {
        $url = $this->api->getApiUrl("/inventory/$hostname");
        $result = $this->api->get($url);

        if (!isset($result) || !isset($result['inventory'])) {
            return null;
        }

        return $result['inventory'];
    }

    /**
     * Get list of hardware types.
     */
    public function hardware(): ?array
    {
        return $this->getField('hardware');
    }

    /**
     * Get list of software versions.
     */
    public function version(): ?array
    {
        return $this->getField('version');
    }

    /**
     * Get list of features.
     */
    public function feature()
    {
        return $this->getField('features');
    }

    /**
     * Get a list of device types.
     */
    public function type(): array
    {
        $devices = $this->api->device->listing();
        $types = [];
        if (!isset($devices)) {
            return null;
        }

        if (!isset($devices['org'])) {
            return null;
        }

        foreach ($devices['org'] as $device) {
            if (in_array($device->type, $types)) {
                continue;
            }
            if (!isset($device->type) || empty($device->type)) {
                continue;
            }

            $types[] = $device->type;
        }

        return $types;
    }

    private function getField(string $field)
    {
        $devices = $this->api->device->listing();

        $result = [];
        foreach ($devices['org'] as $device) {
            $item = trim($device->$field);

            if (empty($item)) {
                continue;
            }
            if (!in_array($item, $result)) {
                $result[] = $item;
            }
        }

        if (0 === count($result)) {
            return null;
        }
        sort($result);

        return $result;
    }
}
