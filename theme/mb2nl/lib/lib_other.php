<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2019 Mariusz Boloz (www.mb2themes.com)
 * @license   Commercial https://themeforest.net/licenses
 *
 */



defined('MOODLE_INTERNAL') || die();


/*
 *
 * Method to set body class
 *
 *
 */
function theme_mb2nl_body_cls($page)
{

	$output = array();


	// Page layout
	$output[] = 'theme-l' . theme_mb2nl_theme_setting($page, 'layout', 'fw');


	// Header style
	$output[] = 'header-' . theme_mb2nl_theme_setting($page, 'headerstyle', 'light');


	// Icon nav menu class
	if (theme_mb2nl_theme_setting($page, 'naviconurl1') !='')
	{
		$output[] = 'isiconmenu';
	}


	// Custom login page
	if (theme_mb2nl_is_login($page, true))
	{
		$output[] = 'custom-login';
	}


	// User logged in or logged out (not guest)
	if ((isloggedin() && !isguestuser()))
	{
		$output[] = 'loggedin';
	}


	// Check if is guest user
	if (isguestuser())
	{
		$output[] = 'isguestuser';
	}


	// Custom page classess
	if (theme_mb2nl_page_cls($page))
	{
		$output[] = theme_mb2nl_page_cls($page);
	}


	// Custom course pages class
	if (theme_mb2nl_page_cls($page, true))
	{
		$output[] = theme_mb2nl_page_cls($page, true);
	}


	// Custom course class
	if (theme_mb2nl_course_cls($page))
	{
		$output[] = theme_mb2nl_course_cls($page);
	}


	// Course category theme
	if (theme_mb2nl_courselist_cls($page))
	{
		$output[] = theme_mb2nl_courselist_cls($page);
	}


	// Theme hidden region mode
	if (isloggedin() && !is_siteadmin())
	{
		$output[] = 'theme-hidden-region-mode';
	}


	// Fixed navigation
	if (theme_mb2nl_theme_setting($page, 'stickynav', 0))
	{
		$output[] = 'sticky-nav';
	}


	// Page predefined background
	if (!theme_mb2nl_is_login($page, true) && theme_mb2nl_theme_setting($page, 'pbgpre') !='')
	{
		$output[] = 'pre-bg' . theme_mb2nl_theme_setting($page, 'pbgpre');
	}


	// Login page predefined background
	if (theme_mb2nl_is_login($page, true) && theme_mb2nl_theme_setting($page, 'loginbgpre') !='')
	{
		$output[] = 'pre-bg' . theme_mb2nl_theme_setting($page, 'loginbgpre');
	}


	if (theme_mb2nl_theme_setting($page,'sidebarbtn') == 2)
	{
		$output[] = 'hide-sidebars';
	}

	if (theme_mb2nl_is_frontpage_empty())
	{
		$output[] = 'fpempty';
	}

	if (theme_mb2nl_course_layout_class())
	{
		$output[] = theme_mb2nl_course_layout_class();
	}

	if (theme_mb2nl_is_sidebars() > 0)
	{
		$output[] = 'sidebar-case';

		if (theme_mb2nl_is_sidebars() == 1)
		{
			$output[] = 'sidebar-one';
		}
		elseif (theme_mb2nl_is_sidebars() == 2)
		{
			$output[] = 'sidebar-two';
		}
	}
	else
	{
		$output[] = 'nosidebar-case';
	}

	$output[] = theme_mb2nl_midentify();


	return $output;


}




/*
 *
 * Method to check if front page is empty
 *
 */
function theme_mb2nl_is_frontpage_empty()
{

	global $CFG, $PAGE;

	if ($PAGE->user_is_editing())
	{
		return false;
	}

	if (!is_dir($CFG->dirroot . '/local/mb2builder'))
	{
		return false;
	}

	if (theme_mb2nl_isblock($PAGE, 'content-top')
	|| theme_mb2nl_isblock($PAGE, 'content-bottom')
	|| theme_mb2nl_isblock($PAGE, 'side-pre')
	|| theme_mb2nl_isblock($PAGE, 'side-post'))
	{
		return false;
	}

	if ((isloggedin() && !isguestuser()))
	{
		if (($CFG->frontpageloggedin === 'none' || $CFG->frontpageloggedin === ''))
		{
			return true;
		}
	}
	else
	{
		if (($CFG->frontpage === 'none' || $CFG->frontpage === ''))
		{
			return true;
		}
	}

	return false;

}



/*
 *
 * Method to check if is login page
 *
 */
function theme_mb2nl_is_login($page, $custom = false)
{

	$output = false;


	$pTypeArr = explode('-', $page->pagetype);
	$isLoginPage = ($pTypeArr[0] === 'login');
	$customLoginPage = theme_mb2nl_theme_setting($page, 'cloginpage', '', 0);


	if ($custom)
	{
		$output = ($isLoginPage && $customLoginPage);
	}
	else
	{
		$output = $isLoginPage;
	}


	return $output;


}










