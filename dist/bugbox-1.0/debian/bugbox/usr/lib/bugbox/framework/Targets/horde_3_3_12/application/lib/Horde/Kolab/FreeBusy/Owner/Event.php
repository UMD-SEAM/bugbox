<?php
/**
 * This basic interface for a calendar owner.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_FreeBusy
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */

/**
 * This basic interface for a calendar owner.
 *
 * Copyright 2010 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see
 * http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html.
 *
 * @category Kolab
 * @package  Kolab_FreeBusy
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_FreeBusy
 */
interface Horde_Kolab_FreeBusy_Owner_Event
extends Horde_Kolab_FreeBusy_Owner
{
    /**
     * Indicates the correct free/busy server for the resource owner.
     *
     * @return string The server name.
     */
    public function getFreeBusyServer();

    /**
     * Return how many days into the past the free/busy data should be
     * calculated for this owner.
     *
     * @return int The number of days.
     */
    public function getFreeBusyPast();

    /**
     * Return how many days into the future the free/busy data should be
     * calculated for this owner.
     *
     * @return int The number of days.
     */
    public function getFreeBusyFuture();
}