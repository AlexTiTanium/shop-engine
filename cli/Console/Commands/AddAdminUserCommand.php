<?php

namespace Console\Commands;

use Symfony\Component\Console;
use models\ODM\Repositories\AdminRepository;
use Documents\Admin;
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
class AddAdminUserCommand extends Console\Command\Command {

  /**
   * @see Console\Command\Command
   */
  protected function configure() {
      $this
      ->setName('system:addAdmin')
      ->setDescription('Add admin user to system')
      ->setDefinition(array(
          new InputArgument(
              'login', InputArgument::REQUIRED,
              'Admin user login'
          ),
          new InputArgument(
              'email', InputArgument::REQUIRED,
              'Admin user e-mail'
          ),
          new InputArgument(
              'password', InputArgument::REQUIRED,
              'Admin user password'
          )
      ));

      $this->setHelp('Create admin user,  addAdmin login login@mail.com password');
  }

  protected function execute(InputInterface $input, OutputInterface $output){

    $output->writeln('Create admin user ...');

    $admin = new Admin();

    $admin->setLogin($input->getArgument('login'));
    $admin->setEmail($input->getArgument('email'));
    $admin->setPassword($input->getArgument('password'));

    AdminRepository::getRepository()->createAdmin($admin);

    $output->writeln('Creating admin user success done');
  }
}