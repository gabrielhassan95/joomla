<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Get an instance of the controller prefixed by PagTesouro
$controller = BaseController::getInstance('PagTesouro');

// Execute the task
$controller->execute(Factory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();