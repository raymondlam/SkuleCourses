<?php

/**
 * maintenance actions.
 *
 * @package    skule_courses\
 * @subpackage maintenance
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class maintenanceActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if (helperFunctions::isLoggedIn())
    {
      
    }
    else
    {
      // redirect to log in page
    }
  }
}
