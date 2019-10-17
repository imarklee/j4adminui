<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

/**
 * Utility class for creating HTML Grids
 *
 * @since  1.5
 */
abstract class JHtmlGrid
{
	/**
	 * Method to sort a column in a grid
	 *
	 * @param   string  $title          The link title
	 * @param   string  $order          The order field for the column
	 * @param   string  $direction      The current direction
	 * @param   string  $selected       The selected ordering
	 * @param   string  $task           An optional task override
	 * @param   string  $new_direction  An optional direction for the new column
	 * @param   string  $tip            An optional text shown as tooltip title instead of $title
	 * @param   string  $form           An optional form selector
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function sort($title, $order, $direction = 'asc', $selected = '', $task = null, $new_direction = 'asc', $tip = '', $form = null)
	{
		HTMLHelper::_('behavior.core');
		HTMLHelper::_('bootstrap.popover');

		$direction = strtolower($direction);
		$icon = array('arrow-up-3', 'arrow-down-3');
		$index = (int) ($direction === 'desc');

		if ($order != $selected)
		{
			$direction = $new_direction;
		}
		else
		{
			$direction = $direction === 'desc' ? 'asc' : 'desc';
		}

		if ($form)
		{
			$form = ', document.getElementById(\'' . $form . '\')';
		}

		$html = '<a href="#" onclick="Joomla.tableOrdering(\'' . $order . '\',\'' . $direction . '\',\'' . $task . '\'' . $form . ');return false;"'
			. ' class="hasPopover" title="' . htmlspecialchars(Text::_($tip ?: $title)) . '"'
			. ' data-content="' . htmlspecialchars(Text::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN')) . '" data-placement="top">';

		if (isset($title['0']) && $title['0'] === '<')
		{
			$html .= $title;
		}
		else
		{
			$html .= Text::_($title);
		}

		if ($order == $selected)
		{
			$html .= '<span class="icon-' . $icon[$index] . '"></span>';
		}

		$html .= '</a>';

		return $html;
	}

	/**
	 * Method to check all checkboxes in a grid
	 *
	 * @param   string  $name    The name of the form element
	 * @param   string  $action  The action to perform on clicking the checkbox
	 *
	 * @return  string
	 *
	 * @since   3.1.2
	 */
	public static function checkall($name = 'checkall-toggle', $action = 'Joomla.checkAll(this)')
	{
		HTMLHelper::_('behavior.core');

		return '<input type="checkbox" name="' . $name . '" class="j-checkbox" value="" title="' . Text::_('JGLOBAL_CHECK_ALL') . '" onclick="' . $action . '">';
	}

	/**
	 * Method to create a checkbox for a grid row.
	 *
	 * @param   integer  $rowNum      The row index
	 * @param   integer  $recId       The record id
	 * @param   boolean  $checkedOut  True if item is checked out
	 * @param   string   $name        The name of the form element
	 * @param   string   $stub        The name of stub identifier
	 * @param   string   $title       The name of the item
	 * @param   string   $formId      An optional form selector.
	 *
	 * @return  mixed    String of html with a checkbox if item is not checked out, null if checked out.
	 *
	 * @since   1.5
	 */
	public static function id($rowNum, $recId, $checkedOut = false, $name = 'cid', $stub = 'cb', $title = '', $formId = null)
	{
		if ($formId !== null)
		{
			return $checkedOut ? '' : '<label for="' . $stub . $rowNum . '"><span class="sr-only">' . Text::_('JSELECT')
				. ' ' . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . '</span></label>'
				. '<input type="checkbox" id="' . $stub . $rowNum . '" name="' . $name . '[]" class="j-checkbox" value="' . $recId
				. '" onclick="Joomla.isChecked(this.checked, \'' . $formId . '\');">';
		}

		return $checkedOut ? '' : '<label for="' . $stub . $rowNum . '"><span class="sr-only">' . Text::_('JSELECT')
			. ' ' . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . '</span></label>'
			. '<input type="checkbox" id="' . $stub . $rowNum . '" name="' . $name . '[]" class="j-checkbox" value="' . $recId
			. '" onclick="Joomla.isChecked(this.checked);">';
	}

