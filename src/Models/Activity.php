<?php

namespace JPaylor\RunKeeper\Models;

/**
 * Activity Model
 *
 * @package JPaylor\RunKeeper\Models
 */
class Activity {
    /**
     * @var int id
     */
    public $id;

    /**
     * @var string username
     */
    public $username;

    /**
     * @var float distance km
     */
    public $distance;

    /**
     * @var int duration seconds
     */
    public $duration;

    /**
     * @var string average pace
     */
    public $averagePace;

    /**
     * @var int calories burned
     */
    public $caloriesBurned;

    /**
     * @var string date and time of activity in the format Y-m-d H:i:s
     */
    public $datetime;

    /**
     * @var string type of activity i.e. walking
     */
    public $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Activity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Activity
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

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

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Activity
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

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

    /**
     * @return int
     */
    public function getCaloriesBurned()
    {
        return $this->caloriesBurned;
    }

    /**
     * @param int $caloriesBurned
     * @return Activity
     */
    public function setCaloriesBurned($caloriesBurned)
    {
        $this->caloriesBurned = $caloriesBurned;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param string $datetime
     * @return Activity
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Activity
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}