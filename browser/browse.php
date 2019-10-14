<?php

/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */
// session_start();
if (!isset($_SESSION['KCFINDER'])) {
    ob_start();
    include('integration/codeigniter.php');
    ob_end_clean();
    $CI =& get_instance();
    $_SESSION['KCFINDER'] = $CI->session->userdata('KCFINDER');
}
  
require "core/autoload.php";
$browser = new browser();
$browser->action();
?>