/*
 *
 * Method to get theme scripts
 *
 *
 */
function theme_mb2nl_theme_scripts ($page, $attribs = array())
{

	global $USER,$CFG;


	// Check if Moodle version is 2.9+
	// to use Bootstrap 3 AMD
	$m28 = '2014111012';
	$m29plus = ($CFG->version > $m28);


	// jQuery framework
	$page->requires->jquery();
	//$page->requires->jquery_plugin('ui');


	// Bootstrap 3
	!$m29plus ? $page->requires->js('/theme/mb2nl/assets/bootstrap/bootstrap.min.js',true) : '';


	// Sf menu script
	$page->requires->js('/theme/mb2nl/assets/superfish/superfish.custom.js');


	// OWL carousel
	$page->requires->css('/theme/mb2nl/assets/OwlCarousel/assets/owl.carousel.min.css');
	$page->requires->js('/theme/mb2nl/assets/OwlCarousel/owl.carousel.min.js');


	// Nivo-Lightbox
	//$page->requires->css('/theme/mb2nl/assets/Nivo-Lightbox/nivo-lightbox.min.css');
	//$page->requires->js('/theme/mb2nl/assets/Nivo-Lightbox/nivo-lightbox.min.js');

	// https://github.com/js-cookie/js-cookie
	$page->requires->js('/theme/mb2nl/assets/js.cookie.js');


	// Spectrum color
	if (is_siteadmin())
	{
		$page->requires->css('/theme/mb2nl/assets/spectrum/spectrum.min.css');
		$page->requires->js('/theme/mb2nl/assets/spectrum/spectrum.min.js');
	}


	// Theme scripts
	if ($m29plus)
	{
		$page->requires->js('/theme/mb2nl/javascript/theme-amd.js');
	}
	else
	{
		$page->requires->js('/theme/mb2nl/javascript/theme-no-amd.js');
	}


	$page->requires->js('/theme/mb2nl/javascript/theme.js');


	// Youtube api player
	$page->requires->js('/theme/mb2nl/assets/youtube/player_api.js');


	// Custom scripts
	$cssFiles = theme_mb2nl_get_custom_files(1);
	$jsFiles = theme_mb2nl_get_custom_files(2);
	$themename = theme_mb2nl_themename();


	if (count($cssFiles)>0)
	{
		foreach ($cssFiles as $cssF)
		{
			$page->requires->css('/theme/' . $themename . '/style/custom/' . $cssF . '.css');
		}
	}


	if (count($jsFiles)>0)
	{
		foreach ($jsFiles as $jsF)
		{
			$page->requires->js('/theme/' . $themename . '/javascript/custom/' . $jsF . '.js');
		}
	}



}





/*
 *
 * Method to get theme name
 *
 */
function theme_mb2nl_themename ()
{
	global $CFG,$PAGE,$COURSE;

	$name = $CFG->theme;

	if (isset($PAGE->theme->name) && $PAGE->theme->name)
	{
		$name = $PAGE->theme->name;
	}
	elseif (isset($COURSE->theme) && $COURSE->theme)
	{
		$name = $COURSE->theme;
	}

	return $name;

}







/*
 *
 * Method to get social icons list
 *
 *
 */
function theme_mb2nl_social_icons ($page, $attribs = array())
{

	$output = '';
	$linkTarget = theme_mb2nl_theme_setting($page, 'sociallinknw') == 1 ? ' target="_balnk"' : '';


	// Define margin
	$marginArr = explode(',', theme_mb2nl_theme_setting($page, 'socialmargin', ''));
	$marginHeader = (isset($marginArr[0]) && $marginArr[0] !='') ? $marginArr[0] : false;
	$marginFooter = (isset($marginArr[1]) && $marginArr[1] !='') ? $marginArr[1] : false;
	$marginStyle = '';


	if ($attribs['pos'] === 'header' && $marginHeader)
	{
		$marginStyle = ' style="margin:' . $marginHeader . ';"';
	}
	elseif ($attribs['pos'] === 'footer' && $marginFooter)
	{
		$marginStyle = ' style="margin:' . $marginFooter . ';"';
	}


	$output .= '<ul class="social-list"' . $marginStyle . '>';


	for ($i = 1; $i <=10; $i++)
	{

		$socialName = explode(',', theme_mb2nl_theme_setting($page, 'socialname' . $i));
		$socialLink = theme_mb2nl_theme_setting($page, 'sociallink' . $i);


		if (isset($socialName[0]) && $socialName[0] != '')
		{

			$isTt = (isset($attribs['tt']) && $attribs['tt']!='') ? ' data-toggle="tooltip" data-placement="' . $attribs['tt'] . '"' : '';


			$output .= '<li class="li-' . $socialName[0] . '"><a href="' . $socialLink . '" title="' . $socialName[1] . '"' . $linkTarget . $isTt . '><i class="fa fa-' . $socialName[0] . '"></i></a></li>';

		}

	}


	$output .= '</ul>';


	return $output;


}








