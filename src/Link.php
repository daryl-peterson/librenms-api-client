<?php

namespace LibrenmsApiClient;

use stdClass;

/**
 * Device Links CDP, LLDP ect.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2023, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       0.0.2
 */
class Link extends Common
{
    /**
     * Listing of discovered devices CDP, LLDP.
     *
     * @return array|null Array of stdClass Objects
     *
     * @see https://docs.librenms.org/API/Switching/#list_links
     */
    public function getListing(): ?array
    {
        $url = $this->curl->getApiUrl('/resources/links');
        $this->result = $this->curl->get($url);

        if (!isset($this->result['links']) || !is_array($this->result['links']) || (0 === count($this->result['links']))) {
            // @codeCoverageIgnoreStart
            return null;
            // @codeCoverageIgnoreEnd
        }

        return $this->result['links'];
    }

    /**
     * Retrieves Link by ID.
     *
     * @return array|false Array of stdClass Objects
     *
     * @see https://docs.librenms.org/API/Switching/#get_link
     */
    public function getById(int $id): false|\stdClass
    {
        $url = $this->curl->getApiUrl("/resources/links/$id");
        $this->result = $this->curl->get($url);

        if (!isset($this->result['link'][0]) || !is_object($this->result['link'][0])) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        return $this->result['link'][0];
    }
}
