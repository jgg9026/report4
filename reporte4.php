<?php
  require_once('../../config.php'); 
  require_once('report4_form.php');
  require_once('Student.php');
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
    $id=0;
  }
  else
  {
    $id=1;
  }
  if($id!=0){
      // echo('el valor de select:');
      // print_object($selected);
    echo('periodo');
    print_r($selected_period);
    echo('variant');
    print_r($selected_variant);
      $category = '%'.$selected.'%';
      $results = $DB->get_records_sql("SELECT {$CFG->prefix}grade_grades.id,{$CFG->prefix}user.id as student_id,
           {$CFG->prefix}user.firstname, {$CFG->prefix}user.lastname,{$CFG->prefix}course.fullname as course_name, 
           {$CFG->prefix}course.id as course_id,
           {$CFG->prefix}grade_items.itemname, {$CFG->prefix}grade_grades.finalgrade 
           FROM {$CFG->prefix}course, {$CFG->prefix}context, {$CFG->prefix}role_assignments, {$CFG->prefix}role, 
           {$CFG->prefix}user, {$CFG->prefix}grade_items, {$CFG->prefix}grade_grades, {$CFG->prefix}course_categories 
           WHERE {$CFG->prefix}context.instanceid = {$CFG->prefix}course.id 
           and {$CFG->prefix}course.id = {$CFG->prefix}grade_items.courseid 
           AND {$CFG->prefix}context.id = {$CFG->prefix}role_assignments.contextid 
           AND {$CFG->prefix}role.id = {$CFG->prefix}role_assignments.roleid 
           and {$CFG->prefix}course_categories.id = {$CFG->prefix}course.category
           and {$CFG->prefix}course_categories.name like ?
           and {$CFG->prefix}role.id = 5 
           AND {$CFG->prefix}user.id = {$CFG->prefix}role_assignments.userid 
           and {$CFG->prefix}user.id={$CFG->prefix}grade_grades.userid 
           and {$CFG->prefix}grade_items.id = {$CFG->prefix}grade_grades.itemid    
           order by {$CFG->prefix}user.lastname ASC
           , {$CFG->prefix}grade_items.itemname ASC", array($category));
  //print_object($results);
  }
  $url = new moodle_url('/course/view.php', array('id' => 9));  
  $simplehtml = new report4_form();
  //$toform['context_id']=$contextid;
  //$toform['selected']=$selected;
  //$simplehtml->set_data($toform);
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
    // $cursos=$DB->get_records_sql('SELECT {$CFG->prefix}course.id, {$CFG->prefix}course.fullname, 
    //   COUNT(DISTINCT {$CFG->prefix}user.id ) AS users 
    //   FROM {$CFG->prefix}course, {$CFG->prefix}context, {$CFG->prefix}role_assignments, {$CFG->prefix}role, {$CFG->prefix}user 
    //   WHERE {$CFG->prefix}context.instanceid = {$CFG->prefix}course.id 
    //   AND {$CFG->prefix}context.id = {$CFG->prefix}role_assignments.contextid 
    //   AND {$CFG->prefix}role.id = {$CFG->prefix}role_assignments.roleid 
    //   AND {$CFG->prefix}user.id = {$CFG->prefix}role_assignments.userid 
    //   and {$CFG->prefix}course.fullname like ? GROUP BY {$CFG->prefix}course.id', array($parametro));
    // foreach ($cursos as $curso)
    // {
     $reporteurl = new moodle_url('/blocks/report4/reporte4.php', array('id'=>$id,'selected'=>$selected));
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
      $path = '';
      //Obtener el nombre de todos los cursos de esa categoria
      $setcourses = $DB->get_records_sql("SELECT {$CFG->prefix}course.id, {$CFG->prefix}course.fullname, {$CFG->prefix}course_categories.path 
        FROM {$CFG->prefix}course, {$CFG->prefix}course_categories 
        where {$CFG->prefix}course_categories.id = {$CFG->prefix}course.category 
        and {$CFG->prefix}course_categories.name like ?", array($category));      
      //print_object($setcourses);
      //Obtener todos los alumnos de los diversos cursos
      foreach ($setcourses as $idcourse => $course)
      {
        $courses[$idcourse]=array();
        $path = $course->path;
        $studentscourse = $DB->get_records_sql("SELECT DISTINCT {$CFG->prefix}user.id AS userid, 
            {$CFG->prefix}user.lastname, {$CFG->prefix}user.firstname
            FROM {$CFG->prefix}course, {$CFG->prefix}context, {$CFG->prefix}role_assignments, {$CFG->prefix}role, {$CFG->prefix}user
            WHERE {$CFG->prefix}context.instanceid = {$CFG->prefix}course.id
            AND {$CFG->prefix}context.id = {$CFG->prefix}role_assignments.contextid
            AND {$CFG->prefix}role_assignments.roleid = 5
            AND {$CFG->prefix}user.id = {$CFG->prefix}role_assignments.userid
            AND {$CFG->prefix}course.id = ?",array($idcourse));        
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
      //Generar el path
      $strpath = '';
      $arraypath = explode('/',$path);
      //print_object($path);
      //print_object($arraypath);
      for($i = 1; $i < count($arraypath);$i++){
        $nameparent = $DB->get_record_sql('SELECT mdl_course_categories.name 
          from mdl_course_categories 
          where mdl_course_categories.id = ?',array($arraypath[$i]));
        $strpath.=$nameparent->name;     
        if($i!=count($arraypath)-1){
          $strpath.='/';     
        }
      }
      echo html_writer::tag('h3',$strpath);
      foreach($courses as $courseid => $students)
      {
        $tasks=$DB->get_records_sql("SELECT {$CFG->prefix}grade_items.itemname 
            FROM {$CFG->prefix}grade_items 
            WHERE {$CFG->prefix}grade_items.courseid = ?",array($courseid)); 
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