/*
 *
 * Method to get menu data attributes
 *
 *
 */
function theme_mb2nl_menu_data ($page, $attribs = array())
{

	$output = '';


	$output .= ' data-animtype="' . theme_mb2nl_theme_setting($page, 'navatype', 2) . '"';
	$output .= ' data-animspeed="' . theme_mb2nl_theme_setting($page, 'navaspeed', 300) . '"';


	return $output;


}


/*
 *
 * Method to get custom css and js file
 *
 *
 */
function theme_mb2nl_get_custom_files ($type)
{

	global $CFG;

	$themename = theme_mb2nl_themename();

	$cssDir = $CFG->dirroot . '/theme/' . $themename . '/style/custom/';
	$jsDir = $CFG->dirroot . '/theme/' . $themename . '/javascript/custom/';


	if (is_dir($cssDir) && $type == 1)
	{
		return theme_mb2nl_file_arr($cssDir, array('css'));
	}
	elseif (is_dir($jsDir) && $type == 2)
	{
		return theme_mb2nl_file_arr($jsDir, array('js'));
	}

	return array();

}






/*
 *
 * Method to get files array from directory
 *
 *
 */
function theme_mb2nl_file_arr ($dir, $filter = array('jpg','jpeg','png','gif'))
{


	$output = '';
	$filesArray = array();

	if (!is_dir($dir))
	{

		$output = get_string('foldernoexists','theme_mb2nl');

	}
	else
	{


		$dirContents = scandir($dir);


		foreach ($dirContents as $file)
		{

			$file_type = pathinfo($file, PATHINFO_EXTENSION);

			if (in_array($file_type, $filter))
			{
				$filesArray[] = basename($file, '.' . $file_type);
			}

		}

		$output = $filesArray;

	}


	return $output;


}








/*
 *
 * Method to get random image from array
 *
 *
 */
function theme_mb2nl_random_image ($dir, $pixDirName, $attribs = array('jpg','jpeg','png','gif'))
{

	global $OUTPUT, $CFG;

	$moodle33 = 2017051500;

	$output = '';

	$arr = theme_mb2nl_file_arr($dir, $attribs);


	if (is_array($arr) && !empty($arr))
	{

		$randomImg = array_rand($arr,1);
		$output = $CFG->version >= $moodle33 ? $OUTPUT->image_url($pixDirName . '/' . $arr[$randomImg],'theme') : $OUTPUT->pix_url($pixDirName . '/' . $arr[$randomImg],'theme');

	}


	return $output;



}




/*
 *
 * Method to get font icons
 *
 *
 */
function theme_mb2nl_font_icon ($page, $attribs = array())
{

	$output = '';


	$faIcons = theme_mb2nl_theme_setting($page, 'ficonfa', 1);
	$ficon7stroke = theme_mb2nl_theme_setting($page, 'ficon7stroke', 1);
	$glyphIcons = theme_mb2nl_theme_setting($page, 'ficonglyph', 1);


	if ($faIcons == 1)
	{
		$page->requires->css('/theme/mb2nl/assets/font-awesome/css/font-awesome.min.css');
	}


	if ($ficon7stroke == 1)
	{
		$page->requires->css('/theme/mb2nl/assets/pe-icon-7-stroke/css/pe-icon-7-stroke.min.css');
	}


	if ($glyphIcons == 1)
	{
		$page->requires->css('/theme/mb2nl/assets/bootstrap/css/glyphicons.min.css');
	}


	return $output;

}











/*
 *
 * Method to set body class
 *
 *
 */
function theme_mb2nl_settings_arr()
{

	global $CFG;
	$themename = theme_mb2nl_themename();

	$output = array(
		'all' => array('name'=>get_string('allsettings','theme_mb2nl'), 'icon'=>'fa fa-cogs', 'url'=> new moodle_url($CFG->wwwroot . '/admin/category.php?category=theme_' . $themename)),
		'general' => array('name'=>get_string('settingsgeneral','theme_mb2nl'), 'icon'=>'fa fa-dashboard', 'url'=>''),
		'features' => array('name'=>get_string('settingsfeatures','theme_mb2nl'), 'icon'=>'fa fa-dashboard', 'url'=>''),
		'fonts' => array('name'=>get_string('settingsfonts','theme_mb2nl'), 'icon'=>'fa fa-font', 'url'=>''),
		'nav' => array('name'=>get_string('settingsnav','theme_mb2nl'), 'icon'=>'fa fa-navicon', 'url'=>''),
		'social' => array('name'=>get_string('settingssocial','theme_mb2nl'), 'icon'=>'fa fa-share-alt', 'url'=>''),
		'style' => array('name'=>get_string('settingsstyle','theme_mb2nl'), 'icon'=>'fa fa-paint-brush', 'url'=>''),
		'typography' => array('name'=>get_string('settingstypography','theme_mb2nl'), 'icon'=>'fa fa-text-height', 'url'=>'')
	);

	return $output;

}







