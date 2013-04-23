<?php
/**
 * Simple information provider for an invited resource.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_Resource
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Resource
 */

/**
 * Simple information provider for an invited resource.
 *
 * Copyright 2010 Klarälvdalens Datakonsult AB
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see
 * http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html.
 *
 * @category Kolab
 * @package  Kolab_Resource
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Resource
 */
class Horde_Kolab_Resource_Itip_Resource_Base
implements Horde_Kolab_Resource_Itip_Resource
{
    /**
     * The mail address.
     *
     * @var string
     */
    private $_mail;

    /**
     * The common name.
     *
     * @var string
     */
    private $_common_name;

    /**
     * Constructor.
     *
     * @param string $mail        The mail address.
     * @param string $common_name The common name.
     */
    public function __construct($mail, $common_name)
    {
        $this->_mail        = $mail;
        $this->_common_name = $common_name;
    }

    /**
     * Retrieve the mail address of the resource.
     *
     * @return string The mail address.
     */
    public function getMailAddress()
    {
        return $this->_mail;
    }

    /**
     * Retrieve the common name of the resource.
     *
     * @return string The common name.
     */
    public function getCommonName()
    {
        return $this->_common_name;
    }

    /**
     * Retrieve the "From" address for this resource.
     *
     * @return string The "From" address.
     */
    public function getFrom()
    {
        return sprintf("%s <%s>", $this->_common_name, $this->_mail);
    }
}