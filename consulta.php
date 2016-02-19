<?php
require_once('../../config.php');
 global $DB, $CFG;
 $records = $DB->get_records('user');

 $consult=$_POST['0'];
 
 $path=$_POST['2'].'%';
switch ($consult) {
    case 0:
          $parametro= '%'.$_POST['1'].'%';
          $cursos=$DB->get_records_sql('SELECT * from mdl_course_categories as course_categories where course_categories.coursecount != 0 and (select count(mdl_course.id) from mdl_course where mdl_course.category = course_categories.id and mdl_course.shortname like "%PA%" )!=0 and course_categories.name like ? and course_categories.path like ?', array($parametro,$path));
          $datos=array();
          foreach ($cursos as $curso)
          {
            array_push($datos,$curso->name);
          }
          echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        break;
    case 1:
    $parametro= $_POST['1'];
        $variantes=$DB->get_records_sql('SELECT mdl_course_categories.id, mdl_course_categories.name from mdl_course_categories where mdl_course_categories.parent = ?;',array($parametro));

        echo json_encode($variantes, JSON_UNESCAPED_UNICODE);
        break;
    case 2:
        $periodos=$DB->get_records_sql('SELECT mdl_course_categories.id, mdl_course_categories.name from mdl_course_categories where mdl_course_categories.parent = 0;');

        echo json_encode($periodos, JSON_UNESCAPED_UNICODE);
        break;
}





  
