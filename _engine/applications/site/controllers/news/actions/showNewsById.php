<?php

$url = Url::get(ENGINE);
$view = View::get($url->type);
$controller = Core::getController('news');
$id = $url->id;

if(!$id){ throw new SystemException('Not found id'); }

$cache = new Cache('siteContrNews_'.$id);
$content = $cache->get(Config::get('cache')->newsOne);

if(!$content){
  
  $template = Tpl::load('showNewsById');
  
  $oneNews = NewsClass::get($id);
  $template->set($oneNews);

  if(!$oneNews){ throw new SystemException('Not found news for id:'.$id); }

  $content = $cache->set($template->toString()); 
}

$view->set('content', $content);