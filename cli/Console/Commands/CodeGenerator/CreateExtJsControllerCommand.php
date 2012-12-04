<?php

namespace Console\Commands\CodeGenerator;

use Symfony\Component\Console\Command\Command;
use Console\Commands\CodeGenerator\Builders\UniversalBuilder;
use TwigGenerator\Builder\BaseBuilder;
use lib\Core\DirCommander\Adapters\LocalDirCommanderAdapter;
use lib\Core\DirCommander;
use TwigGenerator\Builder\Generator;
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
class CreateExtJsControllerCommand extends Command {

  private $applicationName;
  private $controllerName;
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
      ->setName('system:generate:extjs:controller')
      ->setDescription('Generate extJs application controller for backend')
      ->setDefinition(array(
          new InputArgument(
              'applicationName', InputArgument::REQUIRED,
              'The name of application where need create controller'
          ),
          new InputArgument(
              'controllerName', InputArgument::REQUIRED,
              'The name of new controller application'
          )
      ));

      $this->setHelp('Generate extJs controller application');
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

  protected function execute(InputInterface $input, OutputInterface $output){

    $dc = new DirCommander(new LocalDirCommanderAdapter(), PATH_TEMPLATES);

    $this->output = $output;
    $this->applicationName = $input->getArgument('applicationName');
    $this->controllerName = $input->getArgument('controllerName');

    $output->writeln('Generating controller...');

    $dc
      ->cd('admin/desktop/applications')
      ->cd($this->applicationName)->cd('controller')
        ->makeFile(ucfirst($this->controllerName).'.js', $this->createControllerObject())
    ;

    $output->writeln('The controller "'.$this->controllerName.'" for application "'.$this->applicationName.'" was generated');
  }

  private function createControllerObject(){

    $this->output->writeln('Begin create controller object...');

    $builder = new UniversalBuilder();

    $this->getGenerator()->addBuilder($builder);

    $builder->setTemplateName('extjs'.DS.'controller.js.twig');

    $builder->setVariable('appName', $this->applicationName);
    $builder->setVariable('controllerName', $this->controllerName);

    return $builder->getCode();
  }
}
