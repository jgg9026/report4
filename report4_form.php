<?php
  //require_once("{$CFG->libdir}/formslib.php");
  require_once("$CFG->libdir/formslib.php");

 
  class report4_form extends moodleform {
 
    function definition() {
 
        $mform =& $this->_form;
        //----Period Selector
        $periods= array(''=>'Seleccione un periodo');
        $mform->addElement('select', 'period', 'Periodo:',$periods);
        //----Selected Period ID
        $mform->addElement('hidden', 'selected_period');
        $mform->setType('selected_period', PARAM_INT);
        //----Variant Selector
        //$variants= array(''=>'');
        $mform->addElement('select', 'variant','Seleccione una variante:');
        //----Variant ID Selected
        $mform->addElement('hidden','selected_variant');
        $mform->setType('selected_variant',PARAM_INT);
        //----key word for component
        $mform->addElement('text','filtro1', 'Palabra clave');
        $mform->addRule('filtro1', null, 'required', null, 'client');
        $mform->setType('filtro1', PARAM_TEXT);
        //----Component Selector
        $areas= array('0'=>'Seleccione un componente');
        $select=$mform->addElement('select', 'paralelo', 'Componente',$areas);
        //----Selected Component
        $mform->addElement('hidden','selected');
        $mform->setType('selected', PARAM_INT);

        //Disable form checker when reload 
        $mform->disable_form_change_checker();
      
  }
}