<?php

$url = Url::get(ENGINE);
$view = View::get($url->type);
$controller = Core::getController('news');

$cache = new Cache('newsBlockOnMain');
$template = $cache->get(Config::get('cache')->newsBlockOnMain);

if(!$template){
  $template = Tpl::load('newsOnMainPage');
  $news = NewsClass::getLastNews(3);
  $template->set('news',$news);

  $template = $cache->set($template->toString());
}

$view->set('newsBlock', $template);
$view->getTemplate('content')->set('newsBlock',$template);