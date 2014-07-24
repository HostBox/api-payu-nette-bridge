<?php

namespace HostBox\Bridge\PayU;

use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;


class Extension extends CompilerExtension {

    /** @inheritdoc */
    public function loadConfiguration() {
        $container = $this->getContainerBuilder();
        $config = $this->getConfig();

        if ($diff = array_diff(array('posId', 'posAuthKey', 'key1', 'key2'), array_keys($config))) {
            throw new InvalidArgumentException('Missing configuration: ' . implode(', ', $diff));
        }

        $container->addDefinition($this->prefix('config'))
            ->setClass('HostBox\Api\PayU\Config')
            ->setArguments(array($config['posId'], $config['posAuthKey'], $config['key1'], $config['key2']));

        $container->addDefinition($this->prefix('connection'))
            ->setClass('HostBox\Api\PayU\Connection');

        $container->addDefinition($this->prefix('payu'))
            ->setClass('HostBox\Api\PayU\PayU');
    }

}
