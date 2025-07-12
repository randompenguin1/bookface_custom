<?php
/**
 * Name: Bookface Customize
 * Description: Adds a stylesheet to the footer with CSS variable customizations.
 * Version: 1.0
 * Author: Random Penguin <https://gitlab.com/randompenguin>
 */

use Friendica\App;
use Friendica\AppHelper;
use Friendica\Core\Hook;
use Friendica\Core\Renderer;
use Friendica\DI;
use Friendica\Util\XML;

function bookface_custom_install()
{
	Hook::register('addon_settings'	   , __FILE__, 'bookface_custom_addon_settings');
	Hook::register('addon_settings_post', __FILE__, 'bookface_custom_addon_settings_post');
	Hook::register('footer'			   , __FILE__, 'bookface_custom_footer');
}

/**
 * Form for configuring custom settings for a user
 *
 * @param array $data Hook data array
 * @throws \Friendica\Network\HTTPExcepton\ServiceUnavialableException
 */
function bookface_custom_addon_settings(array &$data)
{
	if (!DI::userSession()->getLocalUserId()) {
		return;
	}
	
	if ( method_exists(DI::class, 'app') ){
		$current_theme = DI::app()->getCurrentTheme();
	} else if ( method_exists(DI::class, 'appHelper')) {
		$current_theme = DI::appHelper()->getCurrentTheme();
	} else {
		$current_theme = '';
	}	
	// If current theme is not Frio bail...
	if ($current_theme != 'frio'){
		return;
	}
	
	$uid = DI::userSession()->getLocalUserId();
	
	$enabled = DI::pConfig()->get($uid, 'bookface_custom', 'enabled');

	$global_font = DI::pConfig()->get($uid, 'bookface_custom', 'global_font');
	$nav_bg = DI::pConfig()->get($uid, 'bookface_custom', 'nav_bg');
	$link_color = DI::pConfig()->get($uid, 'bookface_custom', 'link_color');
	$nav_icon_color = DI::pConfig()->get($uid, 'bookface_custom', 'nav_icon_color');
	$background_color = DI::pConfig()->get($uid, 'bookface_custom', 'background_color');
	$content_bg = DI::pConfig()->get($uid, 'bookface_custom', 'content_bg');
	$comment_bg = DI::pConfig()->get($uid, 'bookface_custom', 'comment_bg');
	$font_color = DI::pConfig()->get($uid, 'bookface_custom', 'font_color');
	$font_color_lighter = DI::pConfig()->get($uid, 'bookface_custom', 'font_color_lighter');
	$font_color_darker  = DI::pConfig()->get($uid, 'bookface_custom', 'font_color_darker');
	$menu_background_hover_color = DI::pConfig()->get($uid, 'bookface_custom', 'menu_background_hover_color');
	$border_color = DI::pConfig()->get($uid, 'bookface_custom', 'border_color');
	$count_color = DI::pConfig()->get($uid, 'bookface_custom', 'count_color');
	$count_bg = DI::pConfig()->get($uid, 'bookface_custom', 'count_bg');
	$shadowglow = DI::pConfig()->get($uid, 'bookface_custom', 'shadowglow');
	$dimbright  = DI::pConfig()->get($uid, 'bookface_custom', 'dimbright');
	
	$attach_file_button = DI::pConfig()->get($uid, 'bookface_custom', 'attach_file_button');
	$show_tooltips = DI::pConfig()->get($uid, 'bookface_custom', 'show_tooltips');
	$show_navbar_labels = DI::pConfig()->get($uid, 'bookface_custom', 'show_navbar_labels');

	$navbar_network_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_network_text');
	$navbar_profile_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_profile_text');
	$navbar_community_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_community_text');
	$navbar_messages_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_messages_text');
	$navbar_calendar_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_calendar_text');
	$navbar_contact_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_contact_text');
	$navbar_notices_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_notices_text');
	$sign_in_text = DI::pConfig()->get($uid, 'bookface_custom', 'sign_in_text');
	$compose_text = DI::pConfig()->get($uid, 'bookface_custom', 'compose_text');
	$new_note_text = DI::pConfig()->get($uid, 'bookface_custom', 'new_note_text');
	$save_search_text = DI::pConfig()->get($uid, 'bookface_custom', 'save_search_text');
	$follow_tag_text = DI::pConfig()->get($uid, 'bookface_custom', 'follow_tag_text');
	$comment_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'comment_button_text');
	$share_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'share_button_text');
	$quote_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'quote_button_text');
	$like_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'like_button_text');
	$dislike_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'dislike_button_text');
	$more_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'more_button_text');
	$attendyes_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendyes_button_text');
	$attendno_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendno_button_text');
	$attendmaybe_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendmaybe_button_text');
	$add_photo_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'add_photo_button_text');
	$follow_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'follow_button_text');
	$save_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'save_button_text');
	$new_message_text = DI::pConfig()->get($uid, 'bookface_custom', 'new_message_text');
	$calendar_today_text = DI::pConfig()->get($uid, 'bookface_custom', 'calendar_today_text');

	$template = Renderer::getMarkupTemplate('settings.tpl', 'addon/bookface_custom/');
	$html     = Renderer::replaceMacros($template, [
		'$description' => DI::l10n()->t('Customize colors and button labels in the Bookface scheme for the Frio theme. You can enter a color by name, hex, rgb, rgba, but no gradients or images. Only items with entries will be included in the stylesheet override.'),
		'$version'     => 'Bookface Version 1.8',
		'$disclaimer'  => DI::l10n()->t('WARNING: There is nothing to prevent you from making color combinations that result in an unusable interface!'),
		'$enabled' => [
			'enabled',
			DI::l10n()->t('Enable Customizations'),
			$enabled
		],
		'$section_head_fonts_colors' => DI::l10n()->t('Fonts and Colors'),
		'$global_font' => [
			'global_font',
			DI::l10n()->t('Global Font'),
			$global_font,
			DI::l10n()->t('make sure to define fallbacks in case preferred font is not available')
		],
		'$nav_bg' => [
			'nav_bg',
			DI::l10n()->t('Nav Background Color'),
			$nav_bg
		],
		'$link_color' => [
			'link_color',
			DI::l10n()->t('Link Color'),
			$link_color
		],
		'$font_color' => [
			'font_color',
			DI::l10n()->t('Font Color'),
			$font_color
		],
		'$font_color_lighter' => [
			'font_color_lighter',
			DI::l10n()->t('Font Lighter Color'),
			$font_color_lighter
		],
		'$font_color_darker' => [
			'font_color_darker',
			DI::l10n()->t('Font Darker Color'),
			$font_color_darker
		],
		'$nav_icon_color' => [
			'nav_icon_color',
			DI::l10n()->t('Nav Icon Color'),
			$nav_icon_color
		],
		'$background_color' => [
			'background_color',
			DI::l10n()->t('Background Color'),
			$background_color
		],
		'$content_bg' => [
			'content_bg',
			DI::l10n()->t('Content Background Color'),
			$content_bg
		],
		'$comment_bg' => [
			'comment_bg',
			DI::l10n()->t('Comment Background Color'),
			$comment_bg
		],
		'$menu_background_hover_color' => [
			'menu_background_hover_color',
			DI::l10n()->t('Menu Background Hover Color'),
			$menu_background_hover_color
		],
		'$border_color' => [
			'border_color',
			DI::l10n()->t('Border Color'),
			$border_color
		],
		'$count_color' => [
			'count_color',
			DI::l10n()->t('Engagement Count Text Color'),
			$count_color
		],
		'$count_bg' => [
			'count_bg',
			DI::l10n()->t('Engagement Count Background Color'),
			$count_bg
		],
		'$section_head_features' => DI::l10n()->t('Feature Options'),
		'$shadowglow' => [
			'shadowglow',
			DI::l10n()->t('Drop Shadow/Outer Glow Effect'),
			$shadowglow
		],
		'$dimbright' => [
			'dimbright',
			DI::l10n()->t('Button Rollover Brighter/Dimmer'),
			$dimbright
		],
		'$attach_file_button' => [
			'attach_file_button',
			DI::l10n()->t('Show/Hide File Attachment Button'),
			$attach_file_button,
			DI::l10n()->t('"block" to show, "none" to hide')
		],
		'$show_tooltips' => [
			'show_tooltips',
			DI::l10n()->t('Show/Hide Bootstrap Tooltip Balloons'),
			$show_tooltips,
			DI::l10n()->t('"block" to show, "none" to hide')
		],
		'$show_navbar_labels' => [
			'show_navbar_labels',
			DI::l10n()->t('Show/Hide Navbar Button Labels'),
			$show_navbar_labels,
			DI::l10n()->t('"block" to show, "none" to hide')
		],
		'$section_head_navbar_labels' => DI::l10n()->t('Navbar Button Labels'),
		'$navbar_network_text' => [
			'navbar_network_text',
			DI::l10n()->t('Network Button Label'),
			$navbar_network_text
		],
		'$navbar_profile_text' => [
			'navbar_profile_text',
			DI::l10n()->t('Profile/Home Button Label'),
			$navbar_profile_text
		],
		'$navbar_community_text' => [
			'navbar_community_text',
			DI::l10n()->t('Community Button Label'),
			$navbar_community_text
		],
		'$navbar_messages_text' => [
			'navbar_messages_text',
			DI::l10n()->t('Messages Button Label'),
			$navbar_messages_text
		],
		'$navbar_calendar_text' => [
			'navbar_calendar_text',
			DI::l10n()->t('Calendar Button Label'),
			$navbar_calendar_text
		],
		'$navbar_contact_text' => [
			'navbar_contact_text',
			DI::l10n()->t('Contacts Button Label'),
			$navbar_contact_text
		],
		'$navbar_notices_text' => [
			'navbar_notices_text',
			DI::l10n()->t('Notifications Button Label'),
			$navbar_notices_text
		],
		'$section_head_labels' => DI::l10n()->t('Label Text'),
		'$sign_in_text' => [
			'sign_in_text',
			DI::l10n()->t('Sign-In Button Label'),
			$sign_in_text
		],
		'$compose_text' => [
			'compose_text',
			DI::l10n()->t('Compose Button Label'),
			$compose_text
		],
		'$new_note_text' => [
			'new_note_text',
			DI::l10n()->t('New Note Button Label'),
			$new_note_text
		],
		'$save_search_text' => [
			'save_search_text',
			DI::l10n()->t('Save Search Button Label'),
			$save_search_text
		],
		'$follow_tag_text' => [
			'follow_tag_text',
			DI::l10n()->t('Follow Tag Button Label'),
			$follow_tag_text
		],
		'$comment_button_text' => [
			'comment_button_text',
			DI::l10n()->t('Comment Button Label'),
			$comment_button_text
		],
		'$share_button_text' => [
			'share_button_text',
			DI::l10n()->t('Share Button Label'),
			$share_button_text
		],
		'$quote_button_text' => [
			'quote_button_text',
			DI::l10n()->t('Quote Button Label'),
			$quote_button_text
		],
		'$like_button_text' => [
			'like_button_text',
			DI::l10n()->t('Like Button Label'),
			$like_button_text
		],
		'$dislike_button_text' => [
			'dislike_button_text',
			DI::l10n()->t('Dislike Button Label'),
			$dislike_button_text
		],
		'$more_button_text' => [
			'more_button_text',
			DI::l10n()->t('More Button Label'),
			$more_button_text
		],
		'$attendyes_button_text' => [
			'attendyes_button_text',
			DI::l10n()->t('Attend Yes Button Label'),
			$attendyes_button_text
		],
		'$attendno_button_text' => [
			'attendno_button_text',
			DI::l10n()->t('Attend No Button Label'),
			$attendno_button_text
		],
		'$attendmaybe_button_text' => [
			'attendmaybe_button_text',
			DI::l10n()->t('Attend Maybe Button Label'),
			$attendmaybe_button_text
		],
		'$add_photo_button_text' => [
			'add_photo_button_text',
			DI::l10n()->t('Add Photo Button Label'),
			$add_photo_button_text
		],
		'$follow_button_text' => [
			'follow_button_text',
			DI::l10n()->t('Follow Button Label'),
			$follow_button_text
		],
		'$save_button_text' => [
			'save_button_text',
			DI::l10n()->t('Save Button Label'),
			$save_button_text
		],
		'$new_message_text' => [
			'new_message_text',
			DI::l10n()->t('New Message Button Label'),
			$new_message_text
		],
		'$calendar_today_text' => [
			'calendar_today_text',
			DI::l10n()->t('Calendar "Today" Label'),
			$calendar_today_text,
			DI::l10n()->t('Replaces target icon with text above')
		],
	]);

	$data = [
		'addon' => 'bookface_custom',
		'title' => DI::l10n()->t('Bookface Customizations'),
		'html'  => $html,
	];
}

