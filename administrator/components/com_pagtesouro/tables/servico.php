<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;

/**
 * Servico Table class
 *
 * @since  1.0.0
 */
class PagTesouroTableServico extends Table
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     *
     * @since   1.0.0
     */
    public function __construct(&$db)
    {
        parent::__construct('#__pagtesouro_servicos', 'id', $db);
    }

    /**
     * Overloaded check method to ensure data integrity.
     *
     * @return  boolean  True on success.
     *
     * @since   1.0.0
     */
    public function check()
    {
        // Check for valid name
        if (trim($this->descricao) == '')
        {
            $this->setError(JText::_('COM_PAGTESOURO_ERR_TABLES_NAME'));
            return false;
        }

        // Check for existing code within the same UASG
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__pagtesouro_servicos'))
            ->where($db->quoteName('uasg_id') . ' = ' . (int) $this->uasg_id)
            ->where($db->quoteName('codigo') . ' = ' . $db->quote($this->codigo));

        // Skip self
        if (!empty($this->id))
        {
            $query->where($db->quoteName('id') . ' <> ' . (int) $this->id);
        }

        $db->setQuery($query);

        if ($db->loadResult() > 0)
        {
            $this->setError(JText::_('COM_PAGTESOURO_ERR_TABLES_SERVICO_EXISTS'));
            return false;
        }

        return true;
    }
}