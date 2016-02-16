<?php
require_once('../../config.php');
 global $DB, $CFG;
 $records = $DB->get_records('user');
 

  $parametro= '%'.$_POST['1'].'%';
  //$cursos=$DB->get_records_sql('SELECT mdl_course.id, mdl_course.fullname, COUNT(DISTINCT mdl_user.id ) AS users FROM mdl_course, mdl_context, mdl_role_assignments, mdl_role, mdl_user WHERE mdl_context.instanceid = mdl_course.id AND mdl_context.id = mdl_role_assignments.contextid AND mdl_role.id = mdl_role_assignments.roleid AND mdl_user.id = mdl_role_assignments.userid and mdl_course.fullname like ? GROUP BY mdl_course.id', array($parametro));
  $cursos=$DB->get_records_sql('SELECT * from mdl_course_categories as course_categories where course_categories.coursecount != 0 and (select count(mdl_course.id) from mdl_course where mdl_course.category = course_categories.id and mdl_course.shortname like "%PA%" )!=0 and course_categories.name like ?', array($parametro));
  $datos=array();
  foreach ($cursos as $curso)
  {
    array_push($datos,$curso->name);
  }
  echo json_encode($datos);