/**
 * Process data submitted to user's Bookface features form
 * @param array			$post POST data
 * @param void
 */
function bookface_custom_addon_settings_post(array $post)
{
	if (!DI::userSession()->getLocalUserId() || empty($post['bookface_custom-submit'])){
		return;
	}
	$uid = DI::userSession()->getLocalUserId();
	if ($post['enabled']){
		DI::pConfig()->set($uid, 'bookface_custom', 'enabled', intval($post['enabled']));
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'enabled');
	}
	if ($post['global_font'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'global_font', $post['global_font']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'global_font');
	}

	if ($post['nav_bg'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'nav_bg', $post['nav_bg']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'nav_bg');
	}
	
	if ($post['link_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'link_color', $post['link_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'link_color');
	}
	
	if ($post['font_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'font_color', $post['font_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'font_color');
	}
	
	if ($post['font_color_lighter'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'font_color_lighter', $post['font_color_lighter']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'font_color_lighter');
	}
	
	if ($post['font_color_darker'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'font_color_darker', $post['font_color_darker']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'font_color_darker');
	}
	
	if ($post['nav_icon_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'nav_icon_color', $post['nav_icon_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'nav_icon_color');
	}

	if ($post['background_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'background_color', $post['background_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'background_color');
	}
	
	if ($post['content_bg'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'content_bg', $post['content_bg']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'content_bg');
	}
	
	if ($post['comment_bg'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'comment_bg', $post['comment_bg']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'comment_bg');
	}

	if ($post['menu_background_hover_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'menu_background_hover_color', $post['menu_background_hover_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'menu_background_hover_color');
	}

	if ($post['border_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'border_color', $post['border_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'border_color');
	}

	if ($post['count_color'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'count_color', $post['count_color']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'count_color');
	}

	if ($post['count_bg'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'count_bg', $post['count_bg']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'count_bg');
	}
	
	if ($post['shadowglow'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'shadowglow', $post['shadowglow']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'shadowglow');
	}
	
	if ($post['dimbright'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'dimbright', $post['dimbright']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'dimbright');
	}

	if ($post['attach_file_button'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'attach_file_button', $post['attach_file_button']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'attach_file_button');
	}
	
	if ($post['show_tooltips'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'show_tooltips', $post['show_tooltips']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'show_tooltips');
	}
	
	if ($post['show_navbar_labels'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'show_navbar_labels', $post['show_navbar_labels']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'show_navbar_labels');
	}
	
	if ($post['navbar_network_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_network_text', $post['navbar_network_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_network_text');
	}
	
	if ($post['navbar_profile_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_profile_text', $post['navbar_profile_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_profile_text');
	}
	
	if ($post['navbar_community_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_community_text', $post['navbar_community_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_community_text');
	}
	
	if ($post['navbar_messages_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_messages_text', $post['navbar_messages_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_messages_text');
	}
	
	if ($post['navbar_calendar_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_calendar_text', $post['navbar_calendar_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_calendar_text');
	}
	
	if ($post['navbar_contact_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_contact_text', $post['navbar_contact_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_contact_text');
	}
	
	if ($post['navbar_notices_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'navbar_notices_text', $post['navbar_notices_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'navbar_notices_text');
	}
	
	if ($post['sign_in_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'sign_in_text', $post['sign_in_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'sign_in_text');
	}

	if ($post['compose_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'compose_text', $post['compose_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'compose_text');
	}

	if ($post['new_note_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'new_note_text', $post['new_note_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'new_note_text');
	}

	if ($post['save_search_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'save_search_text', $post['save_search_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'save_search_text');
	}

	if ($post['follow_tag_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'follow_tag_text', $post['follow_tag_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'follow_tag_text');
	}
	
	if ($post['comment_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'comment_button_text', $post['comment_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'comment_button_text');
	}
	
	if ($post['share_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'share_button_text', $post['share_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'share_button_text');
	}

	if ($post['quote_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'quote_button_text', $post['quote_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'quote_button_text');
	}
	
	if ($post['like_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'like_button_text', $post['like_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'like_button_text');
	}
	
	if ($post['dislike_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'dislike_button_text', $post['dislike_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'dislike_button_text');
	}

	if ($post['more_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'more_button_text', $post['more_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'more_button_text');
	}

	if ($post['attendyes_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'attendyes_button_text', $post['attendyes_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'attendyes_button_text');
	}
	
	if ($post['attendno_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'attendno_button_text', $post['attendno_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'attendno_button_text');
	}	
	
	if ($post['attendmaybe_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'attendmaybe_button_text', $post['attendmaybe_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'attendmaybe_button_text');
	}

	if ($post['add_photo_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'add_photo_button_text', $post['add_photo_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'add_photo_button_text');
	}

	if ($post['follow_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'follow_button_text', $post['follow_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'follow_button_text');
	}

	if ($post['save_button_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'save_button_text', $post['save_button_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'save_button_text');
	}
	
	if ($post['new_message_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'new_message_text', $post['new_message_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'new_message_text');
	}
	
	if ($post['calendar_today_text'] != ""){
		DI::pConfig()->set($uid, 'bookface_custom', 'calendar_today_text', $post['calendar_today_text']);
	} else {
		DI::pConfig()->delete($uid, 'bookface_custom', 'calendar_today_text');
	}
	
}

function bookface_custom_footer(string &$body)
{
	if (!DI::userSession()->getLocalUserId()) {
		return;
	}
	$uid = DI::userSession()->getLocalUserId();

	// If current theme is not Frio bail...
	if ( method_exists(DI::class, 'app') ){
		$current_theme = DI::app()->getCurrentTheme();
	} else if ( method_exists(DI::class, 'appHelper') ) {
		$current_theme = DI::appHelper()->getCurrentTheme();
	} else {
		$current_theme = '';
	}	
	// If current theme is not Frio bail...
	if ($current_theme != 'frio'){
		return;
	}
	
	// If not enabled bail...
	if (!DI::pConfig()->get($uid, 'bookface_custom', 'enabled')){
		return;
	}
	
	// if we got here user has saved custom settings so go get them...
	$global_font = DI::pConfig()->get($uid, 'bookface_custom', 'global_font');
	$nav_bg = DI::pConfig()->get($uid, 'bookface_custom', 'nav_bg');
	$link_color = DI::pConfig()->get($uid, 'bookface_custom', 'link_color');
	$font_color = DI::pConfig()->get($uid, 'bookface_custom', 'font_color');
	$font_color_lighter = DI::pConfig()->get($uid, 'bookface_custom', 'font_color_lighter');
	$font_color_darker  = DI::pConfig()->get($uid, 'bookface_custom', 'font_color_darker');
	$nav_icon_color = DI::pConfig()->get($uid, 'bookface_custom', 'nav_icon_color');
	$background_color = DI::pConfig()->get($uid, 'bookface_custom', 'background_color');
	$content_bg = DI::pConfig()->get($uid, 'bookface_custom', 'content_bg');
	$comment_bg = DI::pConfig()->get($uid, 'bookface_custom', 'comment_bg');
	$menu_background_hover_color = DI::pConfig()->get($uid, 'bookface_custom', 'menu_background_hover_color');
	$border_color = DI::pConfig()->get($uid, 'bookface_custom', 'border_color');
	$count_color = DI::pConfig()->get($uid, 'bookface_custom', 'count_color');
	$count_bg = DI::pConfig()->get($uid, 'bookface_custom', 'count_bg');
	$shadowglow = DI::pConfig()->get($uid, 'bookface_custom', 'shadowglow');
	$dimbright  = DI::pConfig()->get($uid, 'bookface_custom', 'dimbright');
	
	$attach_file_button = DI::pConfig()->get($uid, 'bookface_custom', 'attach_file_button');
	$show_tooltips = DI::pConfig()->get($uid, 'bookface_custom', 'show_tooltips');
	$show_navbar_labels = DI::pConfig()->get($uid, 'bookface_custom', 'show_navbar_labels');

	$navbar_network_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_network_text');
	$navbar_profile_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_profile_text');
	$navbar_community_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_community_text');
	$navbar_messages_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_messages_text');
	$navbar_calendar_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_calendar_text');
	$navbar_contact_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_contact_text');
	$navbar_notices_text = DI::pConfig()->get($uid, 'bookface_custom', 'navbar_notices_text');	
	$sign_in_text = DI::pConfig()->get($uid, 'bookface_custom', 'sign_in_text');
	$compose_text = DI::pConfig()->get($uid, 'bookface_custom', 'compose_text');
	$new_note_text = DI::pConfig()->get($uid, 'bookface_custom', 'new_note_text');
	$save_search_text = DI::pConfig()->get($uid, 'bookface_custom', 'save_search_text');
	$follow_tag_text = DI::pConfig()->get($uid, 'bookface_custom', 'follow_tag_text');
	$comment_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'comment_button_text');
	$share_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'share_button_text');
	$quote_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'quote_button_text');
	$like_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'like_button_text');
	$dislike_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'dislike_button_text');
	$more_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'more_button_text');
	$attendyes_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendyes_button_text');
	$attendno_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendno_button_text');
	$attendmaybe_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'attendmaybe_button_text');
	$add_photo_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'add_photo_button_text');
	$follow_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'follow_button_text');
	$save_button_text = DI::pConfig()->get($uid, 'bookface_custom', 'save_button_text');
	$new_message_text = DI::pConfig()->get($uid, 'bookface_custom', 'new_message_text');
	$calendar_today_text = DI::pConfig()->get($uid, 'bookface_custom', 'calendar_today_text');
	
	// now build the custom stylesheet overrides...
	$html = '<style type="text/css">:root{';
	
	if (!empty($global_font)){$html .= '--global-font-family: '.$global_font.';';};
	if (!empty($nav_bg)) { 	  $html .= '--nav-bg: '.$nav_bg.';';};
	if (!empty($link_color)){ $html .= '--link-color: '.$link_color.';';};
	if (!empty($nav_icon_color)) {  $html .= '--nav-icon-color: '.$nav_icon_color.';';};
	if (!empty($background_color)){ $html .= '--background-color: '.$background_color.';';};
	if (!empty($content_bg)){ $html .= '--content-bg: '.$content_bg.';';}; 
	if (!empty($comment_bg)){ $html .= '--comment-bg: '.$comment_bg.';';};
	if (!empty($font_color)){ $html .= '--font-color: '.$font_color.';';};
	if (!empty($font_color_lighter)){ $html .= '--font-color-lighter: '.$font_color_lighter.';';};
	if (!empty($font_color_darker)){  $html .= '--font-color-darker: '.$font_color_darker.';';};
	if (!empty($menu_background_hover_color)){ $html .= '--menu-background-hover-color: '.$menu_background_hover_color.';';};
	if (!empty($border_color)){ $html .= '--border-color: '.$border_color.';';};
	if (!empty($count_color)){  $html .= '--count-color: '.$count_color.';';};
	if (!empty($count_bg)){   $html .= '--count-bg: '.$count_bg.';';};
	if (!empty($shadowglow)){ $html .= '--shadowglow: '.$shadowglow.';';};
	if (!empty($dimbright)){  $html .= '--dimbright: '.$dimbright.';';};
	
	if (!empty($attach_file_button)){ $html .= '--attach-file-button: '.$attach_file_button.';';}; 
	if (!empty($show_tooltips)){ $html .= '--show-tooltips: '.$show_tooltips.';';};
	if (!empty($show_navbar_labels)){ $html .= '--show-navbar-labels: '.$show_navbar_labels.';';};
	
	if (!empty($navbar_network_text)){ $html .= '--navbar-network-text: \''.$navbar_network_text.'\';';};
	if (!empty($navbar_profile_text)){ $html .= '--navbar-profile-text: \''.$navbar_profile_text.'\';';};
	if (!empty($navbar_community_text)){ $html .= '--navbar-community-text: \''.$navbar_community_text.'\';';};
	if (!empty($navbar_messages_text)){ $html .= '--navbar-messages-text: \''.$navbar_messages_text.'\';';};
	if (!empty($navbar_calendar_text)){ $html .= '--navbar-calendar-text: \''.$navbar_calendar_text.'\';';};
	if (!empty($navbar_contact_text)){ $html .= '--navbar-contact-text: \''.$navbar_contact_text.'\';';};
	if (!empty($navbar_notices_text)){ $html .= '--navbar-notices-text: \''.$navbar_notices_text.'\';';};
	
	if (!empty($sign_in_text)){ $html .= '--sign-in-text: \''.$sign_in_text.'\';';}; 
	if (!empty($compose_text)){ $html .= '--compose-text: \''.$compose_text.'\';';}; 
	if (!empty($new_note_text)){$html .= '--new-note-text: \''.$new_note_text.'\';';}; 
	if (!empty($save_search_text)){ $html .= '--save-search-text: \''.$save_search_text.'\';';}; 
	if (!empty($follow_tag_text)){  $html .= '--follow-tag-text: \''.$follow_tag_text.'\';';}; 
	if (!empty($comment_button_text)){ $html .= '--comment-button-text: \''.$comment_button_text.'\';';};
	if (!empty($share_button_text)){   $html .= '--share-button-text: \''.$share_button_text.'\';';}; 
	if (!empty($quote_button_text)){   $html .= '--quote-button-text: \''.$quote_button_text.'\';';}; 
	if (!empty($like_button_text)){    $html .= '--like-button-text: \''.$like_button_text.'\';';}; 
	if (!empty($dislike_button_text)){ $html .= '--dislike-button-text: \''.$dislike_button_text.'\';';}; 
	if (!empty($more_button_text)){    $html .= '--more-button-text: \''.$more_button_text.'\';';};
	if (!empty($attendyes_button_text)){   $html .= '--attendyes-button-text: \''.$attendyes_button_text.'\';';}; 
	if (!empty($attendno_button_text)) {   $html .= '--attendno-button-text: \''.$attendno_button_text.'\';';}; 
	if (!empty($attendmaybe_button_text)){ $html .= '--attendmaybe-button-text: \''.$attendmaybe_button_text.'\';';}; 
	if (!empty($add_photo_button_text)){   $html .= '--add-photo-button-text: \''.$add_photo_button_text.'\';';}; 
	if (!empty($follow_button_text)){      $html .= '--follow-button-text: \''.$follow_button_text.'\';';}; 
	if (!empty($save_button_text)){ $html .= '--save-button-text: \''.$save_button_text.'\';';};
	if (!empty($new_message_text)){ $html .= '--new-message-text: \''.$new_message_text.'\';';};  
	if (!empty($calendar_today_text)){ $html .= '--calendar-today-text: \''.$calendar_today_text.'\';';};	
	$html .= '}</style>';
	
	$body .= $html;
}