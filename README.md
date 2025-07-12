# Bookface Custom

## Description

This is an optional Friendica Addon that makes it easy for users to customize the Bookface scheme for the Friendica "Frio" theme.

Bookface uses a number of CSS variables to set fonts, colors, and text labels for pseudo elements. This add-on allows you to override any or all of them and save those customizations to the server with your account preferences. This way they will be available regardless of what browser or device you use to access your Frindica account.

## System Requirements & Installation

This addon has been tested with Friendica **2024.12** and **2025.7-rc**
It is intended for use with **Bookface 1.8** but will also work, to some extent, all the way back to **version 1.4** but only for whatever CSS variables the older version included.

1. To install place the "bookface_custom" folder in your Friendica _/addon_ subfolder and make sure it has the right file permissions.
2. Go to **Main Menu -> Admin -> Addons** and tick the checkbox next to "Bookface Custom" in the list. There are no Admin settings for this addon.

## Using This Addon

1. If it isn't already go to **Main Menu -> Settings -> Display -> Themes** and select "Frio" from the drop-down list.
2. If it isn't already go to **Main Menu -> Settings -> Display -> Theme Custumization** and select either "Bookface Light" or "Bookface Dark" from the drop-down list.
3. If the admin for your Friendica instance has enabled this addon it will appear under **Main Menu -> Addons -> Bookface customizations**
4. Change whichever entries you want to customize.
5. IMPORTANT! Tick the "Enable Customizations" checkbox near the top of the settings or nothing will be applied.
6. Press the "Save Settings" button.

Your changes should be immediately applied when the settings page reloads.

## Cautions

* The text fields do not perform any validation of what you enter. If you enter invalid parameters they either simply won't work or they may mess up the layout.
* There is nothing to stop you from making color combinations that result in an unusable interface. With great power comes great responsibility!
* This addon does work with "Bookface Auto" if you're only changing the font or labels, it does not work so well for colors because the auto stylesheet will pick Light Mode or Dark Mode depending on the device settings.

## Changelog
1.0 (12 July 2025)
* Initial Release for Friendica 'Interrupted Fern' 2024.12 and the 2025.7 Release Candidate

## Authors and acknowledgment
Random Penguin <https://gitlab.com/randompenguin>

## License
AGPL

## Project status
Unsupported by Friendica devs.
