<?php
namespace LaunchDarkly\Impl\Integrations;

use LaunchDarkly\Impl\Integrations\FeatureRequesterBase;
use Consul\Exception\ClientException;
use Consul\Services\KV;

/**
 * @internal
 */
class ConsulFeatureRequester extends FeatureRequesterBase
{
    /** @var string */
    protected $_prefix;
    /** @var \SensioLabs\Consul\Services\KV */
    protected $_kvClient;

    public function __construct($baseUri, $sdkKey, $options)
    {
        parent::__construct($baseUri, $sdkKey, $options);

        $consulOpts = $options['consul_options'] ?? [];
        if (isset($options['consul_uri'])) {
            $consulOpts['base_uri'] = $options['consul_uri'];
        }
        $this->_kvClient = new KV();

        $prefix = $options['consul_prefix'] ?? null;
        if ($prefix === null || $prefix === '') {
            $prefix = 'launchdarkly';
        }
        $this->_prefix = $prefix . '/';
    }

    protected function readItemString(string $namespace, string $key): ?string
    {
        try {
            $resp = $this->_kvClient->get($this->makeKey($namespace, $key));
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                return null;
            }
            throw $e;
        }
        $results = $resp->json();
        if (count($results) != 1) {
            return null;
        }
        return base64_decode($results[0]['Value']);
    }

    protected function readItemStringList(string $namespace): ?array
    {
        try {
            $resp = $this->_kvClient->get($this->makeKey($namespace, ''), array('recurse' => true));
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                return array();
            }
            throw $e;
        }
        $results = $resp->json();
        $ret = array();
        foreach ($results as $result) {
            $ret[] = base64_decode($result['Value']);
        }
        return $ret;
    }

    private function makeKey($namespace, $key)
    {
        return $this->_prefix . $namespace . '/' . $key;
    }
}