	/**
	 * Displays a checked out icon.
	 *
	 * @param   object   &$row        A data object (must contain checkedout as a property).
	 * @param   integer  $i           The index of the row.
	 * @param   string   $identifier  The property name of the primary key or index of the row.
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function checkedOut(&$row, $i, $identifier = 'id')
	{
		$user = Factory::getUser();
		$userid = $user->get('id');

		if ($row instanceof Table)
		{
			$result = $row->isCheckedOut($userid);
		}
		else
		{
			$result = false;
		}

		if ($result)
		{
			return static::_checkedOut($row);
		}
		else
		{
			if ($identifier === 'id')
			{
				return HTMLHelper::_('grid.id', $i, $row->$identifier);
			}
			else
			{
				return HTMLHelper::_('grid.id', $i, $row->$identifier, $result, $identifier);
			}
		}
	}

	/**
	 * Method to create a clickable icon to change the state of an item
	 *
	 * @param   mixed    $value   Either the scalar value or an object (for backward compatibility, deprecated)
	 * @param   integer  $i       The index
	 * @param   string   $img1    Image for a positive or on value
	 * @param   string   $img0    Image for the empty or off value
	 * @param   string   $prefix  An optional prefix for the task
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function published($value, $i, $img1 = 'tick.png', $img0 = 'publish_x.png', $prefix = '')
	{
		if (is_object($value))
		{
			$value = $value->published;
		}

		$img = $value ? $img1 : $img0;
		$task = $value ? 'unpublish' : 'publish';
		$alt = $value ? Text::_('JPUBLISHED') : Text::_('JUNPUBLISHED');
		$action = $value ? Text::_('JLIB_HTML_UNPUBLISH_ITEM') : Text::_('JLIB_HTML_PUBLISH_ITEM');

		return '<a href="#" onclick="return Joomla.listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">'
			. HTMLHelper::_('image', 'admin/' . $img, $alt, null, true) . '</a>';
	}

	/**
	 * Method to create a select list of states for filtering
	 * By default the filter shows only published and unpublished items
	 *
	 * @param   string  $filter_state  The initial filter state
	 * @param   string  $published     The Text string for published
	 * @param   string  $unpublished   The Text string for Unpublished
	 * @param   string  $archived      The Text string for Archived
	 * @param   string  $trashed       The Text string for Trashed
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function state($filter_state = '*', $published = 'JPUBLISHED', $unpublished = 'JUNPUBLISHED', $archived = null, $trashed = null)
	{
		$state = array('' => '- ' . Text::_('JLIB_HTML_SELECT_STATE') . ' -', 'P' => Text::_($published), 'U' => Text::_($unpublished));

		if ($archived)
		{
			$state['A'] = Text::_($archived);
		}

		if ($trashed)
		{
			$state['T'] = Text::_($trashed);
		}

		return HTMLHelper::_(
			'select.genericlist',
			$state,
			'filter_state',
			array(
				'list.attr' => 'class="form-control" size="1" onchange="Joomla.submitform();"',
				'list.select' => $filter_state,
				'option.key' => null,
			)
		);
	}

	/**
	 * Method to create an icon for saving a new ordering in a grid
	 *
	 * @param   array   $rows   The array of rows of rows
	 * @param   string  $image  The image [UNUSED]
	 * @param   string  $task   The task to use, defaults to save order
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function order($rows, $image = 'filesave.png', $task = 'saveorder')
	{
		return '<a href="javascript:saveorder('
			. (count($rows) - 1) . ', \'' . $task . '\')" rel="tooltip" class="saveorder btn btn-sm btn-secondary float-right" title="'
			. Text::_('JLIB_HTML_SAVE_ORDER') . '"><span class="icon-caret-v" area-hidden="true"></span></a>';
	}

	/**
	 * Method to create a checked out icon with optional overlib in a grid.
	 *
	 * @param   object   &$row     The row object
	 * @param   boolean  $overlib  True if an overlib with checkout information should be created.
	 *
	 * @return  string   HTMl for the icon and overlib
	 *
	 * @since   1.5
	 */
	protected static function _checkedOut(&$row, $overlib = true)
	{
		$hover = '';

		if ($overlib)
		{
			$date = HTMLHelper::_('date', $row->checked_out_time, Text::_('DATE_FORMAT_LC1'));
			$time = HTMLHelper::_('date', $row->checked_out_time, 'H:i');

			$hover = '<span class="editlinktip hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JLIB_HTML_CHECKED_OUT', $row->editor)
				. '<br>' . $date . '<br>' . $time . '">';
		}

		return $hover . HTMLHelper::_('image', 'admin/checked_out.png', null, null, true) . '</span>';
	}
}
