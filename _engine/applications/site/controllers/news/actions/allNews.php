<?php

$url = Url::get(ENGINE);
$view = View::get($url->type);
$controller = Core::getController('news');
$page = $url->page;

$template = Tpl::load('allNews');
$pager = new Paginator(NewsClass::getAllNews(), $page, 10);

$template->set($pager->toArray());

$view->addTemplate('content', $template);