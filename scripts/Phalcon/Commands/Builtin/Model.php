<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2013 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Commands\Builtin;

use Phalcon\Text,
	Phalcon\Builder,
	Phalcon\Script\Color,
	Phalcon\Commands\Command,
	Phalcon\Commands\CommandsInterface,
	Phalcon\Builder\Model as ModelBuilder;

/**
 * CreateModel
 *
 * Create a model from command line
 *
 * @category 	Phalcon
 * @package 	Commands
 * @subpackage  Builtin
 * @copyright   Copyright (c) 2011-2013 Phalcon Team (team@phalconphp.com)
 * @license 	New BSD License
 */
class Model extends Command implements CommandsInterface
{

	protected $_possibleParameters = array(
		'name=s'          => "Model name",
		'schema=s'        => "Name of the schema. [optional]",
		'namespace=s'     => "Model's namespace [optional]",
		'get-set'         => "Attributes will be protected and have setters/getters. [optional]",
        'extends=s'       => 'Model extends the class name supplied [optional]',
        'excludefields=l' => 'Excludes fields defined in a comma separated list [optional]',
		'doc'             => "Helps to improve code completion on IDEs [optional]",
		'directory=s'     => "Base path on which project will be created [optional]",
		'force'           => "Rewrite the model. [optional]",
		'trace'           => "Shows the trace of the framework in case of exception. [optional]",
	);

	public function run($parameters)
	{

		$name = $this->getOption(array('name', 1));

		$className = Text::camelize(isset($parameters[2]) ? $parameters[2] : $name);
		$fileName = Text::uncamelize($className);

		$schema = $this->getOption('schema');

		$modelBuilder = new ModelBuilder(
            array(
                'name'              => $name,
                'schema'            => $schema,
                'className'         => $className,
                'fileName'          => $fileName,
                'genSettersGetters' => $this->isReceivedOption('get-set'),
                'genDocMethods'     => $this->isReceivedOption('doc'),
                'namespace'         => $this->getOption('namespace'),
                'directory'         => $this->getOption('directory'),
                'extends'           => $this->getOption('extends'),
                'excludeFields'     => $this->getOption('excludefields'),
                'force'             => $this->isReceivedOption('force')
		    )
        );

		$modelBuilder->build();
	}

	/**
	 * Returns the commands provided by the command
	 *
	 * @return array
	 */
	public function getCommands()
	{
		return array('model', 'create-model');
	}

	/**
	 * Checks whether the command can be executed outside a Phalcon project
	 */
	public function canBeExternal()
	{
		return false;
	}

	/**
	 * Prints the help for current command.
	 *
	 * @return void
	 */
	public function getHelp()
	{
		print Color::head('Help:') . PHP_EOL;
		print Color::colorize('  Creates a model') . PHP_EOL . PHP_EOL;

		print Color::head('Usage:') . PHP_EOL;
		print Color::colorize('  model [table-name] [options]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

		print Color::head('Arguments:') . PHP_EOL;
		print Color::colorize('  ?', Color::FG_GREEN);
		print Color::colorize("\tShows this help text") . PHP_EOL . PHP_EOL;

		$this->printParameters($this->_possibleParameters);
	}

	/**
	 * Returns number of required parameters for this command
	 *
	 * @return int
	 */
	public function getRequiredParams()
	{
		return 1;
	}
}
