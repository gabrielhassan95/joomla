<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Languages\Site\Helper;

use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Menus\Administrator\Helper\MenusHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper for mod_languages
 *
 * @since  1.6
 */
class LanguagesHelper
{
    /**
     * Gets a list of available languages
     *
     * @param   \Joomla\Registry\Registry  &$params  module params
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public function getLanguages(&$params)
    {
        $app        = Factory::getApplication();
        $user       = $app->getIdentity();
        $lang       = $app->getLanguage();
        $languages  = LanguageHelper::getLanguages();

        $menu       = $app->getMenu();
        $active     = $menu->getActive();

        // Get menu home items
        $homes      = [];
        $homes['*'] = $menu->getDefault('*');

        foreach ($languages as $item) {
            $default = $menu->getDefault($item->lang_code);

            if ($default && $default->language === $item->lang_code) {
                $homes[$item->lang_code] = $default;
            }
        }

        // Load associations
<<<<<<< HEAD
        $assoc        = Associations::isEnabled();
        $option       = $app->getInput()->get('option');
        $associations = [];

        if ($assoc && $option) {
=======
        $assoc = Associations::isEnabled();

        if ($assoc) {
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
            if ($active) {
                $associations = MenusHelper::getAssociations($active->id);
            }

<<<<<<< HEAD
=======
            $option    = $app->getInput()->get('option');
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
            $component = $app->bootComponent($option);

            if ($component instanceof AssociationServiceInterface) {
                $cassociations = $component->getAssociationsExtension()->getAssociationsForItem();
            } else {
                // Load component associations
                $class = str_replace('com_', '', $option) . 'HelperAssociation';
                \JLoader::register($class, JPATH_SITE . '/components/' . $option . '/helpers/association.php');

                if (class_exists($class) && \is_callable([$class, 'getAssociations'])) {
                    $cassociations = \call_user_func([$class, 'getAssociations']);
                }
            }
        }

        $levels    = $user->getAuthorisedViewLevels();
        $sitelangs = LanguageHelper::getInstalledLanguages(0);
        $multilang = Multilanguage::isEnabled();

        // Filter allowed languages
        foreach ($languages as $i => &$language) {
            // Do not display language without frontend UI
            if (!\array_key_exists($language->lang_code, $sitelangs)) {
                unset($languages[$i]);
            } elseif (!isset($homes[$language->lang_code])) {
                // Do not display language without specific home menu
                unset($languages[$i]);
            } elseif (isset($language->access) && $language->access && !\in_array($language->access, $levels)) {
                // Do not display language without authorized access level
                unset($languages[$i]);
            } else {
                $language->active = ($language->lang_code === $lang->getTag());

                // Fetch language rtl
                // If loaded language get from current JLanguage metadata
                if ($language->active) {
                    $language->rtl = $lang->isRtl();
                } else {
                    // If not loaded language fetch metadata directly for performance
                    $languageMetadata = LanguageHelper::getMetadata($language->lang_code);
                    $language->rtl    = $languageMetadata['rtl'];
                }

                if ($multilang) {
                    if (isset($cassociations[$language->lang_code])) {
                        $language->link = Route::_($cassociations[$language->lang_code]);
                    } elseif (isset($associations[$language->lang_code]) && $menu->getItem($associations[$language->lang_code])) {
                        $itemid         = $associations[$language->lang_code];
                        $language->link = Route::_('index.php?lang=' . $language->sef . '&Itemid=' . $itemid);
                    } elseif ($active && $active->language === '*') {
                        $language->link = Route::_('index.php?lang=' . $language->sef . '&Itemid=' . $active->id);
                    } else {
                        if ($language->active) {
                            $language->link = Uri::getInstance()->toString(['path', 'query']);
                        } else {
                            $itemid         = isset($homes[$language->lang_code]) ? $homes[$language->lang_code]->id : $homes['*']->id;
                            $language->link = Route::_('index.php?lang=' . $language->sef . '&Itemid=' . $itemid);
                        }
                    }
                } else {
                    $language->link = Route::_('&Itemid=' . $homes['*']->id);
                }
            }
        }

        return $languages;
    }

    /**
     * Gets a list of available languages
     *
     * @param   \Joomla\Registry\Registry  &$params  module params
     *
     * @return  array
     *
     * @deprecated 5.1.0 will be removed in 7.0
     *             Use the non-static method getLanguages
     *             Example: Factory::getApplication()->bootModule('mod_languages', 'site')
     *                          ->getHelper('LanguagesHelper')
     *                          ->getLanguages($params)
     */
    public static function getList(&$params)
    {
        return (new self())->getLanguages($params);
    }
}
