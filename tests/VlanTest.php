<?php

namespace LibrenmsApiClient\Tests;

use LibrenmsApiClient\ApiException;
use LibrenmsApiClient\Curl;
use LibrenmsApiClient\Vlan;
use PHPUnit\Framework\TestCase;

/**
 * LibreNMS API Vlan unit test.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2020, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @covers \LibrenmsApiClient\Curl
 * @covers \LibrenmsApiClient\Vlan
 */
class VlanTest extends TestCase
{
    private Vlan $vlan;

    public function test()
    {
        $obj = $this->vlan;
        $result = $obj->getListing();
        $this->assertIsArray($result);

        $vlan = array_pop($result);
        $result = $obj->get($vlan->device_id);
        $this->assertIsArray($result);

        // $this->expectException(ApiException::class);
        $result = $obj->get(999999);
        $this->assertNull($result);
    }

    public function setUp(): void
    {
        if (!isset($this->vlan)) {
            global $url,$token;

            $curl = new Curl($url, $token);

            $this->vlan = new Vlan($curl);
        }
    }
}
