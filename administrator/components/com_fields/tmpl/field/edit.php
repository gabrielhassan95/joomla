<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_fields
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Fields\Administrator\View\Field\HtmlView $this */

$app = Factory::getApplication();
$input = $app->getInput();

$this->useCoreUI = true;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
    ->useScript('form.validate')
    ->useScript('com_fields.admin-field-edit');

?>

<form action="<?php echo Route::_('index.php?option=com_fields&context=' . $input->getCmd('context', 'com_content') . '&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" aria-label="<?php echo Text::_('COM_FIELDS_FIELD_FORM_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>" class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'general', 'recall' => true, 'breakpoint' => 768]); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_FIELDS_VIEW_FIELD_FIELDSET_GENERAL', true)); ?>
        <div class="row">
            <div class="col-lg-9">
                <?php echo $this->form->renderField('type'); ?>
                <?php echo $this->form->renderField('name'); ?>
                <?php echo $this->form->renderField('label'); ?>
                <?php echo $this->form->renderField('description'); ?>
                <?php echo $this->form->renderField('required'); ?>
                <?php echo $this->form->renderField('only_use_in_subform'); ?>
                <?php echo $this->form->renderField('default_value'); ?>

                <?php foreach ($this->form->getFieldsets('fieldparams') as $name => $fieldSet) : ?>
                    <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                        <?php echo $field->renderField(); ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-3">
                <?php $this->fields = [
                        [
                            'published',
                            'state',
                            'enabled',
                        ],
                        'group_id',
                        'assigned_cat_ids',
                        'access',
                        'language',
                        'note',
                    ]; ?>
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
                <?php $this->fields = null; ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php $this->ignore_fieldsets = ['fieldparams']; ?>
        <?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
        <fieldset id="fieldset-publishingdata" class="options-form">
            <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
            <div>
            <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
            <?php echo $this->form->renderField('searchindexing'); ?>
            </div>
        </fieldset>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php if ($this->canDo->get('core.admin')) : ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'rules', Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
                <fieldset id="fieldset-rules" class="options-form">
                    <legend><?php echo Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL'); ?></legend>
                    <div>
                    <?php echo $this->form->getInput('rules'); ?>
                    </div>
                </fieldset>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endif; ?>
        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
        <?php echo $this->form->getInput('context'); ?>
        <input type="hidden" name="task" value="">
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
