<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * UASGs list controller class.
 *
 * @since  1.0.0
 */
class PagTesouroControllerUasgs extends AdminController
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  The array of possible config values. Optional.
     *
     * @return  JModelLegacy
     *
     * @since   1.0.0
     */
    public function getModel($name = 'Uasg', $prefix = 'PagTesouroModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}