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
class CreateExtJsModelCommand extends Command {

  private $applicationName;
  private $modelName;
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
      ->setName('system:generate:extjs:model')
      ->setDescription('Generate extJs model for application')
      ->setDefinition(array(
          new InputArgument(
              'applicationName', InputArgument::REQUIRED,
              'The name of application where need create model'
          ),
          new InputArgument(
              'modelName', InputArgument::REQUIRED,
              'The name of new model'
          )
      ));

      $this->setHelp('Generate extJs model for application');
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
    $this->modelName = $input->getArgument('modelName');

    $output->writeln('Generating model...');

    $dc
      ->cd('admin/desktop/applications')
      ->cd($this->applicationName)->cd('model')
        ->makeFile(ucfirst($this->modelName).'Model.js', $this->createModelObject())
    ;

    $output->writeln('The model "'.$this->modelName.'" for application "'.$this->applicationName.'" was generated');
  }

  private function createModelObject(){

    $this->output->writeln('Begin create model object...');

    $builder = new UniversalBuilder();

    $this->getGenerator()->addBuilder($builder);

    $builder->setTemplateName('extjs'.DS.'model.js.twig');

    $builder->setVariable('appName', $this->applicationName);
    $builder->setVariable('modelName', $this->modelName);

    return $builder->getCode();
  }
}
