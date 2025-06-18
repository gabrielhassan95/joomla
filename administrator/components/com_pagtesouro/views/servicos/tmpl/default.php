<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_pagtesouro
 *
 * @copyright   Copyright (C) 2025 Astatonn. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_pagtesouro&view=servicos'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
    <?php endif; ?>
        <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <table class="table table-striped" id="servicoList">
                <thead>
                    <tr>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JHtml::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_UASG', 'uasg_descricao', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JHtml::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_CODIGO', 'a.codigo', $listDirn, $listOrder); ?>
                        </th>
                        <th>
                            <?php echo JHtml::_('searchtools.sort', 'COM_PAGTESOURO_HEADING_DESCRICAO', 'a.descricao', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($this->items as $i => $item) : 
                    $canEdit = $user->authorise('core.edit', 'com_pagtesouro');
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center hidden-phone">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'servicos.', true, 'cb'); ?>
                        </td>
                        <td class="small hidden-phone">
                            <?php echo $this->escape($item->uasg_info); ?>
                        </td>
                        <td class="small hidden-phone">
                            <?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_pagtesouro&task=servico.edit&id=' . (int) $item->id); ?>">
                                    <?php echo $this->escape($item->codigo); ?>
                                </a>
                            <?php else : ?>
                                <?php echo $this->escape($item->codigo); ?>
                            <?php endif; ?>
                        </td>
                        <td class="small hidden-phone">
                            <?php echo $this->escape($item->descricao); ?>
                        </td>
                        <td class="center hidden-phone">
                            <?php echo (int) $item->id; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo $this->pagination->getListFooter(); ?>
        <?php endif; ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>