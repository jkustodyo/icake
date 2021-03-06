<?php
	// página principal
	//Router::connect('/', array('controller' => 'usuarios', 'action' => 'login', 'login'));
	Router::connect('/', array('plugin'=>'con', 'controller' => 'contatos', 'action' => 'buscar'));

	// páginas de ajuda
	Router::connect('/ajuda/*', array('controller' => 'ajuda', 'action' => 'pag'));

	// Carregando todas as rotas de plugin
	CakePlugin::routes();

	// Caregando as rotas padrão do core
	require CAKE . 'Config' . DS . 'routes.php';
