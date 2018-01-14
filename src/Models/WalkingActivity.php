<?php

namespace JPaylor\RunKeeper\Models;

/**
 * Activity Model
 *
 * @package JPaylor\RunKeeper\Models
 */
class WalkingActivity extends DistanceActivity {
    /**
     * @var string average pace
     */
    public $averagePace;
    
    /**
     * @return string
     */
    public function getAveragePace()
    {
        return $this->averagePace;
    }

    /**
     * @param string $averagePace
     * @return Activity
     */
    public function setAveragePace($averagePace)
    {
        $this->averagePace = $averagePace;
        return $this;
    }
}