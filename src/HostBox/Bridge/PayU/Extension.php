<?php

namespace HostBox\Bridge\PayU;

use HostBox\Api\PayU\IConfig;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;


class Extension extends CompilerExtension {

    /** @inheritdoc */
    public function loadConfiguration() {
        $optionKeys = array('posId', 'posAuthKey', 'key1', 'key2', 'encoding', 'format');
        $defaultOptions = array(
            'encoding' => IConfig::ENCODING_UTF_8,
            'format' => IConfig::FORMAT_XML
        );

        $container = $this->getContainerBuilder();
        $config = $this->getConfig();

        $searchKeys = array_intersect($optionKeys, array_keys($config));
        if (!empty($searchKeys)) {
            $config = array('default' => $config);
        }

        foreach ($config as $name => $info) {
            if (!is_array($info)) {
                continue;
            }

            if (!isset($info['encoding'])) {
                $info['encoding'] = $defaultOptions['encoding'];
            }

            if (!isset($info['format'])) {
                $info['format'] = $defaultOptions['format'];
            }

            if ($diff = array_diff($optionKeys, array_keys($info))) {
                throw new InvalidArgumentException(sprintf('[PayU][%s] Missing configuration: %s', $name, implode(', ', $diff)));
            }

            $prefixConfig = $this->prefix($name . '.config');
            $container->addDefinition($prefixConfig)
                ->setClass('HostBox\Api\PayU\Config')
                ->setArguments(array(
                    $info['posId'], $info['posAuthKey'], $info['key1'], $info['key2'],
                    $info['encoding'], $info['format']))
                ->setAutowired(FALSE)
                ->setInject(FALSE);

            $prefixConnection = $this->prefix($name . '.connection');
            $container->addDefinition($prefixConnection)
                ->setClass('HostBox\Api\PayU\Connection')
                ->setArguments(array('@' . $prefixConfig))
                ->setAutowired(FALSE)
                ->setInject(FALSE);

            $payU = $container->addDefinition($this->prefix($name))
                ->setClass('HostBox\Api\PayU\PayU')
                ->setArguments(array('@' . $prefixConnection));

            if (count($config) != 1) {
                $payU->setAutowired(FALSE)->setInject(FALSE);
            }

        }

    }

}
