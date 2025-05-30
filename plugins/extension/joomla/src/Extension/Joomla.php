<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Extension.joomla
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\Extension\Joomla\Extension;

use Joomla\CMS\Event\Extension\AfterInstallEvent;
use Joomla\CMS\Event\Extension\AfterUninstallEvent;
use Joomla\CMS\Event\Extension\AfterUpdateEvent;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\ParameterType;
use Joomla\Event\SubscriberInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Joomla! main extension plugin.
 *
 * @since  1.6
 */
final class Joomla extends CMSPlugin implements SubscriberInterface
{
    use DatabaseAwareTrait;

    /**
     * @var    integer
     *
     * @since  1.6
     */
    private $eid = 0;

    /**
     * @var    Installer
     *
     * @since  1.6
     */
    private $installer = null;

    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     *
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return array
     *
     * @since   5.2.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onExtensionAfterInstall'   => 'onExtensionAfterInstall',
            'onExtensionAfterUpdate'    => 'onExtensionAfterUpdate',
            'onExtensionAfterUninstall' => 'onExtensionAfterUninstall',
        ];
    }

    /**
     * Adds an update site to the table if it doesn't exist.
     *
     * @param   string   $name        The friendly name of the site
     * @param   string   $type        The type of site (e.g. collection or extension)
     * @param   string   $location    The URI for the site
     * @param   boolean  $enabled     If this site is enabled
     * @param   string   $extraQuery  Any additional request query to use when updating
<<<<<<< HEAD
     * @param   int      $total       The total of update sites
=======
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
     *
     * @return  void
     *
     * @since   1.6
     */
<<<<<<< HEAD
    private function addUpdateSite($name, $type, $location, $enabled, $extraQuery = '', int $total = 1)
