<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;

/**
 * Raw View
 */
class PagTesouroViewRaw extends HtmlView
{
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        // Obtenha a aplicação
        $app = Factory::getApplication();
        
        // Defina o tipo de conteúdo como JSON
        $app->setHeader('Content-Type', 'application/json');
        
        // Obtenha o input
        $input = $app->input;
        
        // Obtenha a tarefa
        $task = $input->get('task', '', 'cmd');
        
        // Processe a tarefa
        if (strpos($task, 'formulario.') === 0) {
            $controllerName = substr($task, 0, strpos($task, '.'));
            $methodName = substr($task, strpos($task, '.') + 1);
            
            // Carregue o controlador
            require_once JPATH_COMPONENT . '/controllers/' . $controllerName . '.php';
            $className = 'PagTesouroController' . ucfirst($controllerName);
            
            if (class_exists($className)) {
                $controller = new $className();
                
                if (method_exists($controller, $methodName)) {
                    // Execute o método
                    $controller->$methodName();
                    
                    // Encerre a aplicação
                    $app->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Método não encontrado: ' . $methodName]);
                    $app->close();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Controlador não encontrado: ' . $className]);
                $app->close();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma tarefa especificada']);
            $app->close();
        }
        
        return true;
    }
}