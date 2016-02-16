<?php
class block_report4 extends block_base {
    public function init() {
        $this->title = get_string('simplehtml', 'block_report4');
    }
    public function get_content() {
      if ($this->content !== null) {
        return $this->content;
      }
      $this->content         =  new stdClass;
      Global $DB, $COURSE, $PAGE;
      $array = explode('_',$COURSE->shortname);
      $showrecords = '';
      $context = context_course::instance($COURSE->id);
      $urlreporte = new moodle_url('/blocks/report4/reporte3.php',array('context_id'=>$this->context->id));
      $url = new moodle_url('/blocks/report4/reporte3.php');
      $showrecords.=html_writer::link($urlreporte,'reporte');
      $this->content->text   = $showrecords;
   

      if (! empty($this->config->text)) {
        $this->content->text = $this->config->text;
      }
      return $this->content;

    }
    public function instance_allow_multiple() {
    return false;
    }
    public function applicable_formats() {
    return array(
             'course-view' => true,
      'site'=>false,
      'my'=>false);
    }
}