<?php



$context = 'site';

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);



if (!defined('_JDEFINES')) {
    define('JPATH_BASE', realpath(dirname(__FILE__) . '/../'));
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';
$contextConfig = [
    'site' => [
        'session' => 'session.web.site',
        'applicationClass' => \Joomla\CMS\Application\SiteApplication::class
    ],
    'administrator' => [
        'session' => 'session.web.administrator',
        'applicationClass' => \Joomla\CMS\Application\AdministratorApplication::class
    ],
    'cli' => [
        'session' => 'session.cli',
        'applicationClass' => \Joomla\Console\Application::class
    ]
];

$container = \Joomla\CMS\Factory::getContainer();
$container->alias('session', $contextConfig[$context]['session'])
    ->alias('JSession', $contextConfig[$context]['session'])
    ->alias(\Joomla\CMS\Session\Session::class, $contextConfig[$context]['session'])
    ->alias(\Joomla\Session\Session::class, $contextConfig[$context]['session'])
    ->alias(\Joomla\Session\SessionInterface::class, $contextConfig[$context]['session']);


$app = $container->get($contextConfig[$context]['applicationClass']);
\Joomla\CMS\Factory::$application = $app;

$db = \Joomla\CMS\Factory::getContainer()->get('DatabaseDriver');
$config = $app->getConfig();


function getParam(&$arr, $name, $def = null)
{
    return isset($arr[$name]) ? trim($arr[$name]) : $def;
}

function isValidInitialToken($db, string $token,string $device) : bool
{
    $query = $db->getQuery(true);
    $query
        ->select('*')
        ->from($db->quoteName('#__bieapilogin_tokens', 'd'))
        ->where($db->quoteName('d.device') . ' = ' . $db->quote($device))
        ->where($db->quoteName('d.state') . ' = ' . $db->quote(1));
    $db->setQuery($query);
    $item =  $db->loadObject();
    return (isset($item->token) && !empty($item->token) && (trim($item->token) == $token));
}

function validateLogin($db, string $username, string $password,$secret) : array
{
    /** @var \Joomla\CMS\User\UserFactoryInterface $userFactory */
    $userFactory = \Joomla\CMS\Factory::getContainer()->get(\Joomla\CMS\User\UserFactoryInterface::class);
    $user = $userFactory->loadUserByUsername($username);
    if ($user->id > 0 &&  isTokenEnabledForUser($db, $user->id) && \Joomla\CMS\User\UserHelper::verifyPassword($password, $user->password)) {
        return ['id'=>$user->id,
                    'name'=>$user->name,
                    'mail'=>$user->email,
                    'username'=>$user->username,
                    'token'=>getUserToken($user->id,getTokenSeedForUser($db, $user->id),$secret)
            ];
    }
    return [];
}


Function getUserToken(int $userId,string $tokenSeed,string $secret) : ? string {
    if (!empty($tokenSeed)) {
        $rawToken  = base64_decode($tokenSeed);
        $tokenHash = hash_hmac('sha256', $rawToken, $secret);
        return  base64_encode("sha256:$userId:$tokenHash");
    }
    return null;
}

function getTokenSeedForUser($db, int $userId): ?string
{
    try {
        $query = $db->getQuery(true)
            ->select($db->quoteName('profile_value'))
            ->from($db->quoteName('#__user_profiles'))
            ->where($db->quoteName('profile_key') . ' = :profileKey')
            ->where($db->quoteName('user_id') . ' = :userId');

        $profileKey = 'joomlatoken' . '.token';
        $query->bind(':profileKey', $profileKey,  \Joomla\Database\ParameterType::STRING);
        $query->bind(':userId', $userId, \Joomla\Database\ParameterType::INTEGER);

        return $db->setQuery($query)->loadResult();
    } catch (\Exception) {
        return null;
    }
}

/**
 * Is the token enabled for a given user ID? If the user does not exist or has no token it
 * returns false.
 *
 * @param   int  $userId  The User ID to check whether the token is enabled on their account.
 *
 * @return  boolean
 * @since   4.0.0
 */
function isTokenEnabledForUser($db,int $userId): bool
{
    try {
        $query = $db->getQuery(true)
            ->select($db->quoteName('profile_value'))
            ->from($db->quoteName('#__user_profiles'))
            ->where($db->quoteName('profile_key') . ' = :profileKey')
            ->where($db->quoteName('user_id') . ' = :userId');

        $profileKey = 'joomlatoken' . '.enabled';
        $query->bind(':profileKey', $profileKey, \Joomla\Database\ParameterType::STRING);
        $query->bind(':userId', $userId, \Joomla\Database\ParameterType::INTEGER);

        $value = $db->setQuery($query)->loadResult();

        return $value == 1;
    } catch (\Exception) {
        return false;
    }
}

function registerGetApiToken($db,int $userId, string $username, string $mail,string $token ) {
    $obj = new \stdClass();
    $obj->userid = $userId;
    $obj->username = $username;
    $obj->mail = $mail;
    $obj->token = $token;
    $obj->date = date('Y-m-d H:i:s');
    $result = $db->insertObject('#__bieapilogin_logs', $obj);


}
