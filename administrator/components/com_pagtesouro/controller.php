<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * PagTesouro Component Controller
 *
 * @since  1.0.0
 */
class PagTesouroController extends BaseController
{
    /**
     * The default view.
     *
     * @var     string
     * @since   1.0.0
     */
    protected $default_view = 'uasgs';

    /**
     * Method to display a view.
     *
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe URL parameters and their variable types.
     *
     * @return  BaseController  This object to support chaining.
     *
     * @since   1.0.0
     */
    public function display($cachable = false, $urlparams = array())
    {
        return parent::display($cachable, $urlparams);
    }
}