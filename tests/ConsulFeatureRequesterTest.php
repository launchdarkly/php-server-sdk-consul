<?php

namespace LaunchDarkly\Impl\Integrations\Tests;

use LaunchDarkly\FeatureRequester;
use LaunchDarkly\Integrations\Consul;
use LaunchDarkly\SharedTest\DatabaseFeatureRequesterTestBase;
use SensioLabs\Consul\Exception\ClientException;
use SensioLabs\Consul\ServiceFactory;

class ConsulFeatureRequesterTest extends DatabaseFeatureRequesterTestBase
{
    private static $kvClient;

    public static function setUpBeforeClass(): void
    {
        $sf = new ServiceFactory();
        self::$kvClient = $sf->get('kv');
    }

    private static function realPrefix(?string $prefix): string
    {
        if ($prefix === null || $prefix === '') {
            return 'launchdarkly';
        }
        return $prefix;
    }

    protected function makeRequester($prefix): FeatureRequester
    {
        $options = array(
            'consul_prefix' => $prefix
        );
        $factory = Consul::featureRequester();
        return $factory('', '', $options);
    }

    protected function putSerializedItem($prefix, $namespace, $key, $version, $json): void
    {
        self::$kvClient->put(self::realPrefix($prefix) . '/' . $namespace . '/' . $key, $json);
    }

    protected function clearExistingData($prefix): void
    {
        try {
            $resp = self::$kvClient->get(self::realPrefix($prefix) . '/',
                array('keys' => true, 'recurse' => true));
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                return;
            }
            throw $e;
        }
        $results = $resp->json();
        foreach ($results as $key) {
            self::$kvClient->delete($key);
        }
    }
}
