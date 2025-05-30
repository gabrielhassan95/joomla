<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

// Create shortcuts to some parameters.
$params  = $this->item->params;
$user    = Factory::getUser();
$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

$currentDate       = Factory::getDate()->format('Y-m-d H:i:s');
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isExpired         = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;
?>
<div class="com-content-article item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? Factory::getApplication()->get('language') : $this->item->language; ?>">
	<?php if (Factory::getApplication()->getMenu()->getActive() != Factory::getApplication()->getMenu()->getDefault()) : ?>
	<div class="br-divider"></div>
	<?php endif; ?>

	<?php $showHeader = $this->params->get('show_page_heading') || $params->get('show_title'); ?>
	<?php if ($showHeader) : ?>
	<div class="pt-4"></div>
	<?php if ($this->params->get('show_page_heading')) : ?>
	<p class="page-header"> <?php echo $this->escape($this->params->get('page_heading')); ?> </p>
	<?php endif; ?>
	<?php if ($params->get('show_title')) : ?>
		<<?php echo $htag; ?> itemprop="headline">
			<?php echo $this->escape($this->item->title); ?>
		</<?php echo $htag; ?>>
		<?php if (!empty($this->item->fulltext)) : ?>
			<div itemprop="description">
				<?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
			</div>
		<?php endif; ?>
	<div class="pt-4"></div>
	<div class="br-divider"></div>
	<?php endif; ?>
	<?php endif; ?>

	<?php // Content is generated by content plugin event "onContentAfterTitle" ?>
	<?php echo $this->item->event->afterDisplayTitle; ?>

	<?php $showInfo = $params->get('show_modify_date') || $params->get('show_publish_date'); ?>
	<?php if ($showInfo) : ?>
	<div class="article-info pt-2 pb-2 d-flex">
	<?php if ($params->get('show_publish_date')) :?>
		<div><?php echo Text::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', HTMLHelper::_('date', $this->item->publish_up, Text::_('d/m/Y H:i'))); ?></div>
	<?php endif; ?>
	<?php if ($params->get('show_modify_date') && $params->get('show_publish_date')) :?>
		<div class="br-divider vertical mx-3"></div>
	<?php endif; ?>
	<?php if ($params->get('show_modify_date')) :?>
		<div><?php echo Text::sprintf('COM_CONTENT_LAST_UPDATED', HTMLHelper::_('date', $this->item->modified, Text::_('d/m/Y H:i'))); ?></div>
	<?php endif; ?>
	</div>
	<div class="br-divider"></div>
	<?php endif; ?>
	
	<?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if ((int) $params->get('urls_position', 0) === 0) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php if ($params->get('access-view')) : ?>
	<?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>
	<div class="com-content-article__body pt-5 pb-4">
		<?php if (empty($this->item->fulltext)) : ?>
			<?php echo HTMLHelper::_('content.prepare', $this->item->text); ?>
		<?php else : ?>
			<?php echo HTMLHelper::_('content.prepare', $this->item->fulltext); ?>
		<?php endif; ?>
	</div>

	<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		<div class="tags pb-4">
			<span>Tags:</span>
			<?php $authorised = $user->getAuthorisedViewLevels(); ?>
			<?php foreach ($this->item->tags->itemTags as $tag) : ?>
				<?php if (in_array($tag->access, $authorised)) : ?>
					<span>
						<a href="<?php echo Route::_(Joomla\Component\Tags\Site\Helper\RouteHelper::getComponentTagRoute($tag->tag_id . ':' . $tag->alias, $tag->language)); ?>" class="tags-link">
							<?php echo $this->escape($tag->title); ?>
						</a>
					</span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ((int) $params->get('urls_position', 0) === 1) : ?>
	<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php // Optional teaser intro text for guests ?>
	<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
	<?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
	<?php // Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
	<?php $menu = Factory::getApplication()->getMenu(); ?>
	<?php $active = $menu->getActive(); ?>
	<?php $itemId = $active->id; ?>
	<?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
	<?php $link->setVar('return', base64_encode(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
	<?php echo LayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>
	<?php endif; ?>
	<?php endif; ?>
	<?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
	<?php if (Factory::getApplication()->getMenu()->getActive() != Factory::getApplication()->getMenu()->getDefault()) : ?>
	<div class="br-divider"></div>
	<?php endif; ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div>
