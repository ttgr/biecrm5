<?php
/**
 * @version    CVS: 1.0.3
 * @package    Com_Bieapilogin
 * @author     Tasos Triantis <tasos.tr@gmail.com>
 * @copyright  2025 Tasos Triantis
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Bieapilogin\Component\Bieapilogin\Administrator\Extension\BieapiloginComponent;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;


/**
 * The Bieapilogin service provider.
 *
 * @since  1.0.3
 */
return new class implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   1.0.3
	 */
	public function register(Container $container)
	{

		$container->registerServiceProvider(new CategoryFactory('\\Bieapilogin\\Component\\Bieapilogin'));
		$container->registerServiceProvider(new MVCFactory('\\Bieapilogin\\Component\\Bieapilogin'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Bieapilogin\\Component\\Bieapilogin'));
		$container->registerServiceProvider(new RouterFactory('\\Bieapilogin\\Component\\Bieapilogin'));

		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new BieapiloginComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
