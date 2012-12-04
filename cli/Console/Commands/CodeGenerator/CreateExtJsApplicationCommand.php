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
class CreateExtJsApplicationCommand extends Command {

  private $applicationName;
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
      ->setName('system:generate:extjs:application')
      ->setDescription('Generate extJs application for backend')
      ->setDefinition(array(
          new InputArgument(
              'application', InputArgument::REQUIRED,
              'The name of new application'
          )

      ));

      $this->setHelp('Generate extJs application frontend');
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
    $this->applicationName = $input->getArgument('application');

    $output->writeln('Generating application...');

    $applicationObject = $this->createApplicationObject();
    $viewClass = $this->createViewClass();

    $dc
      ->cd('admin/desktop/applications')
      ->makeDir($this->applicationName)
      ->makeFile(ucfirst($this->applicationName).'.js', $applicationObject)
        ->cd($this->applicationName)
          ->makeDir('controller')
          ->makeDir('model')
          ->makeDir('store')
          ->makeDir('view')
            ->cd('view')->makeFile('Main.js', $viewClass)
    ;

    $output->writeln('The application "'.$this->applicationName.'" was generated');
  }

  private function createApplicationObject(){

    $this->output->writeln('Begin create application object...');

    $builder = new UniversalBuilder();

    $this->getGenerator()->addBuilder($builder);

    $builder->setTemplateName('extjs'.DS.'app.js.twig');
    $builder->setVariable('name', $this->applicationName);

    return $builder->getCode();
  }


  private function createViewClass(){

    $this->output->writeln('Begin create view object...');

    $builder = new UniversalBuilder();

    $this->getGenerator()->addBuilder($builder);

    $builder->setTemplateName('extjs'.DS.'view.js.twig');
    $builder->setVariable('name', $this->applicationName);

    return $builder->getCode();
  }
}
