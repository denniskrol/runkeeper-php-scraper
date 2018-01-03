<?php

namespace JPaylor\RunKeeper;

use JPaylor\RunKeeper\Models\Activity;
use Symfony\Component\DomCrawler;
use Concat\Http\Middleware\Logger;

class Scrape {
    private $log;

    private $http;
    private $cookieJar;

    private $username;
    private $password;

    private $loggedIn = false;

    /**
     * Scrape constructor.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->cookieJar = new \GuzzleHttp\Cookie\CookieJar();

        $handlerStack = \GuzzleHttp\HandlerStack::create();

        if ( ! empty($params['log']) && is_a($params['log'], 'Psr\Log\LoggerInterface')) {
            $this->log = $params['log'];

            // Setup guzzle logging
            $middleware = new Logger($this->log);
            $middleware->setRequestLoggingEnabled(false);
            $handlerStack->push(
                $middleware
            );
        }

        $this->username = $params['username'] ?? null;
        $this->password = $params['password'] ?? null;

        $this->http = new \GuzzleHttp\Client(['handler' => $handlerStack]);
    }

    /**
     * Log
     *
     * @param string $level
     * @param string $message
     * @param array $data
     */
    private function log($level, $message, $data = [])
    {
        if ( ! empty($this->log)) {
            $this->log->$level($message, $data);
        }
    }

    /**
     * Get Activities
     *
     * @param string $profileUsername
     * @return array
     */
    public function getActivities($profileUsername)
    {
        if ( ! $this->loggedIn) {
            $this->login();
        }

        $this->log('info', 'Getting activities', ['profile' => $profileUsername]);

        // Get activities page
        $activitiesPageUrl = sprintf('https://runkeeper.com/user/%s/activitylist', $profileUsername);
        $activitiesPage = $this->http->request('GET', $activitiesPageUrl, ['cookies' => $this->cookieJar]);
        $activitiesPageDom = new DomCrawler\Crawler((string) $activitiesPage->getBody());

        // Get activities list
        $activitiesList = $activitiesPageDom->filter('#activityHistoryMenu ul li a');
        $activities = [];

        if (empty($activitiesList)) {
            return $activities;
        }

        foreach ($activitiesList as $activityListItem) {
            $activity = new Activity();

            // Parse activity link
            $activityHref = $activityListItem->getAttribute('href');
            preg_match('/\/user\/([^\/]+)\/activity\/(\d+)/', $activityHref, $matches);
            $activity->setUsername($matches[1] ?? null);
            $activity->setId($matches[2] ?? null);

            $activity->setType(strtolower(trim($activityListItem->lastChild->textContent)));

            $activities[] = $activity;
        }

        return $activities;
    }

    /**
     * Get Activity Details
     *
     * @param Activity $activity
     * @return Activity
     * @throws \Exception
     */
    public function getActivityDetails(Activity $activity)
    {
        if ( ! $this->loggedIn) {
            $this->login();
        }

        if (empty($activity->getUsername())) {
            throw new \Exception('Activity username is required to get activity details');
        }

        if (empty($activity->getId())) {
            throw new \Exception('Activity id is required to get activity details');
        }

        $this->log('info', 'Getting activity detail', ['profile' => $activity->getUsername(), 'activityId' => $activity->getId()]);

        // Get activity detail page
        $activityDetailPageUrl = sprintf('https://runkeeper.com/user/%s/activity/%d', $activity->getUsername(), $activity->getId());
        $activityDetailPage = $this->http->request('GET', $activityDetailPageUrl, ['cookies' => $this->cookieJar]);
        $activityDetailPageDom = new DomCrawler\Crawler((string) $activityDetailPage->getBody());

        // Parse activity dom
        $activity->setDistance((float) $activityDetailPageDom->filter('#totalDistance .value')->first()->text());
        $activity->setDuration((string) $activityDetailPageDom->filter('#totalDuration .value')->first()->text());
        $activity->setAveragePace((string) $activityDetailPageDom->filter('#averagePace .value')->first()->text());
        $activity->setCaloriesBurned((int) $activityDetailPageDom->filter('#totalCalories .value')->first()->text());

        // "Tue Dec 26 14:10:51 GMT 2017"
        $activityDateTime = (string) $activityDetailPageDom->filter('.userHeader .activitySubTitle')->first()->text();
        preg_match('/([A-z]*) ([A-z]*) (\d+) (\d{2}\:\d{2}\:\d{2}) ([A-z]+) (\d{4})/', $activityDateTime, $matches);
        $activity->setDateTime(date('Y-m-d ', strtotime(sprintf('%s %d, %d', $matches[2], $matches[3], $matches[6]))) . $matches[4]);

        return $activity;
    }

    /**
     * Login to Runkeeper
     *
     * @return bool
     */
    public function login()
    {
        $this->log('info', 'Logging into Runkeeper', ['username' => $this->username]);

        // Visit login page
        $loginPage = $this->http->request('GET', 'https://runkeeper.com/login', ['cookies' => $this->cookieJar]);

        // Parse dom
        $loginPageDom = new DomCrawler\Crawler((string) $loginPage->getBody());

        // Get form inputs
        $loginPageFormInputs = $loginPageDom->filter('#loginForm input');
        $inputVals = [];
        foreach ($loginPageFormInputs as $input) {
            $inputVals[$input->getAttribute('name')] = $input->getAttribute('value');
        }
        // Set username and password
        $inputVals['email'] = $this->username;
        $inputVals['password'] = $this->password;

        // Submit login
        $submitLogin = $this->http->request('POST', 'https://runkeeper.com/login', [
            'cookies' => $this->cookieJar,
            'form_params' => $inputVals
        ]);

        $this->loggedIn = true;

        return $this->loggedIn;
    }
}