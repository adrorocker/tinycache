<?php
/**
 * TinyCache.
 *
 * @link      https://github.com/adrorocker/tinycache
 *
 * @copyright Copyright (c) 2017 Adro Rocker
 * @author    Adro Rocker <alejandro.morelos@jarwebdev.com>
 */

namespace TinyCache\Adapter;

use Psr\Cache\CacheItemInterface;
use TinyCache\Collection;
use TinyCache\Hash;
use TinyCache\Item;

class FilesystemAdapter extends AbstractAdapter
{
    protected $directory;

    protected $collection;

    public function __construct($directory = null)
    {
        $this->directory = $directory;

        if (!$directory) {
            $this->directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tiny-cache';
            if (!file_exists($this->directory)) {
                @mkdir($this->directory, 0777, true);
            }
        }
        $this->collection = new Collection();
    }

    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *                    The key for which to return the corresponding Cache Item.
     *
     * @throws InvalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown.
     *
     * @return CacheItemInterface
     *                            The corresponding Cache Item.
     */
    public function getItem($key)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (strpos($file->getFilename(), $key) === 0) {
                $parts = explode('-', $file->getFilename());
                if ($parts[0] === $key) {
                    return new Item($key, file_get_contents($file->getRealPath()));
                }
            }
        }

        return new Item();
    }

    /**
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     *                       An indexed array of keys of items to retrieve.
     *
     * @throws InvalidArgumentException
     *                                  If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown.
     *
     * @return array|\Traversable
     *                            A traversable collection of Cache Items keyed by the cache keys of
     *                            each item. A Cache item will be returned for each key, even if that
     *                            key is not found. However, if no keys are specified then an empty
     *                            traversable MUST be returned instead.
     */
    public function getItems(array $keys = [])
    {
        $collection = new Collection();
        foreach ($keys as $key) {
            $item = $this->getItem($key);
            if ($item->isHit()) {
                $collection->set($item->getKey(), $item);
            }
        }

        return $collection;
    }

    /**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *                    The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown.
     *
     * @return bool
     *              True if item exists in the cache, false otherwise.
     */
    public function hasItem($key)
    {
    }

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     *              True if the pool was successfully cleared. False if there was an error.
     */
    public function clear()
    {
        $ok = true;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
            $ok = ($file->isDir() || @unlink($file) || !file_exists($file)) && $ok;
        }

        return $ok;
    }

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     *                    The key to delete.
     *
     * @throws InvalidArgumentException
     *                                  If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown.
     *
     * @return bool
     *              True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem($key)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (strpos($file->getFilename(), $key) === 0) {
                $parts = explode('-', $file->getFilename());
                if ($parts[0] === $key) {
                    return !file_exists($file->getRealPath()) || @unlink($file->getRealPath()) || !file_exists($file->getRealPath());
                }
            }
        }
    }

    /**
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *   An array of keys that should be removed from the pool.
     *
     *
     * @throws InvalidArgumentException
     *                                  If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *                                  MUST be thrown.
     *
     * @return bool
     *              True if the items were successfully removed. False if there was an error.
     */
    public function deleteItems(array $keys)
    {
        $ok = true;
        foreach ($keys as $key) {
            $ok = $this->deleteItem($key) && $ok;
        }

        return $ok;
    }

    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *                                 The cache item to save.
     *
     * @return bool
     *              True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item)
    {
        $name = $item->getKey().'-'.Hash::string($item->get());

        $path = $this->directory.DIRECTORY_SEPARATOR.$name;

        if (false === @file_put_contents($path, $item->get())) {
            return false;
        }

        return true;
    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *                                 The cache item to save.
     *
     * @return bool
     *              False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $this->collection->set($item->getKey(), $item);

        return $this;
    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *              True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit()
    {
        $ok = true;
        foreach ($this->collection->all() as $key => $item) {
            $ok = $this->save($item) && $ok;
        }

        return $ok;
    }
}