/*
 *
 * Method to get image url
 *
 *
 */
function theme_mb2nl_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{


	if ($context->contextlevel == CONTEXT_SYSTEM)
	{

	    $theme = theme_config::load('mb2nl');

		switch ($filearea)
		{

			case 'logo' :
			return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
			break;


			case 'pbgimage' :
			return $theme->setting_file_serve('pbgimage', $args, $forcedownload, $options);
			break;


			case 'bcbgimage' :
			return $theme->setting_file_serve('bcbgimage', $args, $forcedownload, $options);
			break;


			case 'acbgimage' :
			return $theme->setting_file_serve('acbgimage', $args, $forcedownload, $options);
			break;


			case 'asbgimage' :
			return $theme->setting_file_serve('asbgimage', $args, $forcedownload, $options);
			break;


			case 'loginbgimage' :
			return $theme->setting_file_serve('loginbgimage', $args, $forcedownload, $options);
			break;


			case 'loginlogo' :
			return $theme->setting_file_serve('loginlogo', $args, $forcedownload, $options);
			break;


			case 'loadinglogo' :
			return $theme->setting_file_serve('loadinglogo', $args, $forcedownload, $options);
			break;


			case 'favicon' :
			return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
			break;


			default :
			send_file_not_found();

		}

	}
	else
	{
        send_file_not_found();
    }

}







/*
 *
 * Method to set page class
 *
 *
 */
function theme_mb2nl_page_cls ($page, $course = false)
{

	$output = '';

	$isPage = $page->pagetype === 'mod-page-view';


	if ($course)
	{
		$pageId = $isPage ? $page->course->id : 0;
		$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursecls'), $pageId);
	}
	else
	{
		$pageId = $isPage ? $page->cm->id : 0;
		$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'pagecls'), $pageId);
	}


	return $output;

}







/*
 *
 * Method to set block class
 *
 *
 */
function theme_mb2nl_course_cls ($page)
{

	$output = '';


	$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursescls'), $page->course->id);


	return $output;

}





/*
 *
 * Method to set body class for course category theme
 *
 *
 */
function theme_mb2nl_courselist_cls($page)
{

	$output = '';


	$isCourse = $page->pagetype === 'course-index';
	$isCourseCat = $page->pagetype === 'course-index-category';
	$catId = $isCourseCat ? $page->category->id : 0;
	$clsPreff = 'coursetheme-';


	if ($catId > 0)
	{
		$output .= $clsPreff . theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursecattheme'), $catId);
	}
	else
	{
		$output .= $clsPreff . theme_mb2nl_theme_setting($page, 'coursetheme');
	}


	return $output;

}







/*
 *
 * Method to get array of css classess
 *
 *
 */
function theme_mb2nl_line_classes ($string, $id, $pref = '', $suff = '')
{



	$output = '';


	$blockStylesArr =  preg_split('/\r\n|\n|\r/', $string);



	if ($string !='')
	{

		foreach ($blockStylesArr as $line)
		{

			$lineArr = explode(':', $line);
			$prefArr = explode(',', $pref);

			if (trim($id) == trim($lineArr[0]))
			{
				$isPref1 = isset($prefArr[0]) ? $prefArr[0] : '';
				$output .= $prefArr[0] . $lineArr[1] . $suff;
			}

			if (isset($lineArr[2]))
			{
				if (trim($id) == trim($lineArr[0]))
				{
					$isPref2 = isset($prefArr[1]) ? $prefArr[1] : '';
					$output .= $isPref2 . $lineArr[2] . $suff;
				}
			}

		}

	}


	return $output;

}











/*
 *
 * Method to to get theme setting
 *
 *
 */
function theme_mb2nl_theme_setting ($page,$name,$default = '',$image = false,$theme = false)
{


	if ($theme)
	{
		if (!empty($theme->settings->$name))
		{

			if ($image)
			{
				$output = $theme->setting_file_url($name, $name);
			}
			else
			{
				$output = $theme->settings->$name;
			}
		}
		else
		{
			$output = $default;
		}

	}
	else
	{
		if (!empty($page->theme->settings->$name))
		{

			if ($image)
			{
				$output = $page->theme->setting_file_url($name, $name);
			}
			else
			{
				$output = $page->theme->settings->$name;
			}
		}
		else
		{
			$output = $default;
		}
	}



	return $output;

}







