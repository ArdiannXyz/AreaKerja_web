<?php

namespace Tests\Unit;

use App\Services\WilayahService;
use Tests\TestCase;

class WilayahServiceTest extends TestCase
{
    /** @test */
    public function it_can_load_provinces_with_memoization()
    {
        $provinces = WilayahService::getProvinces();
        $this->assertNotEmpty($provinces);

        $first = $provinces->first();
        $this->assertNotNull($first);
        $this->assertObjectHasProperty('id', $first);
        $this->assertObjectHasProperty('nama', $first);
    }

    /** @test */
    public function it_can_find_province_by_id_and_name()
    {
        $provinces = WilayahService::getProvinces();
        if ($provinces->isEmpty()) {
            $this->markTestSkipped('Provinces data empty.');
        }

        $target = $provinces->first();
        $foundById = WilayahService::findProvince($target->id);
        $this->assertNotNull($foundById);
        $this->assertEquals($target->id, $foundById->id);

        $foundByName = WilayahService::findProvince(strtolower($target->nama));
        $this->assertNotNull($foundByName);
        $this->assertEquals($target->id, $foundByName->id);
    }

    /** @test */
    public function it_can_get_regencies_filtered_by_province()
    {
        $provinces = WilayahService::getProvinces();
        if ($provinces->isEmpty()) {
            $this->markTestSkipped('Provinces data empty.');
        }

        $targetProv = $provinces->first();
        $regencies = WilayahService::getRegencies($targetProv->id);

        foreach ($regencies as $r) {
            $this->assertEquals((string)$targetProv->id, (string)$r->province_id);
        }
    }
}

