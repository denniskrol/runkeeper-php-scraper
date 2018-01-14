<?php

namespace JPaylor\RunKeeper\Models;

/**
 * Activity Model
 *
 * @package JPaylor\RunKeeper\Models
 */
class CyclingActivity extends DistanceActivity {
    /**
     * @var string average speed
     */
    public $averageSpeed;

    /**
     * @return string
     */
    public function getAverageSpeed()
    {
        return $this->averageSpeed;
    }

    /**
     * @param string $averageSpeed
     * @return Activity
     */
    public function setAverageSpeed($averageSpeed)
    {
        $this->averageSpeed = $averageSpeed;
        return $this;
    }
}