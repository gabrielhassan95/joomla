<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * Servicos List Model
 *
 * @since  1.0.0
 */
class PagTesouroModelServicos extends ListModel
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.0.0
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'uasg_id', 'a.uasg_id',
                'codigo', 'a.codigo',
                'descricao', 'a.descricao',
                'state', 'a.state',
                'uasg_descricao', 'ug.descricao'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function populateState($ordering = 'a.descricao', $direction = 'asc')
    {
        // Get the filter values
        $uasgId = $this->getUserStateFromRequest($this->context . '.filter.uasg_id', 'filter_uasg_id', '');
        $this->setState('filter.uasg_id', $uasgId);
        
        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   1.0.0
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.uasg_id');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.0.0
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.*, ug.descricao as uasg_descricao, CONCAT(ug.orgao, \' - \', ug.ug) as uasg_info'
            )
        );
        $query->from($db->quoteName('#__pagtesouro_servicos') . ' AS a');
        $query->join('LEFT', $db->quoteName('#__pagtesouro_uasgs') . ' AS ug ON a.uasg_id = ug.id');

        // Filter by published state
        $state = $this->getState('filter.state');
        
        if (is_numeric($state))
        {
            $query->where('a.state = ' . (int) $state);
        }
        elseif ($state === '')
        {
            $query->where('(a.state IN (0, 1))');
        }

        // Filter by UASG
        $uasgId = $this->getState('filter.uasg_id');
        
        if (is_numeric($uasgId) && $uasgId > 0)
        {
            $query->where('a.uasg_id = ' . (int) $uasgId);
        }

        // Filter by search in title
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
                $query->where('(a.descricao LIKE ' . $search . ' OR a.codigo LIKE ' . $search . ')');
            }
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.descricao');
        $orderDirn = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
}