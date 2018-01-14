<?php

namespace JPaylor\RunKeeper\Models;

/**
 * Activity Model
 *
 * @package JPaylor\RunKeeper\Models
 */
class DistanceActivity extends Activity {
    /**
     * @var float distance km
     */
    public $distance;

    /**
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return Activity
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

}