/*
 *
 * Method to theme links
 *
 *
 */
function theme_mb2nl_theme_links ()
{


	global $CFG,$USER;


	$output = '';
	$settings = theme_mb2nl_settings_arr();
	$themename = theme_mb2nl_themename();


	if (is_siteadmin())
	{
		$output .= '<div class="theme-links">';
		$output .= '<ul>';

		foreach ($settings as $id=>$v)
		{


			$url = $v['url'] ? $v['url'] : new moodle_url($CFG->wwwroot . '/admin/settings.php?section=theme_' . $themename . '_settings' . $id);

			$output .= '<li>';
			$output .= '<a href="' . $url . '">';
			$output .= '<i class="' . $v['icon'] . '"></i> ';
			$output .= $v['name'];
			$output .= '</a>';
			$output .= '</li>';

		}


		$docUrl = get_string('urldoc','theme_mb2nl');
		$moreUrl = get_string('urlmore','theme_mb2nl');
		$output .= '<li class="purgecaches-link"><a href="' . new moodle_url($CFG->wwwroot . '/admin/purgecaches.php') .
		'?confirm=1&amp;sesskey=' .  $USER->sesskey . '&amp;returnurl=%2Fmy%2Findex.php"><i class="fa fa-cog"></i> ' . get_string('purgecaches','admin') . '</a></li>';
		$output .= '<li class="custom-link"><a href="' . $docUrl . '"  target="_blank" class="link-doc"><i class="fa fa-info-circle"></i> ' . get_string('documentation','theme_mb2nl') . '</a></li>';
		$output .= '<li class="custom-link"><a href="' . $moreUrl . '" target="_blank" class="link-more"><i class="fa fa-shopping-basket"></i> ' . get_string('morethemes','theme_mb2nl') . '</a></li>';

		$output .= '</ul>';
		$output .= '</div>';
	}



	return $output;

}














/*
 *
 * Method to get safe url string
 *
 *
 */
function theme_mb2nl_string_url_safe($string)
{

	// Remove any '-' from the string since they will be used as concatenaters
	$output = str_replace('-', ' ', $string);


	// Trim white spaces at beginning and end of alias and make lowercase
	$output = trim(mb_strtolower($output));


	// Remove any duplicate whitespace, and ensure all characters are alphanumeric
	//$output = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $output);
   $output = preg_replace('#[^\w\d_\-\.]#iu', '-', $output);


	// Trim dashes at beginning and end of alias
	$output = trim($output, '-');


	return $output;

}






/*
 *
 * Method to get logo url
 *
 *
 */
function theme_mb2nl_logo_url($page, $customLogo = '', $login = true)
{

	global $OUTPUT, $CFG;
	$moodle33 = 2017051500;


	// Url to default logo image
	$defaultLogo = $CFG->version >= $moodle33 ? $OUTPUT->image_url('logo-default','theme') : $OUTPUT->pix_url('logo-default','theme');


	// Check if is custom login page
	$customLoginPage = theme_mb2nl_is_login($page, true);


	if ($login && $customLoginPage && theme_mb2nl_theme_setting($page,'loginlogo','', true) !='')
	{
		$isCustomLogo = theme_mb2nl_theme_setting($page,'loginlogo','', true);
	}
	else
	{
		$isCustomLogo = $customLogo !='' ? $customLogo : theme_mb2nl_theme_setting($page,'logo','', true);
	}


	$logoUrl = $isCustomLogo !='' ? $isCustomLogo : $defaultLogo;


	return $logoUrl;


}




/*
 *
 * Method to get page background image
 *
 *
 */
function theme_mb2nl_pagebg_image($page)
{

	global $OUTPUT, $CFG;
	$moodle33 = 2017051500;
	$pageBgUrl = '';


	// Url to page background image
	$pageBgDef = $CFG->version >= $moodle33 ? $OUTPUT->image_url('pagebg/default','theme') : $OUTPUT->pix_url('pagebg/default','theme');
	$pageBg = theme_mb2nl_theme_setting($page, 'pbgimage', '', true);
	$pageBgPre = theme_mb2nl_theme_setting($page, 'pbgpre', '');
	$pageBgLogin = theme_mb2nl_theme_setting($page, 'loginbgimage', '', true);


	// Check if is custom login page
	$customLoginPage = theme_mb2nl_is_login($page, true);


	if ($customLoginPage && $pageBgLogin !='')
	{
		$pageBgUrl = $pageBgLogin;
	}
	elseif ($pageBg !='')
	{
		$pageBgUrl = $pageBg;
	}
	elseif ($pageBgPre === 'default')
	{
		$pageBgUrl = $pageBgDef;
	}


	return $pageBgUrl !='' ? ' style="background-image:url(\'' . $pageBgUrl . '\');"' : '';


}






