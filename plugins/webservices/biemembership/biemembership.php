<?php
/**
 * @package    Com_Biemembership
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\ApiRouter;

/**
 * Web Services adapter for biemembership.
 *
 * @since  1.0.1
 */
class PlgWebservicesBiemembership extends CMSPlugin
{
	public function onBeforeApiRoute(&$router)
	{
		
		$router->createCRUDRoutes('v1/biemembership/delegates', 'delegates', ['component' => 'com_biemembership']);
		$router->createCRUDRoutes('v1/biemembership/delegates/current', 'currents', ['component' => 'com_biemembership']);
		$router->createCRUDRoutes('v1/biemembership/memberstates', 'memberstates', ['component' => 'com_biemembership']);
		$router->createCRUDRoutes('v1/biemembership/membershipdashboards', 'membershipdashboards', ['component' => 'com_biemembership']);
	}
}
