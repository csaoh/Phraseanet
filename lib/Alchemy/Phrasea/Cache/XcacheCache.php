<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Cache;

use Doctrine\Common\Cache\XcacheCache as DoctrineXcache;

/**
 *
 * @license     http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link        www.phraseanet.com
 */
class XcacheCache extends DoctrineXcache implements Cache
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'xcache';
    }

    /**
     * {@inheritdoc}
     */
    public function isServer()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isOnline()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ( ! $this->contains($key)) {
            throw new Exception('Unable to retrieve the value');
        }

        return $this->fetch($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMulti(array $keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return $this;
    }
}