/*
 *
 * Method to get loading screen
 *
 *
 */
function theme_mb2nl_loading_screen($page)
{

	global $OUTPUT, $SITE;


	$output = '';


	$isBgColor = theme_mb2nl_theme_setting($page,'lbgcolor','') !='' ? ' style="background-color:' . theme_mb2nl_theme_setting($page,'lbgcolor','') . ';"' : '';

	if (!is_siteadmin())
	{
		$logow = theme_mb2nl_theme_setting($page,'loadinglogow') != '' ? theme_mb2nl_theme_setting($page,'loadinglogow') : theme_mb2nl_theme_setting($page,'logow',180);

		$output .= '<div class="loading-scr" data-hideafter="' . theme_mb2nl_theme_setting($page, 'loadinghide',600) . '"' . $isBgColor . '>';
		$output .= '<div class="loading-scr-inner" style="width:' . $logow . 'px;max-width:100%;margin-left:-' . round($logow/2) . 'px;">';
		$output .= '<img class="loading-scr-logo" src="' . theme_mb2nl_logo_url($page, theme_mb2nl_theme_setting($page,'loadinglogo','', true), false) . '" alt="' . $SITE->shortname . '">';
		$output .= '<div class="loading-scr-spinner"><img src="' . theme_mb2nl_loading_spinner() . '" alt="' . get_string('loading','theme_mb2nl') . '" style="width:' . theme_mb2nl_theme_setting($page, 'spinnerw', 35) . 'px;"></div>';
		$output .= '</div>';
		$output .= '</div>';


	}


	return $output;


}





/*
 *
 * Method to get spinner svg image
 *
 *
 */
function theme_mb2nl_loading_spinner ()
{

	global $CFG;
	$output = '';


	$spinnerDir = $CFG->dirroot . '/theme/mb2nl/pix/spinners/';
	$spinnerCustomDir = $CFG->dirroot . '/theme/mb2nl/pix/spinners/custom';


	$spinner = theme_mb2nl_random_image($spinnerDir, 'spinners', array('gif','svg'));
	$spinnerCustom = theme_mb2nl_random_image($spinnerCustomDir, 'spinners/custom', array('gif','svg'));


	$output = $spinnerCustom ? $spinnerCustom : $spinner;


	return $output;

}






/*
 *
 * Method to get loading screen
 *
 *
 */
function theme_mb2nl_scrolltt($page)
{

	global $OUTPUT, $SITE;


	$output = '';

	$output .= '<a class="theme-scrolltt" href="#"><i class="pe-7s-angle-up" data-scrollspeed="' . theme_mb2nl_theme_setting($page, 'scrollspeed',400) . '"></i></a>';


	return $output;


}













/*
 *
 * Method to get Gogole Analytics code
 *
 *
 */
function theme_mb2nl_ganalytics($page, $type = 1)
{

	$output = '';
	$codeId = theme_mb2nl_theme_setting($page, 'ganaid');
	$codeAsync = theme_mb2nl_theme_setting($page, 'ganaasync', 0);


	if ($codeId !='')
	{
		//Alternative async tracking snippet
		if($codeAsync == 1)
		{
			$output .= '<script>';
			$output .= 'window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;';
			$output .= 'ga(\'create\', \'' . $codeId . '\', \'auto\');';
			$output .= 'ga(\'send\', \'pageview\');';
			$output .= '</script>';
			$output .= '<script async src=\'https://www.google-analytics.com/analytics.js\'></script>';
		}
		else
		{
			$output .= '<script>';
			$output .= '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
			$output .= '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
			$output .= 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
			$output .= '})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');';
			$output .= 'ga(\'create\', \'' . $codeId . '\', \'auto\');';
			$output .= 'ga(\'send\', \'pageview\');';
			$output .= '</script>';
			$output .= '';
		}
	}


	return $output;


}






/*
 *
 * Method to get favicon
 *
 *
 */
function theme_mb2nl_favicon ($page)
{

	global $OUTPUT, $CFG;


	$output = '';
	$moodle33 = ($CFG->version >= 2017051500);
	$favImg = $CFG->dirroot . '/theme/mb2nl/pix/favicon/favicon-16x16.ico';


	// Additional favicons
	$favDeskDir = $CFG->dirroot . '/theme/mb2nl/pix/favicon/desktop/';
	$favMobDir = $CFG->dirroot . '/theme/mb2nl/pix/favicon/mobile/';
	$deskIcons = theme_mb2nl_file_arr($favDeskDir, array('png'));
	$mobIcons = theme_mb2nl_file_arr($favMobDir, array('png'));


	return $output;

}








