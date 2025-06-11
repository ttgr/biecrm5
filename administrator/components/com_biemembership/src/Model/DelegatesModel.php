<?php
/**
 * @version    CVS: 1.0.1
 * @package    Com_Biemembership
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Biemembership\Component\Biemembership\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Biemembership\Component\Biemembership\Administrator\Helper\BiemembershipHelper;

/**
 * Methods supporting a list of Delegates records.
 *
 * @since  1.0.1
 */
class DelegatesModel extends ListModel
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
			);
		}

		parent::__construct($config);
	}


	

	

	

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('id', 'ASC');

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		// Split context into component and optional section
		if (!empty($context))
		{
			$parts = FieldsHelper::extract($context);

			if ($parts)
			{
				$this->setState('filter.component', $parts[0]);
				$this->setState('filter.section', $parts[1]);
			}
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string A store id.
	 *
	 * @since   1.0.1
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		
		return parent::getStoreId($id);
		
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  DatabaseQuery
	 *
	 * @since   1.0.1
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
        $fields = [
            'm.start_date',
            'm.end_date',
            'm.status_id',
        ];
        $query->select('a.*');
        $query->select($db->quoteName($fields));
		$query->from('`civicrm_contact` AS a');

		$query->join("INNER", "civicrm_membership AS m ON m.contact_id = a.id");
		$query->join('LEFT', 'civicrm_value_contacts_2 AS `ord` ON (`a`.`id` = `ord`.`entity_id`)');

        $query->where(' a.contact_type = ' . $db->quote('Individual'));
        $query->where(' m.membership_type_id = ' . $db->quote(2));

        $filter_active = $this->state->get("filter.active");
        if ($filter_active)
        {
            $query->where('m.status_id = '. $db->quote(2));
        }


        // Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'ASC');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
        //Factory::getApplication()->enqueueMessage($db->replacePrefix((string) $query), 'notice');
		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
        foreach ($items as $item) {
            $item->active  = $item->status_id == 2;
            $item->member_state = $this->getMemberState($item->employer_id);
        }
		return $items;
	}


    public function getTotals() {
        $query = $this->getListQuery();
        $db = Factory::getContainer()->get('DatabaseDriver');
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return count($items);
    }

    private function getMemberState(int $contact_id) : array {
        if ((int) $contact_id == 0) {
            return [];
        }

        $db    = $this->getDatabase();
        $query = $db->getQuery(true);
        $query->select($db->quoteName('v.organization_name_en_1', 'orgname_en'));
        $query->select($db->quoteName('v.organization_name_fr_2', 'orgname_fr'));
        $query->select($db->quoteName('v.username_code_3', 'uname_code'));
        $query->from('`civicrm_contact` AS c');
        $query->join('LEFT', 'civicrm_value_organisaztion_name_en_1 AS `v` ON (`c`.`id` = `v`.`entity_id`)');
        $query->where(' c.id = ' . $db->quote($contact_id));
        $db->setQuery($query);
        return $db->loadAssoc();
    }
}
