<?php
declare(strict_types=1);

namespace Trejjam\BaseExtension\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\MissingServiceException;
use Nette\DI\ServiceDefinition;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;

abstract class BaseExtension extends CompilerExtension
{
	protected $default = [];

	/**
	 * @var ServiceDefinition[]
	 */
	protected $classesDefinition = [];

	/**
	 * @var ServiceDefinition[]
	 */
	protected $factoriesDefinition = [];

	/**
	 * @return array
	 * @throws InvalidStateException
	 * @throws AssertionException
	 *
	 * @deprecated
	 */
	protected function createConfig() : array
	{
		$config = $this->validateConfig($this->default);

		Validators::assert($config, 'array');

		return $config;
	}

	/**
	 * @return ServiceDefinition[]
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 */
	protected function registerTypes() : array
	{
		$builder = $this->getContainerBuilder();

		$classes = [];
		foreach ($this->classesDefinition as $k => $v) {
			$classes[$k] = $builder->addDefinition($this->prefix($k));
			$classes[$k]->setType($v);
		}

		return $classes;
	}

	/**
	 * @return ServiceDefinition[]
	 * @throws MissingServiceException
	 *
	 * @deprecated
	 */
	protected function getClasses() : array
	{
		return $this->getTypes();
	}

	/**
	 * @return ServiceDefinition[]
	 * @throws MissingServiceException
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
	 * @return ServiceDefinition[]
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 */
	protected function registerFactories() : array
	{
		$builder = $this->getContainerBuilder();

		$factories = [];
		foreach ($this->factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->addDefinition($this->prefix($k));
			$factories[$k]->setImplement($v);
		}

		return $factories;
	}

	/**
	 * @return ServiceDefinition[]
	 * @throws MissingServiceException
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
