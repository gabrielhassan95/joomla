<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Model\BaseModel;

// Obtenha a aplicação
$app = Factory::getApplication();
$input = $app->input;

// Adicione o caminho para os modelos
BaseModel::addIncludePath(JPATH_COMPONENT . '/models');

// Obtenha o controlador que manipulará esta requisição
$task = $input->get('task', '');

// Verificar se é uma tarefa AJAX específica
if (strpos($task, 'formulario.get') === 0) {
    // É uma chamada AJAX, carregue o controlador específico
    require_once JPATH_COMPONENT . '/controllers/formulario.php';
    $controller = new PagTesouroControllerFormulario();
    
    // Importante: Desabilitar a opção de display padrão do Joomla
    $controller->registerTask($task, 'display');
    
    // Executar a tarefa
    $controller->execute($task);
    
    // Não é necessário redirecionar, pois já fechamos a aplicação
    // Mas vamos deixar aqui como segurança
    $controller->redirect();
} else {
    // Para requisições normais, use o controlador padrão
    require_once JPATH_COMPONENT . '/controller.php';
    $controller = new PagTesouroController();
    $controller->execute($input->get('task', 'display'));
    $controller->redirect();
}