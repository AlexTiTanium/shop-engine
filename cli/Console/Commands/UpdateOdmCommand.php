<?php

namespace Console\Commands;

use Symfony\Component\Console;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 12.10.12
 * Time: 11:41
 * To change this template use File | Settings | File Templates.
 */
class UpdateOdmCommand extends Console\Command\Command {

  /**
   * @see Console\Command\Command
   */
  protected function configure() {

    $this
    ->setName('system:updateOdm')
    ->setDescription('Update Doctrine 2 ODM, regenerate proxy, clear cache, etc...');

    $this->setHelp('Simple batch commands for updating doctrine odm');
  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $this->getApplication()->find('odm:clear-cache:metadata')->run($input, $output);

    # Generate documents
    //------------------------------------------------------------------------------------------------->

    $generateDocumentsArg = array(
      'command'       => 'odm:generate:documents',
      'dest-path'     => PATH_MODELS_ODM,
      '--generate-annotations'=>true,
      '--extend'              =>'\lib\Doctrine\DoctrineModel'
    );

    $this->getApplication()->find('odm:generate:documents')->run(new ArrayInput($generateDocumentsArg), $output);

    //------------------------------------------------------------------------------------------------->
    # Generate hydrators
    //------------------------------------------------------------------------------------------------->

    $generateHydratorsArg = array(
      'command'   => 'odm:generate:hydrators',
      'dest-path' => PATH_ODM_HYDRATORS,
    );

    $this->getApplication()->find('odm:generate:hydrators')->run(new ArrayInput($generateHydratorsArg), $output);

    //------------------------------------------------------------------------------------------------->
    # Generate proxies
    //------------------------------------------------------------------------------------------------->

    $generateProxiesArg = array(
      'command'   => 'odm:generate:proxies',
      'dest-path' => PATH_ODM_PROXIES,
    );

    $this->getApplication()->find('odm:generate:proxies')->run(new ArrayInput($generateProxiesArg), $output);

    //------------------------------------------------------------------------------------------------->
    # Generate repositories
    //------------------------------------------------------------------------------------------------->

    $generateProxiesArg = array(
      'command'   => 'odm:generate:repositories',
      'dest-path' => PATH_ODM_REPOSITORIES,
    );

    $this->getApplication()->find('odm:generate:repositories')->run(new ArrayInput($generateProxiesArg), $output);

    //------------------------------------------------------------------------------------------------->


    $this->getApplication()->find('odm:schema:create')->run($input, $output);

  }
}