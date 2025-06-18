<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;

/**
 * Formulario Controller
 */
class PagTesouroControllerFormulario extends BaseController
{
    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        
        // Registrar tarefas para métodos específicos
        $this->registerTask('getUasgDetails', 'ajaxResponse');
        $this->registerTask('getServicos', 'ajaxResponse');
        $this->registerTask('getServicoDetails', 'ajaxResponse');
    }
    
    /**
     * Método genérico para processar requisições AJAX
     *
     * @return void
     */
    public function ajaxResponse()
    {
        // Desabilitar redirecionamento
        $this->setRedirect('');
        
        $app = Factory::getApplication();
        $task = $this->input->get('task', '', 'cmd');
        
        // Remover o prefixo "formulario."
        $method = str_replace('formulario.', '', $task);
        
        // Verificar se o método existe
        if (method_exists($this, $method)) {
            // Chamar o método específico
            $this->$method();
        } else {
            // Método não encontrado
            $app->setHeader('Content-Type', 'application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'Método não encontrado: ' . $method
            ));
            $app->close();
        }
    }
    
    /**
     * Método para obter detalhes de uma UASG
     *
     * @return  void
     */
    public function getUasgDetails()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        
        // Usar o parâmetro correto conforme sua URL
        $uasgId = $input->getInt('uasg_id', 0);
        
        // Obter o modelo
        $model = $this->getModel('Formulario', 'PagTesouroModel');
        if (!$model) {
            // Tentar carregar o modelo sem prefixo específico
            $model = $this->getModel('Formulario');
        }
        
        $uasg = null;
        if ($model) {
            $uasg = $model->getUasg($uasgId);
        }
        
        $response = array(
            'success' => ($uasg !== null),
            'data' => $uasg,
        );
        
        if (!$uasg) {
            $response['message'] = 'UASG não encontrada';
        }
        
        // Define o tipo de conteúdo como JSON
        $app->setHeader('Content-Type', 'application/json');
        
        // Retorne os dados como JSON
        echo json_encode($response);
        
        // Feche a aplicação
        $app->close();
    }
    
    /**
     * Método para obter serviços para uma UASG
     */
    public function getServicos()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $uasgId = $input->getInt('uasg_id', 0);
        
        // Obter o modelo
        $model = $this->getModel('Formulario', 'PagTesouroModel');
        if (!$model) {
            // Tentar carregar o modelo sem prefixo específico
            $model = $this->getModel('Formulario');
        }
        
        $servicos = array();
        if ($model) {
            $servicos = $model->getServicos($uasgId);
        }
        
        $response = array(
            'success' => !empty($servicos),
            'data' => $servicos,
        );
        
        if (empty($servicos)) {
            $response['message'] = 'Nenhum serviço encontrado';
        }
        
        // Define o tipo de conteúdo como JSON
        $app->setHeader('Content-Type', 'application/json');
        
        // Retorne os dados como JSON
        echo json_encode($response);
        
        // Feche a aplicação
        $app->close();
    }
    
    /**
     * Método para obter detalhes de um serviço
     */
    public function getServicoDetails()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $servicoId = $input->getInt('servico_id', 0);
        
        // Obter o modelo
        $model = $this->getModel('Formulario', 'PagTesouroModel');
        if (!$model) {
            // Tentar carregar o modelo sem prefixo específico
            $model = $this->getModel('Formulario');
        }
        
        $servico = null;
        if ($model) {
            $servico = $model->getServico($servicoId);
        }
        
        $response = array(
            'success' => ($servico !== null),
            'data' => $servico,
        );
        
        if (!$servico) {
            $response['message'] = 'Serviço não encontrado';
        }
        
        // Define o tipo de conteúdo como JSON
        $app->setHeader('Content-Type', 'application/json');
        
        // Retorne os dados como JSON
        echo json_encode($response);
        
        // Feche a aplicação
        $app->close();
    }
}