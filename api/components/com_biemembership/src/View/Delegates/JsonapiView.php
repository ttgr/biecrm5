<?php
/**
 * @version    CVS: 1.0.1
 * @package    Com_Biemembership
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Biemembership\Component\Biemembership\Api\View\Delegates;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Config\Administrator\Model\ApplicationModel;

/**
 * The Delegates view
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
        'first_name',
        'last_name',
        'job_title',
        'start_date',
        'end_date',
        'status_id',
        'preferred_language',
        'active',
        'employer_id',
        'created_date',
        'member_state',
	];


    public function displayList(?array $items = null)
    {
        /** @var ApplicationModel $model */
        $totals = $this->getModel()->getTotals();

        $currentUrl                    = Uri::getInstance();
        $currentPageDefaultInformation = ['offset' => 0, 'limit' => 20,'ordering'=>'id','direction'=>'DESC'];
        $currentPageQuery              = $currentUrl->getVar('page', $currentPageDefaultInformation);


        $this->getDocument()->addMeta('total-items',$totals);
        $this->getDocument()->addMeta('offset',$currentPageQuery['offset']);
        $this->getDocument()->addMeta('limit',$currentPageQuery['limit']);
        $this->getDocument()->addMeta('ordering',$currentPageQuery['ordering']);
        $this->getDocument()->addMeta('direction',$currentPageQuery['direction']);


        return parent::displayList();

    }

}