/*
 *
 * Method to get course from category
 *
 *
 */
function theme_mb2nl_course ()
{

	global $PAGE, $CFG;

	if (!theme_mb2nl_moodle_from(2018120300))
    {
        require_once($CFG->libdir. '/coursecatlib.php');
    }

	require_once($CFG->dirroot . '/course/lib.php');

	$output = '';


	if ($PAGE->course->id > 1)
	{

		if (theme_mb2nl_moodle_from(2018120300))
		{
			$coursesList = core_course_category::get($PAGE->course->category)->get_courses();
		}
		else
		{
			$coursesList = coursecat::get($PAGE->course->category)->get_courses();
		}

		foreach ($coursesList as $course)
		{
			if ($PAGE->course->id == $course->id)
			{
				$output = $course;
			}
		}

	}


	return $output;

}





/*
 *
 * Method to display sho/hide sidebar button
 *
 *
 */
function theme_mb2nl_show_hide_sidebars ($page, $vars = array())
{

	$output = '';

	$sidebarBtn = theme_mb2nl_theme_setting($page,'sidebarbtn');
	$showSdebar = ($sidebarBtn == 1 || $sidebarBtn == 2);


	if (isset($vars['sidebar']) && $vars['sidebar'] && $showSdebar)
	{

		$output .= '<a href="#" class="theme-hide-sidebar mode' . $sidebarBtn . '" data-showtext="' . get_string('showsidebar','theme_mb2nl') . '" data-hidetext="' .
		get_string('hidesidebar','theme_mb2nl') . '">';
		$output .= $sidebarBtn == 2 ? get_string('showsidebar','theme_mb2nl') : get_string('hidesidebar','theme_mb2nl');
		$output .= '</a>';

	}


	return $output;

}








/*
 *
 * Method to add body class to idetntify moodle version in js files
 *
 *
 */
function theme_mb2nl_midentify ($vars = array())
{

	global $CFG;
	$output = '';


	$m34Cls = 'custom_id_a59e006be59d';
	$m33Cls = 'custom_id_f24fdc656fc4';
	$m35Cls = 'custom_id_5b1649004a21';


	$m33 = ($CFG->version >= 2017051500 && $CFG->version < 2017111300);
	$m34 = $CFG->version >= 2017111300;
	$m35 = $CFG->version >= 2018051700;


	if ($m33)
	{
		$output = $m33Cls;
	}
	elseif ($m34)
	{
		$output = $m34Cls;
	}


	if ($m35) {
		$output .= ' ' . $m35Cls;
	}


	return $output;

}



/*
 *
 * Method to get shot text from string
 *
 *
 */
function theme_mb2nl_wordlimit($string, $limit = 999, $end = '...')
{

	$output = $string;

	if ($limit < 999)
	{
		$content_limit = strip_tags($string);
		$words = explode(' ', $content_limit);
		$new_string = implode(' ', array_splice($words, 0, $limit));
		$word_count = str_word_count($string);
		$end_char = ($end !='' && $word_count > $limit) ? $end : '';

		$output = $new_string . $end_char;
	}

	return $output;

}





/*
 *
 * Method to check moodle version
 *
 *
 */
