<?php

namespace LZaplata\PayU\DI;


use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;

class Extension extends CompilerExtension
{
    public $defaults = array(
        "sandbox" => true
    );

    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix("config"))
            ->setClass("LZaplata\PayU\Service", [$config["posId"], $config["clientId"], $config["sandbox"], $config["clientSecret"], $config["key2"]]);
    }
}