=======
    private function addUpdateSite($name, $type, $location, $enabled, $extraQuery = '')
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
    {
        // Look if the location is used already; doesn't matter what type you can't have two types at the same address, doesn't make sense
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $query->select($db->quoteName('update_site_id'))
            ->from($db->quoteName('#__update_sites'))
            ->where($db->quoteName('location') . ' = :location')
            ->bind(':location', $location);

        $db->setQuery($query);

        $update_site_id = (int) $db->loadResult();

<<<<<<< HEAD
        // If it doesn't exist and there is an extension, use that site
        if (!$update_site_id && $this->eid && $total === 1) {
            $query->clear();
            $query->select($db->quoteName('update_site_id'))
                ->from($db->quoteName('#__update_sites_extensions'))
                ->where($db->quoteName('extension_id') . ' = :extension_id')
                ->bind(':extension_id', $this->eid, ParameterType::INTEGER);

            $db->setQuery($query);

            // When there is an existing update site, update the location and return
            if ($id = $db->loadResult()) {
                $query->clear()
                    ->update($db->quoteName('#__update_sites'))
                    ->set($db->quoteName('location') . ' = :location')
                    ->where($db->quoteName('update_site_id') . ' = :update_site_id')
                    ->bind(':location', $location)
                    ->bind(':update_site_id', $id, ParameterType::INTEGER);

                $db->setQuery($query);
                $db->execute();

                return;
            }
        }

=======
        // If it doesn't exist, add it!
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
        if (!$update_site_id) {
            $enabled = (int) $enabled;
            $query->clear()
                ->insert($db->quoteName('#__update_sites'))
                ->columns($db->quoteName(['name', 'type', 'location', 'enabled', 'extra_query']))
                ->values(':name, :type, :location, :enabled, :extra_query')
                ->bind(':name', $name)
                ->bind(':type', $type)
                ->bind(':location', $location)
                ->bind(':enabled', $enabled, ParameterType::INTEGER)
                ->bind(':extra_query', $extraQuery);

            $db->setQuery($query);

            if ($db->execute()) {
                // Link up this extension to the update site
                $update_site_id = $db->insertid();
            }
        }

        // Check if it has an update site id (creation might have failed)
        if ($update_site_id) {
            // Look for an update site entry that exists
            $query->clear()
                ->select($db->quoteName('update_site_id'))
                ->from($db->quoteName('#__update_sites_extensions'))
                ->where(
                    [
                        $db->quoteName('update_site_id') . ' = :updatesiteid',
                        $db->quoteName('extension_id') . ' = :extensionid',
                    ]
                )
                ->bind(':updatesiteid', $update_site_id, ParameterType::INTEGER)
                ->bind(':extensionid', $this->eid, ParameterType::INTEGER);

            $db->setQuery($query);

            $tmpid = (int) $db->loadResult();

            if (!$tmpid) {
                // Link this extension to the relevant update site
                $query->clear()
                    ->insert($db->quoteName('#__update_sites_extensions'))
                    ->columns($db->quoteName(['update_site_id', 'extension_id']))
                    ->values(':updatesiteid, :eid')
                    ->bind(':updatesiteid', $update_site_id, ParameterType::INTEGER)
                    ->bind(':eid', $this->eid, ParameterType::INTEGER);

                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    /**
     * Handle post extension install update sites
     *
     * @param   AfterInstallEvent $event  Event instance.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function onExtensionAfterInstall(AfterInstallEvent $event): void
    {
        $eid = $event->getEid();

        if ($eid) {
            $this->installer = $event->getInstaller();
            $this->eid       = (int) $eid;

            // After an install we only need to do update sites
            $this->processUpdateSites();
        }
    }

    /**
     * Handle extension uninstall
     *
     * @param   AfterUninstallEvent $event  Event instance.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function onExtensionAfterUninstall(AfterUninstallEvent $event): void
    {
        $eid     = $event->getEid();
        $removed = $event->getRemoved();

        // If we have a valid extension ID and the extension was successfully uninstalled wipe out any
        // update sites for it
        if ($eid && $removed) {
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);
            $eid   = (int) $eid;

            $query->delete($db->quoteName('#__update_sites_extensions'))
                ->where($db->quoteName('extension_id') . ' = :eid')
                ->bind(':eid', $eid, ParameterType::INTEGER);

            $db->setQuery($query);
            $db->execute();

            // Delete any unused update sites
            $query->clear()
                ->select($db->quoteName('update_site_id'))
                ->from($db->quoteName('#__update_sites_extensions'));

            $db->setQuery($query);
            $results = $db->loadColumn();

            if (\is_array($results)) {
                // So we need to delete the update sites and their associated updates
                $updatesite_delete = $db->getQuery(true);
                $updatesite_delete->delete($db->quoteName('#__update_sites'));

                $updatesite_query = $db->getQuery(true);
                $updatesite_query->select($db->quoteName('update_site_id'))
                    ->from($db->quoteName('#__update_sites'));

                // If we get results back then we can exclude them
                if (\count($results)) {
                    $updatesite_query->whereNotIn($db->quoteName('update_site_id'), $results);
                    $updatesite_delete->whereNotIn($db->quoteName('update_site_id'), $results);
                }

                // So let's find what update sites we're about to nuke and remove their associated extensions
                $db->setQuery($updatesite_query);
                $update_sites_pending_delete = $db->loadColumn();

                if (\is_array($update_sites_pending_delete) && \count($update_sites_pending_delete)) {
                    // Nuke any pending updates with this site before we delete it
                    // @todo: investigate alternative of using a query after the delete below with a query and not in like above
                    $query->clear()
                        ->delete($db->quoteName('#__updates'))
                        ->whereIn($db->quoteName('update_site_id'), $update_sites_pending_delete);

                    $db->setQuery($query);
                    $db->execute();
                }

                // Note: this might wipe out the entire table if there are no extensions linked
                $db->setQuery($updatesite_delete);
                $db->execute();
            }

            // Last but not least we wipe out any pending updates for the extension
            $query->clear()
                ->delete($db->quoteName('#__updates'))
                ->where($db->quoteName('extension_id') . ' = :eid')
                ->bind(':eid', $eid, ParameterType::INTEGER);

            $db->setQuery($query);
            $db->execute();
        }
    }

    /**
     * After update of an extension
     *
     * @param   AfterUpdateEvent $event  Event instance.
     *
     * @return  void
     *
     * @since   1.6
     */
    public function onExtensionAfterUpdate(AfterUpdateEvent $event): void
    {
        $eid = $event->getEid();

        if ($eid) {
            $this->installer = $event->getInstaller();
            $this->eid       = (int) $eid;

            // Handle any update sites
            $this->processUpdateSites();
        }
    }

    /**
     * Processes the list of update sites for an extension.
     *
     * @return  void
     *
     * @since   1.6
     */
    private function processUpdateSites()
    {
        $manifest      = $this->installer->getManifest();
        $updateservers = $manifest->updateservers;

        if ($updateservers) {
            $children = $updateservers->children();
        } else {
            $children = [];
        }

        if (\count($children)) {
            foreach ($children as $child) {
                $attrs = $child->attributes();
<<<<<<< HEAD
                $this->addUpdateSite((string) $attrs['name'], (string) $attrs['type'], trim($child), true, $this->installer->extraQuery, \count($children));
=======
                $this->addUpdateSite((string) $attrs['name'], (string) $attrs['type'], trim($child), true, $this->installer->extraQuery);
>>>>>>> 954ffb7afcbc309937911469236d138fc7d262f9
            }
        } else {
            $data = trim((string) $updateservers);

            if ($data !== '') {
                // We have a single entry in the update server line, let us presume this is an extension line
                $this->addUpdateSite(Text::_('PLG_EXTENSION_JOOMLA_UNKNOWN_SITE'), 'extension', $data, true);
            }
        }
    }
}
