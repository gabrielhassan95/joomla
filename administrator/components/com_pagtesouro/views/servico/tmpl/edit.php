<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// Carregando scripts para o Joomla 4
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

// Bootstrap e jQuery são necessários para o Joomla 4
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.framework');
HTMLHelper::_('bootstrap.tooltip');
?>
<form action="<?php echo Route::_('index.php?option=com_pagtesouro&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    
    <div class="form-horizontal">
        <fieldset class="adminform">
            <legend><?php echo Text::_('COM_PAGTESOURO_SERVICO_DETAILS'); ?></legend>
            <div class="row-fluid">
                <div class="span6">
                    <?php foreach ($this->form->getFieldset() as $field) : ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $field->label; ?>
                            </div>
                            <div class="controls">
                                <?php echo $field->input; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </fieldset>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>