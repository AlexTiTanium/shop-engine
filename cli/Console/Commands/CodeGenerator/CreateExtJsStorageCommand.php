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
class CreateExtJsStorageCommand extends Command {

  private $applicationName;
  private $storageName;
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
      ->setName('system:generate:extjs:storage')
      ->setDescription('Generate extJs storage for application')
      ->setDefinition(array(
          new InputArgument(
              'applicationName', InputArgument::REQUIRED,
              'The name of application where need create model'
          ),
          new InputArgument(
              'modelName', InputArgument::REQUIRED,
              'The name of model'
          ),
          new InputArgument(
              'storageName', InputArgument::REQUIRED,
              'The name of new storage'
          )
      ));

      $this->setHelp('Generate extJs storage for application');
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
    $this->storageName = $input->getArgument('storageName');

    $output->writeln('Generating storage...');

    $dc
      ->cd('admin/desktop/applications')
      ->cd($this->applicationName)->cd('store')
        ->makeFile(ucfirst($this->storageName).'Store.js', $this->createStorageObject())
    ;

    $output->writeln('The storage "'.$this->modelName.'" for application "'.$this->applicationName.'" was generated');
  }

  private function createStorageObject(){

    $this->output->writeln('Begin create storage object...');

    $builder = new UniversalBuilder();

    $this->getGenerator()->addBuilder($builder);

    $builder->setTemplateName('extjs'.DS.'storage.js.twig');

    $builder->setVariable('appName',      $this->applicationName);
    $builder->setVariable('modelName',    $this->modelName);
    $builder->setVariable('storageName',  $this->storageName);

    return $builder->getCode();
  }
}
