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

use Doctrine\Common\Cache\ArrayCache as DoctrineArray;

/**
 *
 * @license     http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link        www.phraseanet.com
 */
class ArrayCache extends DoctrineArray implements Cache
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'array';
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
    public function get($id)
    {
        if ( ! $this->contains($id)) {
            throw new Exception(sprintf('Unable to find key %s', $id));
        }

        return $this->fetch($id);
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
