<?php
/**
 * @version    CVS: 1.0.1
 * @package    Com_Biemembership
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Biemembership\Component\Biemembership\Api\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\ApiController;

/**
 * The Memberstates controller
 *
 * @since  1.0.1
 */
class MemberstatesController extends ApiController 
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $contentType = 'memberstates';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $default_view = 'memberstates';
}