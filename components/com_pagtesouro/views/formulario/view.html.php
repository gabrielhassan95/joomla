<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;

/**
 * Formulario View
 *
 * @since  1.0.0
 */
class PagTesouroViewFormulario extends HtmlView
{
    /**
     * The list of UASGs
     *
     * @var  array
     */
    protected $uasgs;

    /**
     * Display the view.
     *
     * @param   string  $tpl  The name of the template file to parse.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        // Certifique-se de que o modelo está sendo carregado corretamente
        $model = $this->getModel('Formulario');
        if (!$model) {
            JError::raiseError(500, 'Modelo "Formulario" não encontrado.');
            return false;
        }
        
        // Obtenha as UASGs e passe para a view
        $this->uasgs = $model->getUasgs();
        
        // Adicione depuração para verificar se as UASGs estão sendo obtidas
        if (empty($this->uasgs)) {
            JFactory::getApplication()->enqueueMessage('Nenhuma UASG encontrada. Por favor, cadastre UASGs no administrador.', 'warning');
        }
        
        parent::display($tpl);
    }
}