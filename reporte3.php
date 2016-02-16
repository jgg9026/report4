<?php
  require_once('../../config.php'); 
  require_once('report3_form.php');
  require_once('Student.php');
  GLOBAL $DB, $COURSE;

  $id = optional_param('id', 0, PARAM_INT);
  $selected = optional_param('selected', '', PARAM_TEXT);
  $contextid = required_param('context_id',PARAM_INT);
  require_login();
  $PAGE->set_context(context::instance_by_id($contextid));
  $PAGE->set_title('Reporte');
  $PAGE->set_url('/blocks/report4/reporte3.php');
  $PAGE->requires->jquery();
  $urljs = new moodle_url('/blocks/report4/amd/src/hello.js');
  $PAGE->requires->js($urljs);
  if($id!=0){
      // echo('el valor de select:');
      // print_object($selected);
      $category = '%'.$selected.'%';
      $results = $DB->get_records_sql('SELECT mdl_grade_grades.id,mdl_user.id as student_id,
           mdl_user.firstname, mdl_user.lastname,mdl_course.fullname as course_name, 
           mdl_course.id as course_id,
           mdl_grade_items.itemname, mdl_grade_grades.finalgrade 
           FROM mdl_course, mdl_context, mdl_role_assignments, mdl_role, 
           mdl_user, mdl_grade_items, mdl_grade_grades, mdl_course_categories 
           WHERE mdl_context.instanceid = mdl_course.id 
           and mdl_course.id = mdl_grade_items.courseid 
           AND mdl_context.id = mdl_role_assignments.contextid 
           AND mdl_role.id = mdl_role_assignments.roleid 
           and mdl_course_categories.id = mdl_course.category
           and mdl_course_categories.name like ?
           and mdl_role.id = 5 
           AND mdl_user.id = mdl_role_assignments.userid 
           and mdl_user.id=mdl_grade_grades.userid 
           and mdl_grade_items.id = mdl_grade_grades.itemid    
           order by mdl_user.lastname ASC
           , mdl_grade_items.itemname ASC', array($category));
  //print_object($results);
  }
  $url = new moodle_url('/course/view.php', array('id' => 9));  
  $simplehtml = new report3_form();
  $toform['context_id']=$contextid;
  //$toform['selected']=$selected;
  $simplehtml->set_data($toform);
  echo $OUTPUT->header();
  if($simplehtml->is_cancelled())
  {
    redirect($url);
  } 
  else if ($simplehtml->get_data())
  {
    $fromform=$simplehtml->get_data();
    // print_object($fromform);
    // $parametro= '%'.$fromform->filtro1.'%';
    // $cursos=$DB->get_records_sql('SELECT mdl_course.id, mdl_course.fullname, 
    //   COUNT(DISTINCT mdl_user.id ) AS users 
    //   FROM mdl_course, mdl_context, mdl_role_assignments, mdl_role, mdl_user 
    //   WHERE mdl_context.instanceid = mdl_course.id 
    //   AND mdl_context.id = mdl_role_assignments.contextid 
    //   AND mdl_role.id = mdl_role_assignments.roleid 
    //   AND mdl_user.id = mdl_role_assignments.userid 
    //   and mdl_course.fullname like ? GROUP BY mdl_course.id', array($parametro));
    // foreach ($cursos as $curso)
    // {
     $reporteurl = new moodle_url('/blocks/report4/reporte3.php', array('id'=>1, 'context_id'=>$contextid, 'selected'=>$selected));
     redirect($reporteurl);
    //   $reporte=html_writer::link($reporteurl,$curso->fullname);
    //   print_r($reporte);
    //   $salto='<br>';
    //   print_r($salto);
    // }
  }
  else
  {
    $site = get_site();
    $simplehtml->display();
     if($id!=0)
    {
      $students = array();
      $courses = array();
      //Obtener el nombre de todos los cursos de esa categoria
      $setcourses = $DB->get_records_sql('SELECT mdl_course.id, mdl_course.fullname 
        FROM mdl_course, mdl_course_categories 
        where mdl_course_categories.id = mdl_course.category 
        and mdl_course_categories.name like ?', array($category));      
      //print_object($setcourses);
      //Obtener todos los alumnos de los diversos cursos
      foreach ($setcourses as $idcourse => $course)
      {
        $courses[$idcourse]=array();
        $studentscourse = $DB->get_records_sql('SELECT DISTINCT mdl_user.id AS userid, 
            mdl_user.lastname, mdl_user.firstname
            FROM mdl_course, mdl_context, mdl_role_assignments, mdl_role, mdl_user
            WHERE mdl_context.instanceid = mdl_course.id
            AND mdl_context.id = mdl_role_assignments.contextid
            AND mdl_role_assignments.roleid = 5
            AND mdl_user.id = mdl_role_assignments.userid
            AND mdl_course.id = ?',array($idcourse));        
        foreach ($studentscourse as $idstudent => $student)
        {
          $courses[$idcourse][$idstudent] = new Student($student->firstname,
          $student->lastname);
        }
      }      
      //print_object($results);
      //Almacenar todas las notas de los cursos de la categoria
      foreach ($results as $idnote => $result)
      {
        $courseid = $result->course_id;
        $studentid = $result->student_id;        
        if($result->itemname=='')
        {
          $result->itemname = 'Nota Final';
        }
        if($result->finalgrade!=null)
        {
          $courses[$courseid][$studentid]->grades[$result->itemname] = round($result->finalgrade,0,PHP_ROUND_HALF_DOWN);                  
        }
        else
        {
          $courses[$courseid][$studentid]->grades[$result->itemname] = $result->finalgrade;                    
        }

      }      
      //Impresion de la informacion
      foreach($courses as $courseid => $students)
      {
        $tasks=$DB->get_records_sql('SELECT mdl_grade_items.itemname 
            FROM mdl_grade_items 
            WHERE mdl_grade_items.courseid = ?',array($courseid)); 
        //----Construyendo la tabla
        echo html_writer::tag('h2',$setcourses[$courseid]->fullname,array());
        $table = new html_table();
        $heads=array('Estudiante');
        foreach ($tasks as $task)
        {
          if($task->itemname!='')
          {
            array_push($heads,$task->itemname);
          }
        }
        array_push($heads, 'Nota Final');
        $table->head=$heads;
        //Iterar sobre los estudiantes del curso
        foreach ($students as $studentid => $student) {
          $person = $student->firstname.' '.$student->lastname;
          $data = array();
          array_push($data, $person);
          foreach ($heads as $head) {                      
            if($head!='Estudiante'){
              array_push($data, $student->grades[$head]);
            }
          }
          $table->data[]=$data;
        }
        echo html_writer::table($table);
      }      
    }    
  }
  echo $OUTPUT->footer();  