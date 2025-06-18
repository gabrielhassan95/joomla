<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * PagTesouro component helper.
 *
 * @since  1.0.0
 */
class PagTesouroHelper
{
    /**
     * Configure the Linkbar.
     *
     * @param   string  $vName  The name of the active view.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_PAGTESOURO_UASGS'),
            'index.php?option=com_pagtesouro&view=uasgs',
            $vName == 'uasgs'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_PAGTESOURO_SERVICOS'),
            'index.php?option=com_pagtesouro&view=servicos',
            $vName == 'servicos'
        );
    }
}