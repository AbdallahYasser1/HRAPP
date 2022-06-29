<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Traits\CalulateTotalMission;

class ExampleTest extends TestCase
{
    use CalulateTotalMission;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }


    public function test_addAmountToSlip()
    {
        $this->addAmountToSlip([
            'user_id' => 1,
            'total' => 100,
            'mission_description' => 'test_mission',
        ]);
        $this->assertTrue(true);
    }

}
