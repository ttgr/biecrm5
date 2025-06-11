<?php
/**
 * @version    CVS: 1.0.1
 * @package    Com_Biemembership
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Biemembership\Component\Biemembership\Api\View\Memberstates;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;

/**
 * The Memberstates view
 *
 * @since  1.0.1
 */
class JsonApiView extends BaseApiView
{
	/**
	 * The fields to render item in the documents
	 *
	 * @var    array
	 * @since  1.0.1
	 */
	protected $fieldsToRenderItem = [
		'id', 
		'state', 
		'ordering', 
	];

	/**
	 * The fields to render items in the documents
	 *
	 * @var    array
	 * @since  1.0.1
	 */
	protected $fieldsToRenderList = [
		'id', 
		'state', 
		'ordering', 
	];
}