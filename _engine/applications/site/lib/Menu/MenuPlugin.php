<?php

namespace site\lib\Menu;

use lib\Core\UrlService;
use lib\View\View;
use lib\Templates\TemplatesManager;

class MenuPlugin {

  private static $actions;

  private $thisController;
  private $currentController;

  /**
   * MenuPlugin::__construct()
   *
   * @return \site\lib\Menu\MenuPlugin
   */
  public function __construct() {
    $this->currentController = UrlService::get()->controller;
  }

  /**
   * MenuPlugin::controller()
   *
   * @param mixed $controller
   *
   * @return \site\lib\Menu\MenuPlugin
   */
  public function controller($controller) {
    $this->thisController = $controller->name;
    return $this;
  }

  /**
   * MenuPlugin::addAction()
   *
   * @param mixed $name
   * @param bool $action
   * @param bool $event
   *
   * @return void
   */
  public function addAction($name, $action = false, $event = false) {

    if ($this->thisController !== $this->currentController) {
      return;
    }

    $item = array();

    $currentAction = UrlService::get(ENGINE)->action;
    $currentController = UrlService::get(ENGINE)->controller;
    if ($currentAction === $action) {
      $item['active'] = true;
    }

    $item['name'] = $name;
    $item['url'] = UrlService::get('leftMenu')->toString(array('application' => UrlService::get(ENGINE)->application, 'controller' => $currentController, 'action' => $action, 'event' => $event));

    self::$actions[] = $item;
  }

  /**
   * MenuPlugin::sendToView()
   *
   * @return void
   */
  public static function sendToView() {
    View::get('html')->set('menu', array('actions' => self::$actions));
  }

}