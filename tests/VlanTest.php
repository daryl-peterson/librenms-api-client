<?php

namespace LibrenmsApiClient\Tests;

use LibrenmsApiClient\ApiClient;
use LibrenmsApiClient\ApiException;
use LibrenmsApiClient\Vlan;
use PHPUnit\Framework\TestCase;

/**
 * LibreNMS API Vlan unit test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2020, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @covers \LibrenmsApiClient\ApiClient
 * @covers \LibrenmsApiClient\Curl
 * @covers \LibrenmsApiClient\Vlan
 * @covers \LibrenmsApiClient\Common
 */
class VlanTest extends TestCase
{
    private Vlan $vlan;
    private $switch_id;

    public function test()
    {
        $obj = $this->vlan;
        $result = $obj->getListing();
        $this->assertIsArray($result);

        $vlan = array_pop($result);
        $result = $obj->get($this->switch_id);
        $this->assertIsArray($result);

        // $this->expectException(ApiException::class);
        $result = $obj->get(999999);
        $this->assertNull($result);
    }

    public function setUp(): void
    {
        if (!isset($this->vlan)) {
            global $settings;

            $api = new ApiClient($settings['url'], $settings['token']);
            $this->switch_id = $settings['switch_id'];
            $this->vlan = $api->get(Vlan::class);
        }
    }
}