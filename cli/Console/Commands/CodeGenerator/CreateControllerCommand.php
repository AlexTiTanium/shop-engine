<?php

namespace Console\Commands\CodeGenerator;

use Symfony\Component\Console\Command\Command;
use Console\Commands\CodeGenerator\Builders\RouterBuilder;
use lib\Core\DirCommander\Adapters\LocalDirCommanderAdapter;
use lib\Core\DirCommander;
use TwigGenerator\Builder\Generator;
use Console\Commands\CodeGenerator\Builders\ControllerBuilder;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 26.10.12
 * Time: 23:22
 * To change this template use File | Settings | File Templates.
 */
class CreateControllerCommand extends Command {

  private $controllerName;
  private $application;
  private $generator;

  /**
   * @var OutputInterface
   */
  private $output;

  /**
   * @see Console\Command\Command
   */
  protected function configure() {

      $this
      ->setName('system:generate:controller')
      ->setDescription('Generate controller for application')
      ->setDefinition(array(
          new InputArgument(
              'application', InputArgument::REQUIRED,
              'Application where will be generated controller'
          ),
          new InputArgument(
              'newControllerName', InputArgument::REQUIRED,
              'Controller name'
          )
      ));

      $this->setHelp('Generate application controller');
  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $dc = new DirCommander(new LocalDirCommanderAdapter(), PATH_APPLICATIONS);

    $this->output = $output;
    $this->application = $input->getArgument('application');
    $this->controllerName = $input->getArgument('newControllerName');

    $output->writeln('Generating controller...');

    $controllerClass = $this->createControllerClass();
    $routerFile = $this->createRouterFile();

    $dc
       ->cd($this->application)
        ->cd('controllers')
          ->makeDir($this->controllerName)
          ->cd($this->controllerName)
            ->makeDir('config')
            ->makeDir('events')
            ->makeDir('lib')
              ->cd('lib')->makeFile(ucfirst($this->controllerName).'Controller.php', $controllerClass)
            ->up()
            ->makeFile('router.php', $routerFile)
    ;

    $output->writeln('Controller "'.$this->controllerName.'" was generated for "'.$this->application.'" application');
  }


  private function getGenerator(){

    if($this->generator){ return $this->generator; }

    $generator = new Generator();
    $generator->setTemplateDirs(array(
        __DIR__.DS.'Templates',
    ));

    $generator->setMustOverwriteIfExists(true);

    return $this->generator = $generator;

  }

  private function createControllerClass(){

    $this->output->writeln('Begin create controller class...');

    $className = ucfirst($this->controllerName).'Controller';

    $controllerBuilder = new ControllerBuilder();

    $this->getGenerator()->addBuilder($controllerBuilder);

    $controllerBuilder->setTemplateName('controller'.DS.'controller.php.twig');

    $controllerBuilder->setVariable('className', $className);
    $controllerBuilder->setVariable('namespace', 'controllers\\'.$this->controllerName.'\lib');
    $controllerBuilder->setVariable('extends', 'Controller');
    $controllerBuilder->setVariable('controllerName', $this->controllerName);

    $controllerBuilder->setVariable('use', array(
      'lib\Core\Controller',
      'lib\View\View',
      'lib\Core\ControllerRouter'
    ));

    return $controllerBuilder->getCode();
  }

  private function createRouterFile(){

    $this->output->writeln('Begin create router class...');

    $routerBuilder = new RouterBuilder();
    $this->getGenerator()->addBuilder($routerBuilder);

    $routerBuilder->setTemplateName('controller'.DS.'router.php.twig');

    $routerBuilder->setOutputName('router.php');

    $routerBuilder->setVariable('controller', $this->controllerName);

    $routerBuilder->setVariable('use', array(
      'lib\Core\Core'
    ));

    return $routerBuilder->getCode();
  }
}
