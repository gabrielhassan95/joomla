<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Modelo Formulario para o componente PagTesouro
 */
class PagTesouroModelFormulario extends ListModel
{
    /**
     * Método para obter UASGs
     *
     * @return  array  Uma lista de UASGs
     */
    public function getUasgs()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('id, orgao, ug, descricao')
            ->from($db->quoteName('#__pagtesouro_uasgs'))
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('descricao') . ' ASC');
            
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }
    
    /**
     * Método para obter uma UASG específica
     *
     * @param   int  $id  ID da UASG
     *
     * @return  object  Dados da UASG
     */
    public function getUasg($id)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__pagtesouro_uasgs'))
            ->where($db->quoteName('id') . ' = ' . (int) $id)
            ->where($db->quoteName('state') . ' = 1');
            
        $db->setQuery($query);
        
        return $db->loadObject();
    }
    
    /**
     * Método para obter serviços para uma UASG
     *
     * @param   int  $uasgId  ID da UASG
     *
     * @return  array  Lista de serviços
     */
    public function getServicos($uasgId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('id, codigo, descricao')
            ->from($db->quoteName('#__pagtesouro_servicos'))
            ->where($db->quoteName('uasg_id') . ' = ' . (int) $uasgId)
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('descricao') . ' ASC');
            
        $db->setQuery($query);
        
        return $db->loadObjectList();
    }
    
    /**
     * Método para obter um serviço específico
     *
     * @param   int  $id  ID do serviço
     *
     * @return  object  Dados do serviço
     */
    public function getServico($id)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('a.*, u.orgao, u.ug')
            ->from($db->quoteName('#__pagtesouro_servicos', 'a'))
            ->join('LEFT', $db->quoteName('#__pagtesouro_uasgs', 'u') . ' ON ' . $db->quoteName('a.uasg_id') . ' = ' . $db->quoteName('u.id'))
            ->where($db->quoteName('a.id') . ' = ' . (int) $id)
            ->where($db->quoteName('a.state') . ' = 1');
            
        $db->setQuery($query);
        
        return $db->loadObject();
    }
}