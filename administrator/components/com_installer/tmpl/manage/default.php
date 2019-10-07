<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_installer
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('script', 'com_installer/changelog.js', ['version' => 'auto', 'relative' => true]);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<div id="installer-manage" class="clearfix">
	<form action="<?php echo Route::_('index.php?option=com_installer&view=manage'); ?>" method="post" name="adminForm" id="adminForm">
		<div class="row">
			<div class="col-md-12">
				<div id="j-main-container" class="j-main-container">
					<?php if ($this->showMessage) : ?>
						<?php echo $this->loadTemplate('message'); ?>
					<?php endif; ?>
					<?php if ($this->ftp) : ?>
						<?php echo $this->loadTemplate('ftp'); ?>
					<?php endif; ?>
					<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
					<?php if (empty($this->items)) : ?>
						<div class="j-alert j-alert-info">
							<span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
							<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
						</div>
					<?php else : ?>
					<table class="table j-list-table" id="manageList">
						<caption id="captionTable" class="sr-only">
							<?php echo Text::_('COM_INSTALLER_MANAGE_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
								<td style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" style="width:1%" class="text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'status', $listDirn, $listOrder); ?>
								</th>
								<th scope="col">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_NAME', 'name', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_LOCATION', 'client_translated', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_TYPE', 'type_translated', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-lg-table-cell">
									<?php echo Text::_('JVERSION'); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo Text::_('JDATE'); ?>
								</th>
								<th scope="col" style="width:10%" class="d-none d-md-table-cell">
									<?php echo Text::_('JAUTHOR'); ?>
								</th>
								<th scope="col" style="width:5%" class="d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_FOLDER', 'folder_translated', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_PACKAGE_ID', 'package_id', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="width:1%" class="d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_INSTALLER_HEADING_ID', 'extension_id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($this->items as $i => $item) : ?>
							<tr class="row<?php echo $i % 2; if ($item->status == 2) echo ' protected'; ?>">
								<td class="text-center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->extension_id); ?>
								</td>
								<td class="text-center">
									<?php if (!$item->element) : ?>
									<strong>X</strong>
									<?php else : ?>
										<?php echo HTMLHelper::_('manage.state', $item->status, $i, $item->status < 2, 'cb'); ?>
									<?php endif; ?>
								</td>
								<th scope="row">
									<span tabindex="0"><?php echo $item->name; ?></span>
									<div role="tooltip" id="tip<?php echo $i; ?>">
										<?php echo $item->description; ?>
									</div>
								</th>
								<td>
									<?php echo $item->client_translated; ?>
								</td>
								<td>
									<?php echo $item->type_translated; ?>
								</td>
								<td class="d-none d-lg-table-cell">
									<?php if ($item->version !== '') : ?>
										<?php if (!empty($item->changelogurl)) : ?>
											<a data-href="#changelogModal<?php echo $item->extension_id; ?>" href="#" class="changelogModal" data-js-extensionid="<?php echo $item->extension_id; ?>" data-js-view="manage">
												<?php echo $item->version?>
											</a>
											<?php
											echo HTMLHelper::_(
												'webcomponent.renderModal',
												'changelogModal' . $item->extension_id,
												array(
													'title' => Text::sprintf('COM_INSTALLER_CHANGELOG_TITLE', $item->name, $item->version),
												),
												''
											);
											?>
										<?php else : ?>
											<?php echo $item->version; ?>
										<?php endif; ?>
									<?php else :
										echo '&#160;';
									endif; ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo !empty($item->creationDate) ? $item->creationDate : '&#160;'; ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo !empty($item->author) ? $item->author : '&#160;'; ?>
								</td>
								<td class="d-none d-lg-table-cell">
									<?php echo $item->folder_translated; ?>
								</td>
								<td class="d-none d-lg-table-cell">
									<?php echo $item->package_id ?: '&#160;'; ?>
								</td>
								<td class="d-none d-md-table-cell">
									<?php echo $item->extension_id; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					
					<!-- load the pagination. -->
					<div class="j-pagination-footer">
						<?php echo LayoutHelper::render('joomla.searchtools.default.listlimit', array('view' => $this)); ?>
						<?php echo $this->pagination->getListFooter(); ?>
					</div>
					<?php endif; ?>


					<input type="hidden" name="task" value="">
					<input type="hidden" name="boxchecked" value="0">
					<?php echo HTMLHelper::_('form.token'); ?>
				</div>
			</div>
		</div>
	</form>
</div>
