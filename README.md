# GS Plugin Installer
## A Plugin Installer for GetSimple CMS 3.3.x


![Screenshot](/assets/screenshot.png)


### About GS Plugin Installer

GS Plugin Installer is a plugin installer created for the GetSimple CMS, versions 3.3.x, It has not been tested on versions older than 3.3.5, but I don't think the plugin architecture has changed drastically in the 3.3.x releases, if anyone more knowledable of Plugin Development in GS can confirm this please let me know.


The plugin works by querying the Extend API url ```http://get-simple.info/api/extend/all.php``` which to my knowlege is mostly undocumented, it will then cache the result into a JSON file, I have chosen to do it this way because if you were to query the api on every request, it will be extremely slow, since there is no way to "paginate" the response from the api.


The cache will by default be refreshed every 24 hours, but by clicking the "Refresh list" button will force the cache to refresh, __NOTE__ that the Extend API is cached and will only update every 4 hours according to the [official documentation](http://get-simple.info/wiki/plugins:extend_api#extend_api_limitations).


The jQuery plugin [DataTables](http://datatables.net/) is used to display, search and sort the plugin list, this enables you to quickly find the plugin you are looking for and install or uninstall it.


## Install the plugin

[Download here](http://get-simple.info/extend/plugin/gs-plugin-installer/955/)

```
1. Download the plugin zip file.
2. Unzip it into /plugins
3. Activate it in the "Plugins" tab in your GetSimple CMS admin area.
4. Done
```


### Features

- Installing one or multiple plugins
- Uninstalling one or multiple plugins
- Order/Sort plugins by name, author and whether or not its installed or not.
- Search for plugins by name, author and it's installation status


### Planned Features and TODOs

- Add way to active a plugin from the plugin installer, this can only be done from the native "Installed Plugins" area for now.
- Improve the caching system.
- Refactor the script to be more object oriented and structured.
- Add more flexibility through plugin settings so users can control the behaviour of the script and caching functionality.
- "Last Updated" ordering


### Reporting bugs

Please report bugs in the support thread [here](http://get-simple.info/forums/showthread.php?tid=7370) or create a [GitHub Issue](https://github.com/HelgeSverre/gs-plugin-installer/issues).