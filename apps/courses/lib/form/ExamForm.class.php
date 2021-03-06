<?php

/**
 * Exam form.
 *
 * @package    sf_sandbox
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class ExamForm extends BaseExamForm
{	
  protected static $terms = array("9"=>"Fall", "1"=>"Winter", "5"=>"Summer");
  protected static function getYears(){
    $date = getdate();
    $yearsArr = array();
    
    for ($i=$date["year"]+1; $i>=skuleadminConst::EARLIEST_YEAR; $i--){
      $yearsArr[$i]=$i;
    }
    
    return $yearsArr;
  }
  
  public function configure()
  {
  	$c= new Criteria();
    $c->add(EnumItemPeer::PARENT_ID, EnumItemPeer::EXAM_TYPES_NODE_ID);

    //unset($this->year);
    //$this->unset['year'];
    
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'course_id' => new sfWidgetFormInputHidden(),
      'type'      => new sfWidgetFormPropelChoice(array('model' => 'EnumItem', 'add_empty' => false,'criteria' => $c)),
      'year'      => new sfWidgetFormChoice(array('choices' =>  self::getYears())),
      'term'      => new sfWidgetFormChoice(array('choices' =>  self::$terms)),
      //'year'      => new sfWidgetFormInputHidden(),
      'descr'     => new sfWidgetFormInput(),
      'file_path' => new sfWidgetFormInputFile(),
    ));
    
    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'Exam', 'column' => 'id' )),
      'course_id' => new sfValidatorPropelChoice(array('model' => 'Course', 'column' => 'id')),
      'type'      => new sfValidatorPropelChoice(array('model' => 'EnumItem', 'column' => 'id')),
      'year'      => new sfValidatorChoice(array('choices' =>  array_keys(self::getYears()))),
      'term'	  => new sfValidatorChoice(array('choices' =>  array_keys(self::$terms))),
      //'year'      => new sfValidatorInteger(),
      'descr'     => new sfValidatorString(array('max_length' => 255)),
      'file_path' => new sfValidatorFile(array('required' => false)),
    ));
    
    $this->widgetSchema->setNameFormat('exam[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }
}
