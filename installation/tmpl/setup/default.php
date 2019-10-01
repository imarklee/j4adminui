<?php
/**
 * @package     Joomla.Installation
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var \Joomla\CMS\Installation\View\Setup\HtmlView $this */
?>



<div id="installer-view" data-page-name="setup">

	<div class="row no-gutters">
		<div class="col-auto">
			<ul class="j-install-menu">
				<li>
					<button class="active" title="<?php echo Text::_('INSTL_SELECT_INSTALL_LANG'); ?>">Choose Language</button>
				</li>
				<li>
					<button title="<?php echo Text::_('INSTL_SETUP_SITE_NAME'); ?>">Site Configuration</button>
				</li>
				<li>
					<button>Database Configuration</button>
				</li>
				<li>
					<button>Installing Site</button>
				</li>
				<li>
					<button>Finalizion Installation</button>
				</li>

			</ul>
		</div>
		<div class="col">
			<form action="index.php" method="post" id="languageForm" class="lang-select j-install-form">
				<fieldset class="j-install-step active">
					<div class="j-install-step-form">
						<div class="form-group">
							<?php echo $this->form->getLabel('language'); ?>
							<?php echo $this->form->getInput('language'); ?>
						</div>
						<input type="hidden" name="task" value="language.set">
						<input type="hidden" name="format" value="json">
						<?php echo HTMLHelper::_('form.token'); ?>
					</div>
				</fieldset>
			</form>
			<form action="index.php" method="post" id="adminForm" class="form-validate j-install-form">
				<fieldset id="installStep1" class="j-install-step active">
					<div class="j-install-step-form">
						<div class="form-group">
							<?php echo $this->form->getLabel('site_name'); ?>
							<?php echo $this->form->getInput('site_name'); ?>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" id="step1"><?php echo Text::_('INSTL_SETUP_LOGIN_DATA'); ?> <span class="fa fa-chevron-right" aria-hidden="true"></span></button>
						</div>
					</div>
				</fieldset>
				<fieldset id="installStep2" class="j-install-step">
					<legend class="j-install-step-header">
						<span class="fa fa-lock" aria-hidden="true"></span> <?php echo Text::_('INSTL_LOGIN_DATA'); ?>
					</legend>
					<div class="j-install-step-form">
						<div class="form-group">
							<?php echo $this->form->getLabel('admin_user'); ?>
							<?php echo $this->form->getInput('admin_user'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('admin_username'); ?>
							<?php echo $this->form->getInput('admin_username'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('admin_password'); ?>
							<?php echo $this->form->getInput('admin_password'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('admin_email'); ?>
							<?php echo $this->form->getInput('admin_email'); ?>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" id="step2"><?php echo Text::_('INSTL_CONNECT_DB'); ?> <span class="fa fa-chevron-right" aria-hidden="true"></span></button>
						</div>
					</div>
				</fieldset>
				<fieldset id="installStep3" class="j-install-step" >
					<legend class="j-install-step-header">
						<span class="fa fa-database" aria-hidden="true"></span> <?php echo Text::_('INSTL_DATABASE'); ?>
					</legend>
					<div class="j-install-step-form">
						<div class="form-group">
							<?php echo $this->form->getLabel('db_type'); ?>
							<?php echo $this->form->getInput('db_type'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('db_host'); ?>
							<?php echo $this->form->getInput('db_host'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('db_user'); ?>
							<?php echo $this->form->getInput('db_user'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('db_pass'); ?>
							<?php echo $this->form->getInput('db_pass'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('db_name'); ?>
							<?php echo $this->form->getInput('db_name'); ?>
						</div>
						<div class="form-group">
							<?php echo $this->form->getLabel('db_prefix'); ?>
							<?php echo $this->form->getInput('db_prefix'); ?>
						</div>
						<div class="form-group">
							<?php //echo $this->form->getLabel('db_old'); ?>
							<?php echo $this->form->getInput('db_old'); ?>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-block" id="setupButton"><?php echo Text::_('INSTL_INSTALL_JOOMLA'); ?> <span class="fa fa-chevron-right" aria-hidden="true"></span></button>
						</div>
					</div>
				</fieldset>

				<input type="hidden" name="admin_password2" id="jform_admin_password2">
				<?php echo HTMLHelper::_('form.token'); ?>
			</form>
		</div>
	</div>
</div>
