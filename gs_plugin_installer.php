<?php

/**
 * Plugin Name: GS Plugin Installer
 * Description: Lets you browse, install and uninstall plugins from your administration area.
 * Version: 1.2.6
 * Author: Helge Sverre
 * Author URI: https://helgesverre.com/
 */


// No direct access
defined('IN_GS') or die('Cannot load plugin directly.');

// Gets the plugin id, which is pretty much just the filename without the extension
$thisfile = basename(__FILE__, ".php");

// Register this plugin
register_plugin(
    $thisfile,
    'GS Plugin Installer',
    '1.2.6',
    'Helge Sverre',
    'https://helgesverre.com/',
    'Let\'s you browse, install and uninstall plugins from your administration area.',
    'plugins',
    'gs_plugin_installer_main'
);


// Register scripts and styles
register_script('datatables_js', 'http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js', '1.0');
register_script('gs_plugin_installer_js', $SITEURL . 'plugins/gs_plugin_installer/js/script.js', '0.1');

register_style('datatables_css', 'http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css', '1.0', 'screen');
register_style('gs_plugin_installer_css', $SITEURL . 'plugins/gs_plugin_installer/css/style.css', '0.1');


// Queue the scripts  &styles
queue_script('datatables_js', GSBACK);
queue_script('gs_plugin_installer_js', GSBACK);

queue_style('gs_plugin_installer_css', GSBACK);
queue_style('datatables_css', GSBACK);


// add a link in the admin tab 'plugins'
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, "Plugin Installer"));

// Import plugin localization files
i18n_merge('gs_plugin_installer');
error_reporting(E_ALL);
ini_set("displayer", "on");

require_once($thisfile . "/PluginInstaller.class.php");

// Main plugin function
function gs_plugin_installer_main()
{

    $pluginInstaller = new PluginInstaller("gs_plugin_installer/plugin_cache.json");



    if (isset($_GET["update"])) {
        $pluginInstaller->deleteCache();
        ?>
        <script>
        $(function () {
            $('div.bodycontent').before('<div class="updated" style="display:block;"><?php i18n("gs_plugin_installer/CACHE_REFRESHED"); ?></div>');
                $('.updated, .error').fadeOut(300).fadeIn(500);
            });
        </script>
        <?php
    }


    if (isset($_GET["install"])) {
        $plugin_ids = $_GET["plugins"];
        $installed = 0;

        if (is_array($plugin_ids)) {
            foreach($plugin_ids as $plugin_id) {
                if($pluginInstaller->installPlugin($plugin_id)) {
                    $installed++;
                }
            }
        } else {
            if($pluginInstaller->installPlugin($plugin_ids)) {
                $installed++;
            }
        }

        $install_msg = ($installed ? $installed . i18n_r("gs_plugin_installer/INSTALLED_SUCCESS") : i18n_r("gs_plugin_installer/INSTALLED_FAIL"));

        ?>
        <script>
            $(function () {
                $('div.bodycontent').before('<div class="<?php echo ($installed ? 'updated' : 'error'); ?>" style="display:block;">' + "<?php echo $install_msg ?>" + '</div>');
                $('.updated, .error').fadeOut(300).fadeIn(500);
            });
        </script>

    <?php
    }

    if (isset($_GET["uninstall"])) {

        $plugin_ids = $_GET["plugins"];
        $uninstalled = 0;

        if (is_array($plugin_ids)) {
            foreach($plugin_ids as $plugin_id) {
                if($pluginInstaller->uninstallPlugin($plugin_id)) {
                    $uninstalled++;
                }
            }
        } else {
            if($pluginInstaller->uninstallPlugin($plugin_ids)) {
                $uninstalled++;
            }
        }

        $uninstall_msg = ($uninstalled ? $uninstalled . i18n_r("gs_plugin_installer/UNINSTALLED_SUCCESS") : i18n_r("gs_plugin_installer/UNINSTALLED_FAIL"));

        ?>
        <script>
            $(function () {
                $('div.bodycontent').before('<div class="<?php echo ($uninstalled ? 'updated' : 'error'); ?>" style="display:block;">' + "<?php echo $uninstall_msg ?>" + '</div>');
                $('.updated, .error').fadeOut(300).fadeIn(500);
            });
        </script>
    <?php

    }

    $plugins = $pluginInstaller->getPlugins();

    ?>

    <form id="gs_plugin_form" action="load.php" method="GET">
        <input type="hidden" name="id" value="gs_plugin_installer">
        <h3 class="floated"><?php i18n("gs_plugin_installer/PLUGIN_NAME"); ?></h3>
        <div class="edit-nav clearfix">
            <a href="load.php?id=gs_plugin_installer&update"><?php i18n("gs_plugin_installer/REFRESH"); ?></a>
            <button id="install" type="submit" name="install" value="1"><?php i18n("gs_plugin_installer/INSTALL"); ?></button>
            <button id="uninstall" type="submit" name="uninstall" value="1"><?php i18n("gs_plugin_installer/UNINSTALL"); ?></button>
        </div>

        <table id="plugin_table" class="highlight">
            <thead>
            <tr>
                <th><?php i18n("gs_plugin_installer/LIST_UPDATED"); ?></th>
                <th><?php i18n("gs_plugin_installer/LIST_PLUGIN"); ?></th>
                <th><?php i18n("gs_plugin_installer/LIST_DESCRIPTION"); ?></th>
                <th><?php i18n("gs_plugin_installer/LIST_INSTALL"); ?></th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($plugins as $index => $plugin): ?>
                <tr id="tr-<?php echo $index ?>">
                    <td><?php $plugin->updated_date ?></td>
                    <td>
                        <a href="<?php echo $plugin->path?>" target="_blank">
                            <b><?php echo $plugin->name ?></b>
                        </a>
                    </td>
                    <td>
                        <div class="description">
                            <?php echo trim(strip_tags(nl2br($plugin->description), "<br><br/>")) ?>
                        </div>
                        <b><?php i18n("gs_plugin_installer/VERSION"); ?> <?php echo $plugin->version ?></b> — <?php i18n("gs_plugin_installer/AUTHOR"); ?>:
                        <a href="<?php echo $plugin->author_url ?>" target="_blank"><?php echo $plugin->owner ?></a>
                    </td>
                    <td>
                        <?php if ($pluginInstaller->isPluginInstalled($plugin)): ?>
                            <a class="cancel" href="load.php?id=gs_plugin_installer&uninstall=1&plugins=<?php echo $plugin->id ?>">Uninstall</a>
                        <?php else: ?>
                            <a href="load.php?id=gs_plugin_installer&install=1&plugins=<?php echo $plugin->id ?>"><?php i18n("gs_plugin_installer/INSTALL"); ?></a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <input name="plugins[]" type="checkbox" value="<?php echo $plugin->id ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </form>
<?php

}


?>