<?php
require_once('../../config.php');
 global $DB, $CFG;
 $parametro= $_POST['1'];
  $variantes=$DB->get_records_sql('SELECT mdl_course_categories.id, mdl_course_categories.name from mdl_course_categories where mdl_course_categories.parent = ?;',array($parametro));

echo json_encode($variantes, JSON_UNESCAPED_UNICODE);