function theme_mb2nl_moodle_from ($version)
{

	global $CFG;

	if ($CFG->version >= $version)
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to get all plugins array
 *
 *
 */
function theme_mb2nl_plugins_arr()
{

	global $CFG;

	$output = array(
		'mb2slider'=>array('text'=>get_string('mb2slider_plugin','theme_mb2nl'),'file'=>$CFG->dirroot . '/blocks/mb2slider/block_mb2slider.php'),
		'mb2shortcodes_filter'=>array('text'=>get_string('mb2shortcodes_filter_plugin','theme_mb2nl'),'file'=>$CFG->dirroot . '/filter/mb2shortcodes/filter.php'),
		'mb2shortcodes_button'=>array('text'=>get_string('mb2shortcodes_button_plugin','theme_mb2nl'),'file'=>$CFG->dirroot . '/lib/editor/atto/plugins/mb2shortcodes/ajax.php')
	);

	return $output;

}





/*
 *
 * Method to check if plugins are installed
 *
 */
 function theme_mb2nl_check_plugins()
 {

 	$output = '';
 	$plugins = theme_mb2nl_plugins_arr();
 	$show_alert = 0;

 	foreach ($plugins as $id=>$plugin)
 	{

 		$show_alert = !file_exists($plugin['file']);

 		if ($id === 'mb2shortcodes_filter')
 		{
 			$show_alert = !function_exists('mb2_do_shortcode');
 		}

 		if ($show_alert)
 		{
 			$output .= '<div class="alert alert-warning" role="alert">' . $plugin['text'] . '</div>';
 		}

 	}

 	return $output;

 }



/*
 *
 * Method to check if user has role
 *
 *
 */
function theme_mb2nl_is_user_role($courseid, $roleid, $userid = 0)
{

	 $roles = get_user_roles(context_course::instance($courseid), $userid, false);

	 foreach ($roles as $role)
	 {
		  if ($role->roleid == $roleid)
		  {
			  return true;
		  }
	 }

    return false;
}



/*
 *
 * Method to set page title
 *
 */
function theme_mb2nl_page_title($coursename = true)
{

	global $PAGE, $COURSE;

	$title = '';

	$itle_arr = explode(':', $PAGE->title);

	if ($coursename && $COURSE->id > 1 && !preg_match('@course-view@', $PAGE->pagetype))
	{
		$title .= $COURSE->fullname . ': ';
	}

	$title .= end($itle_arr);

	return $title;

}



/*
 *
 * Method to fix problem in lesson layout in M36
 *
 */
function theme_mb2nl_fix_html_lesson()
{

	global $PAGE, $DB;

	$output = '';

	if ($PAGE->pagetype !== 'mod-lesson-view')
	{
		return;
	}

	$id = required_param('id', PARAM_INT);
	$context = context_module::instance($PAGE->cm->id);
	$cm = get_coursemodule_from_id('lesson', $id, 0, false, MUST_EXIST);
	$pageid = optional_param('pageid', null, PARAM_INT);
	$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
	$lesson = new lesson($DB->get_record('lesson', array('id' => $cm->instance), '*', MUST_EXIST), $cm, $course);
	$can_edit = has_capability('mod/lesson:manage', $context);

	if (theme_mb2nl_moodle_from(2018120300) && !$can_edit && preg_match('@pageid@',$PAGE->url->get_query_string()) && $lesson->progressbar)
	{
		$output = '</div>';
	}

	return $output;

}




/*
 *
 * Method to get course categor layout switcher
 *
 */
function theme_mb2nl_course_layout_switcher()
{
	global $PAGE;
	$output = '';
	$is_course_cat = ($PAGE->pagetype === 'course-index-category' && theme_mb2nl_theme_setting($PAGE, 'coursegridcat'));
	$is_course_fp = ($PAGE->pagetype === 'site-index' && theme_mb2nl_theme_setting($PAGE, 'coursegridfp'));
	$actice_cls_grid = '';
	$actice_cls_list = ' active';

	if ($is_course_cat || $is_course_fp)
	{
		$actice_cls_grid = ' active';
		$actice_cls_list = '';
	}

	$output .= '<div class="course-layout-switcher">';
	$output .= '<a href="#" class="grid-layout' . $actice_cls_grid . '" title="' . get_string('layoutgrid', 'theme_mb2nl') . '"><i class="fa fa-th-large"></i></a>';
	$output .= '<a href="#" class="list-layout' . $actice_cls_list . '" title="' . get_string('layoutlist', 'theme_mb2nl') . '"><i class="fa fa-th-list"></i></a>';
	$output .= '</div>';

	return $output;

}



/*
 *
 * Method to set course layout body class
 *
 */
function theme_mb2nl_course_layout_class()
{

	global $PAGE;
	$output = '';
	$is_course_cat = ($PAGE->pagetype === 'course-index-category' && theme_mb2nl_theme_setting($PAGE, 'coursegridcat'));
	$is_course_fp = ($PAGE->pagetype === 'site-index' && theme_mb2nl_theme_setting($PAGE, 'coursegridfp'));

	if ($is_course_cat || $is_course_fp)
	{
		return 'course-layout-grid';
	}

	return ;

}




/*
 *
 * Method to check if sidebar exists
 *
 */
function theme_mb2nl_is_sidebars()
{

	global $PAGE;
	$sidePre = theme_mb2nl_isblock($PAGE, 'side-pre');
	$sidePost = theme_mb2nl_isblock($PAGE, 'side-post');

	if ($PAGE->user_is_editing())
	{
		return  2;
	}

	if ($sidePre && $sidePost)
	{
		return 2;
	}
	elseif ($sidePre || $sidePre)
	{
		return 1;
	}

	return 0;

}




/*
 *
 * Method to check for front page courses
 *
 */
function theme_mb2nl_frontpage_courses()
{

	global $CFG;

	$loggedin_arr = explode(',', $CFG->frontpageloggedin);
	$noneloggedin_arr = explode(',', $CFG->frontpage);

	if (isloggedin() && !isguestuser())
	{
		if (in_array(6, $loggedin_arr))
		{
			return true;
		}
	}
	else
	{
		if (in_array(6, $noneloggedin_arr))
		{
			return true;
		}
	}

	return false;

}
