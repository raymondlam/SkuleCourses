<?php

/**
 * department actions.
 *
 * @package    sf_sandbox
 * @subpackage department
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class admindepartmentActions extends sfActions
{
  public function preExecute(){
    if (!helperFunctions::isLoggedIn(sfContext::getInstance()->getRequest())) $this->redirect("siteadmin/login");
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->department_list = $this->getDepartmentList();
    $this->form = new DepartmentForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new DepartmentForm();

    $this->processForm($request, $this->form);
    $this->department_list = $this->getDepartmentList();
    $this->setTemplate('index');
  }

  public function executeEdit(sfWebRequest $request)
  {
  	$this->forward404Unless($department = DepartmentPeer::retrieveByPk($request->getParameter('id')), sprintf('Object department does not exist (%s).', $request->getParameter('id')));
  	$this->department_list = $this->getDepartmentList();
    
    $values=array('edit'=>'true');
    $this->form = new DepartmentForm($department,$values);
    $this->setTemplate('index');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($department = DepartmentPeer::retrieveByPk($request->getParameter('id')), sprintf('Object department does not exist (%s).', $request->getParameter('id')));
    $this->form = new DepartmentForm($department);

    $this->redirectAddress = "admindepartment/edit?id=".$request->getParameter('id');
    
    $this->processForm($request, $this->form);
    $this->department_list = $this->getDepartmentList();
    $this->setTemplate('index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($department = DepartmentPeer::retrieveByPk($request->getParameter('id')), sprintf('Object department does not exist (%s).', $request->getParameter('id')));
    
    try {
      $department->delete();
      
      if ($request->hasParameter("page")){
        $par = "?page=".$request->getParameter("page");
      }
    
      $this->redirect('admindepartment/index'.$par);
    } catch (Exception $e) {
      $this->globalErrors = $e->getMessage();
      $this->department_list = $this->getDepartmentList();
      $this->form = new DepartmentForm($department);
      $this->setTemplate('index');
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try {
        $department = $form->save();
        
        $par="";
        if ($request->hasParameter("page")){
          $par = "&page=".$request->getParameter("page");
        }
        $this->redirect('admindepartment/edit?id='.$department->getId().$par);
      } catch (Exception $e) {
        $this->globalErrors = $e->getMessage();
      }      
    }
  }

  protected function getDepartmentList(Criteria $c = null){
  	
    $pagenumber = 1;
    if($this->getRequestParameter('page')!==null){
    	$pagenumber = $this->getRequestParameter('page');
    }
  	$pager = new sfPropelPager('Department', skuleadminConst::DEPARTMENT_RECORDNUMBER);
  	if(!isset($c)){
  	 $c = new Criteria();
  	 $c->addAscendingOrderByColumn(DepartmentPeer::ID);
  	}
    $pager->setCriteria($c);
    $pager->setPage($pagenumber);
    $pager->init();
    return $pager;
    //return DepartmentPeer::doSelect($c);
  }
}
