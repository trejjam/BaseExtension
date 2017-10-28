<?php
declare(strict_types=1);

namespace Trejjam\BaseExtension\DI;

use Nette;
use Trejjam;

abstract class BaseExtension extends Nette\DI\CompilerExtension
{
	protected $default = [];

	/**
	 * @var Nette\DI\ServiceDefinition[]
	 */
	protected $classesDefinition = [];

	/**
	 * @var Nette\DI\ServiceDefinition[]
	 */
	protected $factoriesDefinition = [];

	/**
	 * @return array
	 * @throws Nette\InvalidStateException
	 * @throws Nette\Utils\AssertionException
	 *
	 * @deprecated
	 */
	protected function createConfig() : array
	{
		$config = $this->validateConfig($this->default);

		Nette\Utils\Validators::assert($config, 'array');

		return $config;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 * @throws Nette\InvalidArgumentException
	 * @throws Nette\InvalidStateException
	 */
	protected function registerTypes() : array
	{
		$builder = $this->getContainerBuilder();

		$classes = [];
		foreach ($this->classesDefinition as $k => $v) {
			$classes[$k] = $builder->addDefinition($this->prefix($k))
								   ->setType($v);
		}

		return $classes;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 * @throws Nette\DI\MissingServiceException
	 *
	 * @deprecated
	 */
	protected function getClasses() : array
	{
		return $this->getTypes();
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 * @throws Nette\DI\MissingServiceException
	 */
	protected function getTypes() : array
	{
		$builder = $this->getContainerBuilder();

		$classes = [];
		foreach ($this->classesDefinition as $k => $v) {
			$classes[$k] = $builder->getDefinition($this->prefix($k));
		}

		return $classes;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 * @throws Nette\InvalidArgumentException
	 * @throws Nette\InvalidStateException
	 */
	protected function registerFactories() : array
	{
		$builder = $this->getContainerBuilder();

		$factories = [];
		foreach ($this->factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->addDefinition($this->prefix($k))
									 ->setImplement($v);
		}

		return $factories;
	}

	/**
	 * @return Nette\DI\ServiceDefinition[]
	 * @throws Nette\DI\MissingServiceException
	 */
	protected function getFactories() : array
	{
		$builder = $this->getContainerBuilder();

		$factories = [];
		foreach ($this->factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->getDefinition($this->prefix($k));
		}

		return $factories;
	}

	public function loadConfiguration(bool $validateConfig = TRUE) : void
	{
		if ($validateConfig) {
			$this->validateConfig($this->default);
		}

		$this->registerTypes();
		$this->registerFactories();
	}
}
