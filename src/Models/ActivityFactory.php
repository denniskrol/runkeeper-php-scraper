<?php

namespace JPaylor\RunKeeper\Models;

/**
 * Activity Factory
 *
 * @package JPaylor\RunKeeper\Models
 */
class ActivityFactory {

    public static function getActivity($activityType)
    {
        switch ($activityType) {
            case ACTIVITY::ACTIVITY_TYPE_WALKING:
                $activity = new WalkingActivity();
                break;
            case ACTIVITY::ACTIVITY_TYPE_RUNNING:
            $activity = new RunningActivity();
                break;
            case ACTIVITY::ACTIVITY_TYPE_CYCLING:
                $activity = new CyclingActivity();
                break;
            case ACTIVITY::ACTIVITY_TYPE_ELLIPTICAL:
                $activity = new EllipticalActivity();
                break;
            default:
                $activity = new Activity();
                break;
        }

        return $activity;
    }

}
