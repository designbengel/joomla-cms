<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.Atum
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       4.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var JDocumentHtml $this */

$app   = Factory::getApplication();
$lang  = $app->getLanguage();
$input = $app->input;
$wa    = $this->getWebAssetManager();

// Detecting Active Variables
$option     = $input->get('option', '');
$view       = $input->get('view', '');
$layout     = $input->get('layout', 'default');
$task       = $input->get('task', 'display');
$itemid     = $input->get('Itemid', '');
$cpanel     = $option === 'com_cpanel';
$hiddenMenu = $app->input->get('hidemainmenu');

require_once __DIR__ . '/Service/HTML/Atum.php';

// Template params
$headerExpandedLogo  = $this->params->get('headerExpandedLogo')
	? Uri::root() . htmlspecialchars($this->params->get('headerExpandedLogo'), ENT_QUOTES)
	: $this->baseurl . '/templates/' . $this->template . '/images/logos/header-expanded.svg';
$loginLogo = $this->params->get('loginLogo')
	? Uri::root() . $this->params->get('loginLogo')
	: $this->baseurl . '/templates/' . $this->template . '/images/logos/login.svg';
$headerCollapsedLogo = $this->params->get('headerCollapsedLogo')
	? Uri::root() . htmlspecialchars($this->params->get('headerCollapsedLogo'), ENT_QUOTES)
	: $this->baseurl . '/templates/' . $this->template . '/images/logos/header-collapsed.svg';

$headerExpandedLogoAlt = htmlspecialchars($this->params->get('headerExpandedLogoAlt', ''), ENT_COMPAT, 'UTF-8');
$headerCollapsedLogoAlt = htmlspecialchars($this->params->get('headerCollapsedLogoAlt', ''), ENT_COMPAT, 'UTF-8');

// Enable assets
$wa->usePreset('template.atum.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
	->useStyle('template.active.language')
	->useStyle('template.user');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.atum.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

// Set some meta data
$this->setMetaData('viewport', 'width=device-width, initial-scale=1');
// @TODO sync with _variables.scss
$this->setMetaData('theme-color', '#1c3d5c');

$monochrome = (bool) $this->params->get('monochrome');

HTMLHelper::getServiceRegistry()->register('atum', 'JHtmlAtum');
HTMLHelper::_('atum.rootcolors', $this->params);

// Add cookie alert message
Text::script('JGLOBAL_WARNCOOKIES');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="metas" />
	<jdoc:include type="styles" />
</head>

<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ($task ? ' task-' . $task : '') . ($monochrome ? ' monochrome' : ''); ?>">

<noscript>
	<div class="alert alert-danger" role="alert">
		<?php echo Text::_('JGLOBAL_WARNJAVASCRIPT'); ?>
	</div>
</noscript>
<div class="ie11 alert alert-warning" role="alert">
	<?php echo Text::_('JGLOBAL_WARNIE'); ?>
</div>

<header id="header" class="header">
	<div class="d-flex">
		<div class="header-title d-flex">
			<div class="d-flex align-items-center">
				<?php // No home link in edit mode (so users can not jump out) and control panel (for a11y reasons) ?>
				<div class="logo">
					<img src="<?php echo $headerExpandedLogo; ?>" alt="<?php echo $headerExpandedLogoAlt; ?>">
					<img class="logo-collapsed" src="<?php echo $headerCollapsedLogo; ?>" alt="<?php echo $headerCollapsedLogoAlt; ?>">
				</div>
			</div>
			<jdoc:include type="modules" name="title" />
		</div>
		<div class="header-items d-flex">
			<jdoc:include type="modules" name="status" style="header-item" />
		</div>
	</div>
</header>

<div id="wrapper" class="d-flex wrapper">

	<div class="container-fluid container-main order-1">
		<section id="content" class="content h-100">
			<main class="d-flex justify-content-center align-items-center h-100">
				<div class="login">
					<div class="main-brand text-center">
						<img src="<?php echo $loginLogo; ?>"
							 alt="<?php echo htmlspecialchars($this->params->get('altLoginLogo', ''), ENT_COMPAT, 'UTF-8'); ?>">
					</div>
					<jdoc:include type="component" />
				</div>
			</main>
		</section>

		<div class="notify-alerts">
			<jdoc:include type="message" />
		</div>
	</div>

	<?php // Sidebar ?>
	<div id="sidebar-wrapper" class="sidebar-wrapper order-0">
		<div id="main-brand" class="main-brand">
			<h1><?php echo $app->get('sitename'); ?></h1>
			<h2><?php echo Text::_('TPL_ATUM_BACKEND_LOGIN'); ?></h2>
		</div>
		<div id="sidebar">
			<jdoc:include type="modules" name="sidebar" style="body" />
		</div>
	</div>
</div>
<jdoc:include type="modules" name="debug" style="none" />
<jdoc:include type="scripts" />
</body>
</html>
