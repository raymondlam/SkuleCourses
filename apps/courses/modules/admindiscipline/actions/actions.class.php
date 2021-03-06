<?php

/**
 * discipline actions.
 *
 * @package    SkuleCourses
 * @subpackage discipline
 * @author     Jason Ko, Jimmy Lu
 */
class admindisciplineActions extends sfActions
{
  public function preExecute(){
    if (!helperFunctions::isLoggedIn(sfContext::getInstance()->getRequest())) $this->redirect("siteadmin/login");

    // separator used for course_discipl assoc data
    $this->separator = "&&**&&";
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->enum_item_list = $this->getDisciplineList();
    $values=array('discipline'=>1);
    $this->form = new DisciplineForm(new Discipline(),$values);

    $this->getDisAssocListFromDB();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $values=array('discipline'=>1);
    $this->form = new DisciplineForm(new Discipline(),$values);

    $this->processForm($request, $this->form);

    // at this point, save has failed
    $this->enum_item_list = $this->getDisciplineList();
    $this->getDisAssocListFromPost($request);
    $this->setTemplate('index');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($discipline = DisciplinePeer::retrieveByPk($request->getParameter('id')), sprintf('Object discipline does not exist (%s).', $request->getParameter('id')));
    $this->enum_item_list = $this->getDisciplineList();
    $values=array('discipline'=>1);
    $this->form = new DisciplineForm($discipline,$values);
    $this->getDisAssocListFromDB($discipline);
    $this->setTemplate('index');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($discipline = DisciplinePeer::retrieveByPk($request->getParameter('id')), sprintf('Object discipline does not exist (%s).', $request->getParameter('id')));
    $values=array('discipline'=>1);
    $this->form = new DisciplineForm($discipline,$values);

    if ($request->hasParameter("page")){
      $par = "page=".$request->getParameter("page");
    }
    $this->redirectAddress = "admindiscipline/edit?".$par."&id=".$request->getParameter('id');

    $this->processForm($request, $this->form);
    $this->enum_item_list = $this->getDisciplineList();
    $this->getDisAssocListFromPost($request);
    $this->setTemplate('index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($discipline = EnumItemPeer::retrieveByPk($request->getParameter('id')), sprintf('Object enum_item does not exist (%s).', $request->getParameter('id')));

    try {
      $discipline->delete();

      $par="";
      if ($request->hasParameter("page")){
        $par = "?page=".$request->getParameter("page");
      }

      $this->redirect('admindiscipline/index'.$par);
    } catch (Exception $e){
      $this->globalErrors = $e->getMessage();
      $this->enum_item_list = $this->getDisciplineList();
      $values=array('discipline'=>1);
      $this->form = new DisciplineForm($discipline,$values);
      $this->getDisAssocListFromDB($discipline);
      $this->setTemplate('index');
    }
  }

  /* process the forms being submitted, do validation and saving */
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try{
        $discipline = $form->save();
        $this->parseDisAssoc($discipline, $request);

        $par="";
        if ($request->hasParameter("page")){
          $par = "&page=".$request->getParameter("page");
        }
        $this->redirect('admindiscipline/edit?id='.$discipline->getId().$par);
      } catch (Exception $e){
        $this->globalErrors = $e->getMessage();
      }
    }
  }

  protected function getDisciplineList(Criteria $c = null){
     
    $pagenumber = 1;
    if($this->getRequestParameter('page')!==null){
      $pagenumber = $this->getRequestParameter('page');
    }
    $pager = new sfPropelPager('Discipline', skuleadminConst::DISCIPLINE_RECORDNUMBER);
    if(!isset($c)){
      $c = new Criteria();
    }
    $c->addAscendingOrderByColumn(DisciplinePeer::DESCR);
    $pager->setCriteria($c);
    $pager->setPage($pagenumber);
    $pager->init();
    return $pager;
  }

  protected function getDisAssocListFromDB(Discipline $discipline=null){
    // declare empty array
    $this->assocData = array("1"=>"", "2"=>"", "3"=>"", "4"=>"");

    // get data from db
    if (isset($discipline)){
      $crit = new Criteria();
      $crit->addAscendingOrderByColumn(CourseDisciplineAssociationPeer::YEAR_OF_STUDY);
      $crit->addAscendingOrderByColumn(CourseDisciplineAssociationPeer::COURSE_ID);
      $rawList = $discipline->getCourseDisciplineAssociationsJoinCourse($crit);

      // parse out raw data string to client
      foreach ($rawList as $obj){
        $year = $obj->getYearOfStudy();
        $this->assocData[$year] .= $obj->getCourseId()." (".$obj->getCourse()->getDescr().")".$this->separator;
      }
    }
  }

  protected function getDisAssocListFromPost(sfWebRequest $request){
    $this->assocData = array(
      "1" => $request->getParameter("assoc[1]"),
      "2" => $request->getParameter("assoc[2]"),
      "3" => $request->getParameter("assoc[3]"),
      "4" => $request->getParameter("assoc[4]"));
  }

  /**
   * Save the course_discipline_assocs
   * @param $discipline
   * @param $request
   * @return true if ready for saving, false otherwise
   */
  protected function parseDisAssoc(Discipline $discipline, sfWebRequest $request){
    $conn = Propel::getConnection();

    // retrieve existing assoc objects
    $criteria = new Criteria();
    $criteria->addAscendingOrderByColumn(CourseDisciplineAssociationPeer::YEAR_OF_STUDY);
    $criteria->addAscendingOrderByColumn(CourseDisciplineAssociationPeer::COURSE_ID);
    $extObjs = $discipline->getCourseDisciplineAssociations($criteria, $conn);
    $delList = $extObjs;

    for ($year=1; $year<=4; $year++) {
      // first get an array of items
      $itemArr = array();
      $token = strtok($request->getParameter("assoc[".$year."]"), $this->separator);
      while ($token !== false){
        if (trim($token) != "") $itemArr[] = $token;
        $token = strtok($this->separator);
      }
       
      // check which ones exist, which ones are new and which ones need deletion
      foreach ($itemArr as $item){
        $cCode = substr($item, 0, 8);
        $existed = false;
        foreach ($extObjs as $obj){
          if ($obj->getCourseId() == $cCode && $obj->getYearOfStudy() == $year) {
            $existed = true;
            $key = array_search($obj, $delList);
            if ($key !== false) unset($delList[$key]);
            break;
          }
        }
         
        if (!$existed) {
          // save the new assoc
          $assoc = new CourseDisciplineAssociation();
          $assoc->setCourseId($cCode);
          $assoc->setDisciplineId($discipline->getId());
          $assoc->setYearOfStudy($year);
          $assoc->save($conn);
        }
      }
    }

    // delete old assocs that no longer exist
    foreach ($delList as $obj){
      $obj->delete($conn);
    }

    return true;
  }
}
