<?php

class block_das_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', 'Usuarios ausentes');

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_beginoffirstgap', 'Inicio do primeiro intervalo');
        $mform->setDefault('config_beginoffirstgap', '0');
        $mform->setType('config_begindoffirstgap', PARAM_RAW);

        $mform->addElement('text', 'config_beginofsecondgap', 'Inicio do segundoi intervalo');
        $mform->setDefault('config_beginofsecondgap', '7');
        $mform->setType('config_begindofsecondgap', PARAM_RAW);

        $mform->addElement('text', 'config_beginofthirdgap', 'Inicio do terceiro intervalo');
        $mform->setDefault('config_beginofthirdgap', '11');
        $mform->setType('config_begindofthirdgap', PARAM_RAW);

        $mform->addElement('text', 'config_beginofforthgap', 'Inicio do quarto intervalo');
        $mform->setDefault('config_beginofforthgap', '60');
        $mform->setType('config_begindofforthgap', PARAM_RAW);
    }
    }
    ?>