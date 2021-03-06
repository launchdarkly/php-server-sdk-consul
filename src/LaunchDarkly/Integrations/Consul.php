<?php
namespace LaunchDarkly\Integrations;

use \LaunchDarkly\Impl\Integrations\ConsulFeatureRequester;

/**
 * Integration with a Consul data store.
 */
class Consul
{
    /**
     * Configures an adapter for reading feature flag data from Consul.
     *
     * After calling this method, store its return value in the `feature_requester` property of
     * your client configuration:
     *
     *     $fr = Consul::featureRequester([ "consul_prefix" => "env1" ]);
     *     $config = [ "feature_requester" => $fr ];
     *     $client = new LDClient("sdk_key", $config);
     *
     * For more about using LaunchDarkly with databases, see the
     * [SDK reference guide](https://docs.launchdarkly.com/sdks/features/storing-data).
     *
     * @param array $options  Configuration settings (can also be passed in the main client configuration):
     *   - `consul_uri`: URI of the Consul host; defaults to `http://localhost:8500`
     *   - `consul_options`: array of settings that the Consul client will pass to Guzzle
     *   - `consul_prefix`: a string to be prepended to all database keys; corresponds to the prefix
     * setting in ld-relay
     *   - `apc_expiration`: expiration time in seconds for local caching, if `APCu` is installed
     * @return mixed an object to be stored in the `feature_requester` configuration property
     */
    public static function featureRequester($options = array())
    {
        return function ($baseUri, $sdkKey, $baseOptions) use ($options) {
            return new ConsulFeatureRequester($baseUri, $sdkKey, array_merge($baseOptions, $options));
        };
    }
}
