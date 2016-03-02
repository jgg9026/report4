<?php
  require_once('../../config.php'); 
  require_once('report4_form.php');
  require_once('Student.php');
  require_once('course_report.php');
  GLOBAL $DB, $COURSE, $CFG;
  //print_object($CFG->prefix);

  $id = optional_param('id', 0, PARAM_INT);
  $selected = optional_param('selected', '', PARAM_TEXT);//component
  $selected_period = optional_param('selected_period','',PARAM_INT);
  $selected_variant = optional_param('selected_variant','', PARAM_INT);
  //$contextid = required_param('context_id',PARAM_INT);
  require_login();
  $PAGE->set_context(context_system::instance());
  //$PAGE->set_context(context::instance_by_id($contextid));
  $PAGE->set_title('Reporte 4');
  $PAGE->set_url('/blocks/report4/reporte4.php');
  $PAGE->requires->jquery();
  $urljs = new moodle_url('/blocks/report4/amd/src/hello.js');
  $PAGE->requires->js($urljs);
  if($selected=='')
  {
    $id=3;
  }
  else
  {
    $id=1;
  }
  $url = new moodle_url('/course/view.php', array('id' => 9));  
  $simplehtml = new report4_form();
  echo $OUTPUT->header();
  if($simplehtml->is_cancelled())
  {
    redirect($url);
  } 
  else if ($simplehtml->get_data())
  {
    $fromform=$simplehtml->get_data();

     $reporteurl = new moodle_url('/blocks/report4/reporte4.php', array('id'=>$id,'selected'=>$selected, 'selected_period'=>$selected_period, 'selected_variant'=>$selected_variant));
     redirect($reporteurl);
  }
  else
  {
    $site = get_site();
    $simplehtml->display();
     if($id==1)
    {

      $category = '%'.$selected.'%';
      course_report($category);      
    }
    elseif ($id==3)
    {
      $path1='/'.$selected_period.'/'.$selected_variant.'%';
      //echo('pacth');
      //print_object($path1);
      $cursos=$DB->get_records_sql('SELECT * from mdl_course_categories as course_categories where course_categories.coursecount != 0 and (select count(mdl_course.id) from mdl_course where mdl_course.category = course_categories.id and mdl_course.shortname like "%PA%" )!=0  and course_categories.path like ?', array($path1));
      foreach ($cursos as $curso)
      {
        $category = '%'.$curso->name.'%';
        course_report($category);
      }//end foreach courses

    }//end if id = 3

  }
  echo $OUTPUT->footer();  