<?php
require_once('../../config.php');
 global $DB, $CFG;
 
  $periodos=$DB->get_records_sql('SELECT mdl_course_categories.id, mdl_course_categories.name from mdl_course_categories where mdl_course_categories.parent = 0;');

echo json_encode($periodos, JSON_UNESCAPED_UNICODE);