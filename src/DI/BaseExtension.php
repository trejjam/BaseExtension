<?php

namespace Trejjam\BaseExtension\DI;

use Nette;
use Trejjam;

abstract class BaseExtension extends Nette\DI\CompilerExtension
{
	protected $default = [];

	protected $classesDefinition = [];

	protected $factoriesDefinition = [];

	/**
	 * @return array
	 * @throws Nette\Utils\AssertionException
	 */
	protected function createConfig() {
		$config = $this->getConfig($this->default);

		Nette\Utils\Validators::assert($config, 'array');

		return $config;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 */
	protected function registerClasses() {
		$builder = $this->getContainerBuilder();

		/** @var Nette\DI\ServiceDefinition[] $classes */
		$classes = [];
		foreach ($this->classesDefinition as $k => $v) {
			$classes[$k] = $builder->addDefinition($this->prefix($k))
								   ->setClass($v);
		}

		return $classes;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 */
	protected function getClasses() {
		$builder = $this->getContainerBuilder();

		/** @var Nette\DI\ServiceDefinition[] $classes */
		$classes = [];
		foreach ($this->classesDefinition as $k => $v) {
			$classes[$k] = $builder->getDefinition($this->prefix($k));
		}

		return $classes;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 */
	protected function registerFactories() {
		$builder = $this->getContainerBuilder();

		/** @var Nette\DI\ServiceDefinition[] $factories */
		$factories = [];
		foreach ($this->factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->addDefinition($this->prefix($k))
									 ->setImplement($v);
		}

		return $factories;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 */
	protected function getFactories() {
		$builder = $this->getContainerBuilder();

		/** @var Nette\DI\ServiceDefinition[] $factories */
		$factories = [];
		foreach ($this->factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->getDefinition($this->prefix($k));
		}

		return $factories;
	}

	public function loadConfiguration() {
		parent::loadConfiguration();

		$this->registerClasses();
		$this->registerFactories();
	}
}
