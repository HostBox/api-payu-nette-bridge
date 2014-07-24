<?php

namespace HostBoxTests\Api\PayU;

use HostBox\Bridge\PayU\Extension;
use Nette\Configurator;
use Nette\DI\Compiler;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class ExtensionTest extends TestCase {

    /** @return \Nette\DI\Container */
    protected function createContainer() {
        $configurator = new Configurator();
        $configurator->setTempDirectory(TEMP_DIR);
        $configurator->onCompile[] = function ($config, Compiler $compiler) {
            $compiler->addExtension('payu', new Extension());
        };
        $configurator->addConfig(__DIR__ . '/files/config.neon');

        return $configurator->createContainer();
    }

    /** @return void */
    public function testServices() {
        $dic = $this->createContainer();

        Assert::true($dic->getService('payu.config') instanceof \HostBox\Api\PayU\Config);
        Assert::true($dic->getService('payu.connection') instanceof \HostBox\Api\PayU\Connection);
        Assert::true($dic->getService('payu.payu') instanceof \HostBox\Api\PayU\PayU);
    }

}

\run(new ExtensionTest());
