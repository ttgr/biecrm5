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

use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\Uri\Uri;

/**
 * The Delegates controller
 *
 * @since  1.0.1
 */
class CurrentsController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $contentType = 'delegates';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  1.0.1
	 */
	protected $default_view = 'currents';



    public function displayList()
    {
        $apiFilterInfo = $this->input->get('filter', [], 'array');
        $filter        = InputFilter::getInstance();

        if (\array_key_exists('author', $apiFilterInfo)) {
            $this->modelState->set('filter.author_id', $filter->clean($apiFilterInfo['author'], 'INT'));
        }

        $this->modelState->set('filter.active', '1');

        $currentUrl           = Uri::getInstance();
        $itemsDefaultOrdering = ['ordering' => 'id', 'direction' => 'ASC'];
        $itemsOrderingQuery   = $currentUrl->getVar('page', $itemsDefaultOrdering);

        $this->modelState->set('list.ordering', $itemsOrderingQuery['ordering']);
        $this->modelState->set('list.direction', $itemsOrderingQuery['direction']);

        return parent::displayList();
    }




}