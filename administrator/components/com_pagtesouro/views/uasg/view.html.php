<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;

/**
 * View to edit a UASG
 *
 * @since  1.0.0
 */
class PagTesouroViewUasg extends HtmlView
{
    /**
     * The JForm object
     *
     * @var  JForm
     */
    protected $form;

    /**
     * The active item
     *
     * @var  object
     */
    protected $item;

    /**
     * The model state
     *
     * @var  object
     */
    protected $state;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("\n", $errors), 500);
            return false;
        }

        $this->addToolbar();

        return parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    protected function addToolbar()
    {
        Factory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        $title = Text::_('COM_PAGTESOURO_' . ($isNew ? 'NEW' : 'EDIT') . '_UASG_TITLE');
        ToolbarHelper::title($title);

        ToolbarHelper::apply('uasg.apply');
        ToolbarHelper::save('uasg.save');

        if ($isNew)
        {
            ToolbarHelper::cancel('uasg.cancel');
        }
        else
        {
            ToolbarHelper::cancel('uasg.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}