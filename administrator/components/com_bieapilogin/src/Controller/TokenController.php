<?php
/**
 * @version    CVS: 1.0.3
 * @package    Com_Bieapilogin
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Bieapilogin\Component\Bieapilogin\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;

/**
 * Token controller class.
 *
 * @since  1.0.3
 */
class TokenController extends FormController
{
	protected $view_list = 'tokens';
}
