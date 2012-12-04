<?php

use lib\Core\Events;
use lib\EngineExceptions\SystemException;
use Documents\Admin;
use lib\Debugger\Debugger;
use lib\ExtJs\GridHelperOdm;
use models\ODM\Repositories\AdminRepository;
use lib\Core\Manager;
use lib\Core\Log;
use lib\Session\Session;
use lib\Core\Data;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 23.09.12
 * Time: 10:36
 * To change this template use File | Settings | File Templates.
 */
class DefaultListener extends Events {

  /**
   * Create admin user
   */
  public function create(){

    $data = $this->post->getJsonRequest('data', $this->getConfig('createAdminData'));

    $admin = new Admin();

    $admin->setEmail($data->getRequired('email'));
    $admin->setPassword($data->getRequired('password'));
    $admin->setLogin($data->getRequired('login'));

    AdminRepository::getRepository()->createAdmin($admin);

    $this->view->set('data', $admin->toFlatArray('password'));
  }

  /**
   * Update admin
   */
  public function update(){

    $data = $this->post->getJsonRequest('data');

    /**
     * @var \Documents\Admin $admin
     */
    $admin = AdminRepository::getRepository()->find($data->getRequired('id'));

    $admin->setLogin($data->getRequired('login'));
    $admin->setEmail($data->getRequired('email'));
    $admin->setEnable($data->getRequired('enable'));

    AdminRepository::getRepository()->updateAdmin($admin);

    $this->view->set('data', $admin->toFlatArray('password'));
  }

  /**
   * Delete admin
   */
  public function destroy(){

    $data = $this->post->getJsonRequest('data');
    $repo = AdminRepository::getRepository();

    /**
     * @var \Documents\Admin $admin
     */
    $admin = $repo->find($data->getRequired('id'));

    if(!$admin){ throw new SystemException('User not found'); }

    $repo->deleteAdmin($admin);
  }

  /**
   * List admins
   */
  public function defaultEvent(){

    $adminQb = AdminRepository::getRepository()->createQueryBuilder();
    $grid = new GridHelperOdm($adminQb, $this->get);

    $this->view->set($grid->getList('password'));
  }
}
