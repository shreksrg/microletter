<?php

class FormValidation extends CCApplication
{
    static public function make()
    {
        $ci = self::ci_instance();
        $ci->load->library('form_validation');
        return $ci->form_validation;
    }
}