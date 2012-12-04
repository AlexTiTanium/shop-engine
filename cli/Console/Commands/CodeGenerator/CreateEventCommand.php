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
class CreateEventCommand extends Command {

  private $controllerName;
  private $application;
  private $eventName;
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
      ->setName('system:generate:event')
      ->setDescription('Generate controller event listener for application')
      ->setDefinition(array(
          new InputArgument(
              'application', InputArgument::REQUIRED,
              'Application where will be generated event listener'
          ),
          new InputArgument(
              'newControllerName', InputArgument::REQUIRED,
              'Controller name'
          ),
          new InputArgument(
              'newEventName', InputArgument::REQUIRED,
              'Event listener name'
          )
      ));

      $this->setHelp('Generate application event listener');
  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $dc = new DirCommander(new LocalDirCommanderAdapter(), PATH_APPLICATIONS);

    $this->output = $output;
    $this->application = $input->getArgument('application');
    $this->controllerName = $input->getArgument('newControllerName');
    $this->eventName = $input->getArgument('newEventName');

    $output->writeln('Generating event listener...');

    $eventClass = $this->createEventClass();

    $dc
       ->cd($this->application)
        ->cd('controllers')
          ->cd($this->controllerName)
            ->cd('events')
            ->makeFile(ucfirst($this->eventName).'Listener.php', $eventClass)
    ;

    $output->writeln('Event listener "'.$this->eventName.'" for controller  "'.$this->controllerName.'" was generated in "'.$this->application.'" application');
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

  private function createEventClass(){

    $this->output->writeln('Begin create event listener class...');

    $eventListenerBuilder = new ControllerBuilder();

    $this->getGenerator()->addBuilder($eventListenerBuilder);

    $eventListenerBuilder->setTemplateName('controller'.DS.'event.php.twig');

    $eventListenerBuilder->setVariable('className', $this->eventName);
    $eventListenerBuilder->setVariable('extends', 'Events');

    $eventListenerBuilder->setVariable('use', array(
      'lib\Core\Events',
      'lib\EngineExceptions\SystemException'
    ));

    return $eventListenerBuilder->getCode();
  }

}
