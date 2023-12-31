<?php

namespace LibrenmsApiClient\Tests;

use LibrenmsApiClient\ApiException;
use LibrenmsApiClient\Link;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2020, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @covers \LibrenmsApiClient\ApiClient
 * @covers \LibrenmsApiClient\Cache
 * @covers \LibrenmsApiClient\Common
 * @covers \LibrenmsApiClient\Curl
 * @covers \LibrenmsApiClient\FileLogger
 * @covers \LibrenmsApiClient\Device
 * @covers \LibrenmsApiClient\DeviceCache
 * @covers \LibrenmsApiClient\Link
 * @covers \LibrenmsApiClient\PortCache
 */
class LinkTest extends BaseTest
{
    private Link $link;

    public function testGetById()
    {
        $link = $this->link;
        $result = $link->getListing();
        $object = array_pop($result);

        $result = $link->getById((int) $object->id);
        $this->assertIsObject($result);

        $this->expectException(ApiException::class);
        $result = $link->getById(0);
    }

    public function testGetByHost()
    {
        $link = $this->link;
        $result = $link->getListing();
        $object = array_pop($result);

        $result = $link->getDeviceLinks($object->local_device_id);
        $this->assertIsArray($result);

        $result = $link->getDeviceLinks(0);
        $this->assertFalse($result);
    }

    public function testGetListing()
    {
        $link = $this->link;
        $result = $link->getListing();
        $this->assertIsArray($result);
    }

    public function setUp(): void
    {
        if (!isset($this->link)) {
            $this->link = $this->api->get(Link::class);
        }
    }
}
