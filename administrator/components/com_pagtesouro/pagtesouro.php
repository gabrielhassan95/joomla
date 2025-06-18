<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// Access check
if (!Factory::getUser()->authorise('core.manage', 'com_pagtesouro')) {
    throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
JLoader::register('PagTesouroHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/pagtesouro.php');

// Execute the task
$controller = JControllerLegacy::getInstance('PagTesouro');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();