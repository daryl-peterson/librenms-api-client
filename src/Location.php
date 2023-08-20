<?php

namespace LibrenmsApiClient;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2020, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Location
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Add location.
     *
     * @see https://docs.librenms.org/API/Locations/#add_location
     */
    public function add(string $location, string $lat, string $lng, bool $fixed = false): ?array
    {
        $data = [
            'location' => $location,
            'lat' => $lat,
            'lng' => $lng,
            'fixed_coordinates' => $fixed,
        ];
        $url = $this->api->getApiUrl('/locations');
        $result = $this->api->post($url, $data);

        if (!isset($result) || !isset($result['message'])) {
            return null;
        }
        unset($result['headers']);

        return $result;
    }

    /**
     * Get location.
     *
     * @see https://docs.librenms.org/API/Locations/#get_location
     */
    public function get(string $location)
    {
        $location = rawurlencode($location);
        $url = $this->api->getApiUrl("/location/$location");

        $result = $this->api->get($url);
        if (!isset($result) || !isset($result['get_location'])) {
            return null;
        }

        return (array) $result['get_location'];
    }

    /**
     * Get locations list.
     *
     * @see https://docs.librenms.org/API/Locations/#list_locations
     */
    public function listing(): ?array
    {
        $url = $this->api->getApiUrl('/resources/locations');
        $result = $this->api->get($url);
        if (!isset($result) || !isset($result['locations'])) {
            return null;
        }

        return $result['locations'];
    }

    /**
     * Delete location.
     *
     * @see https://docs.librenms.org/API/Locations/#delete_location
     */
    public function delete(string $location): ?array
    {
        $location = rawurlencode($location);
        $url = $this->api->getApiUrl("/locations/$location");

        $result = $this->api->delete($url);

        if (!isset($result) || !isset($result['message'])) {
            return null;
        }
        unset($result['headers']);

        return $result;
    }

    /**
     * Update location.
     *
     * @see https://docs.librenms.org/API/Locations/#edit_location
     */
    public function edit(string $location, array $data): ?array
    {
        $location = rawurlencode($location);
        $url = $this->api->getApiUrl("/locations/$location");
        $result = $this->api->patch($url, $data);

        return $result;
    }
}