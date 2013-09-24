<?php
/**
 * NextCMS (http://nextcms.org)
 *
 * @link        https://github.com/nghuuphuoc/nextcms
 * @author      Nguyen Huu Phuoc <phuoc@huuphuoc.me>
 * @copyright   Copyright (c) 2013 NextCMS
 * @license     MIT
 */

namespace User\Service;

use MongoId;
use MongoRegex;
use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class User implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * @var \User\Mapper\User
     */
    protected $userMapper;

    public function setUserMapper($userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    public function findById($userId)
    {
        if (null == $userId || '' == $userId) {
            return null;
        }
        return $this->userMapper->findOne(['_id' => new MongoId($userId)]);
    }

    public function find($criteria = [], $start = 0, $count = 20, $sortBy = ['user_name' => -1])
    {
        $criteria = $this->normalizeCriteria($criteria);
        return $this->userMapper->find($criteria, $start, $count, $sortBy);
    }

    public function count($criteria = [])
    {
        $criteria = $this->normalizeCriteria($criteria);
        return $this->userMapper->count($criteria);
    }

    protected function normalizeCriteria($criteria = [])
    {
        if (isset($criteria['keyword'])) {
            // Check if user want to look for users having the similar email address
            $field = (strpos($criteria['keyword'], '@') === false) ? 'user_name' : 'email';
            $criteria[$field] = new MongoRegex('/' . urldecode($criteria['keyword']) . '/i');

            unset($criteria['keyword']);
        }
        return $criteria;
    }

    /**
     * Activate/deactivate given user
     *
     * @param string $userId User's Id
     * @return bool
     */
    public function activate($userId)
    {
        $user = $this->findById($userId);
        if (null == $user) {
            return false;
        }
        $user->status = ('activated' == $user->status) ? 'deactivated' : 'activated';
        $this->userMapper->update($user->getProperties());
        $this->getEventManager()->trigger('activate.success', $this, ['user' => $user]);

        return true;
    }

    /**
     * Authenticate with given username and password
     *
     * @param string $username
     * @param string $password
     * @return \User\Entity\User|null
     */
    public function authenticate($username, $password)
    {
        $user = $this->userMapper->findOne(['user_name' => $username]);
        if (null == $user || $this->verifyPassword($password, $user->password) == false) {
            $this->getEventManager()->trigger('authenticate.error', $this, ['user' => $user]);
            return null;
        }

        $this->getEventManager()->trigger('authenticate.success', $this, ['user' => $user]);
        return $user;
    }

    /**
     * Create new user
     *
     * @param \User\Entity\User $user
     * @return \User\Entity\User|null
     */
    public function create($user)
    {
        // I have to ensure that there is no user with the same username or email
        if ($this->exist($user)) {
            return null;
        }

        if ($user->password) {
            // Encrypt the password
            $user->password = $this->encryptPassword($user->password);
        }

        $user->secret_key = $this->generateSecretKey();

        $user = $this->userMapper->create($user->getProperties());
        $this->getEventManager()->trigger('create.success', $this, ['user' => $user]);

        return $user;
    }

    /**
     * Encrypt the user's password
     *
     * @param string $password The original password
     * @return string
     */
    public function encryptPassword($password)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->create($password);
    }

    /**
     * Verify the user's password
     *
     * @param string $password The user's password, usually taken from the sign in form
     * @param string $encryptedPassword The encrypted password
     * @return bool
     */
    public function verifyPassword($password, $encryptedPassword)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->verify($password, $encryptedPassword);
    }

    /**
     * Generate secret key for each user
     *
     * @return string
     */
    public function generateSecretKey()
    {
        if (file_exists('/dev/urandom')) {
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100);
        } else {
            $randomData = mt_rand() . mt_rand() . mt_rand() . mt_rand() . microtime(true);
        }

        $randomData .= uniqid(mt_rand(), true);
        return hash('sha512', $randomData);
    }

    /**
     * Save user
     *
     * @param \User\Entity\User $user
     */
    public function save($user)
    {
        $this->userMapper->update($user->getProperties());
        $this->getEventManager()->trigger('save.success', $this, ['user' => $user]);
    }

    /**
     * Save user's password
     *
     * @param \User\Entity\User $user
     */
    public function savePassword($user)
    {
        // Encrypt the password
        $user->password = $this->encryptPassword($user->password);
        $this->userMapper->update($user->getProperties());
        $this->getEventManager()->trigger('savePassword.success', $this, ['user' => $user]);
    }

    /**
     * Check if there is user with given username or email address
     *
     * @param \User\Entity\User $user
     * @return bool
     */
    public function exist($user)
    {
        $criteria = $user->email ? ['email' => $user->email] : ['user_name' => $user->user_name];
        return ($this->userMapper->findOne($criteria) != null);
    }
}
