<?php

namespace WPDeskFIVendor;

/**
 * Create MySQL lock.
 *
 * @param string $lockName Lock name.
 * @param int    $waitForLockTimeout Wait for lock timeout.
 *
 * @return \WPDesk\Mutex\WordpressMySQLLockMutex
 */
function wpdesk_create_mysql_lock($lockName, $waitForLockTimeout = 5)
{
    return new \WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex($lockName, $waitForLockTimeout);
}
/**
 * Create MySQL Lock from order.
 *
 * @param WC_Order $order
 * @param string $lockName
 * @param int $waitForLockTimeout
 *
 * @return \WPDesk\Mutex\WordpressMySQLLockMutex
 */
function wpdesk_create_mysql_lock_from_order(\WC_Order $order, $lockName = '_mutex', $waitForLockTimeout = 5)
{
    return \WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex::fromOrder($order, $lockName, $waitForLockTimeout);
}
/**
 * Acquire lock.
 *
 * @param string $lockName
 * @param int $waitForLockTimeout
 * @param string $lockType
 *
 * @return bool
 */
function wpdesk_acquire_lock($lockName, $waitForLockTimeout = 5, $lockType = 'mysql')
{
    if ('mysql' === $lockType) {
        $mutex = \WPDeskFIVendor\wpdesk_create_mysql_lock($lockName, $waitForLockTimeout);
        $storage = new \WPDeskFIVendor\WPDesk\Mutex\StaticMutexStorage();
        $storage->addToStorage($lockName, $mutex);
        return $mutex->acquireLock();
    }
}
/**
 * Release lock.
 *
 * @param string $lockNAme
 * @throws \WPDesk\Mutex\MutexNotFoundInStorage Exception.
 */
function wpdesk_release_lock($lockNAme)
{
    $storage = new \WPDeskFIVendor\WPDesk\Mutex\StaticMutexStorage();
    $mutex = $storage->getFromStorage($lockNAme);
    if (null !== $mutex) {
        $mutex->releaseLock();
        $storage->removeFromStorage($lockNAme);
    } else {
        throw new \WPDeskFIVendor\WPDesk\Mutex\MutexNotFoundInStorage();
    }
}
