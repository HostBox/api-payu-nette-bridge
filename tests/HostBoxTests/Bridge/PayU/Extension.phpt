<?php

namespace HostBoxTests\Bridge\PayU;

use HostBox\Api\PayU;
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
            $compiler->addExtension('single', new Extension());
            $compiler->addExtension('multi', new Extension());
        };
        $configurator->addConfig(__DIR__ . '/files/config.neon');

        return $configurator->createContainer();
    }

    /** @return void */
    public function testSingleService() {
        $dic = $this->createContainer();

        Assert::true(($config = $dic->getService('single.default.config')) instanceof PayU\Config);
        Assert::true($dic->getService('single.default.connection') instanceof PayU\Connection);
        Assert::true($dic->getService('single.default') instanceof PayU\PayU);
        /** @var PayU\IConfig $config */
        Assert::same('txt', $config->getFormat());
    }

    /** @return void */
    public function testMultiService() {
        $dic = $this->createContainer();

        Assert::true(($config = $dic->getService('multi.second.config')) instanceof PayU\Config);
        Assert::true($dic->getService('multi.second.connection') instanceof PayU\Connection);
        Assert::true($dic->getService('multi.second') instanceof PayU\PayU);
        /** @var PayU\IConfig $config */
        Assert::same('ISO', $config->getEncoding());
    }

}

\run(new ExtensionTest());
