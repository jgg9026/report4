<?php
  //require_once("{$CFG->libdir}/formslib.php");
  require_once("$CFG->libdir/formslib.php");

 
  class report4_form extends moodleform {
 
    function definition() {
 
        $mform =& $this->_form;
        $mform->addElement('text','filtro1', 'Palabra clave');
        $mform->addRule('filtro1', null, 'required', null, 'client');
        $mform->setType('filtro1', PARAM_TEXT);

        $areas= array('0'=>'Seleccione un componente');
        $select=$mform->addElement('select', 'paralelo', 'Componente',$areas);
        $mform->addElement('hidden','selected');
        $mform->setType('selected', PARAM_INT);



        //$mform->addElement('hidden', 'context_id');
        //$mform->setType('context_id', PARAM_TEXT);
        //$this->add_action_buttons();
        $mform->disable_form_change_checker();
      
  }
}