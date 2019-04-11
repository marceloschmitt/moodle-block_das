<?php

class block_simplehtml_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', 'Teste');

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text', 'campo um');
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

    }
    ?>