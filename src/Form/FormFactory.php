<?php

namespace Framework\Form;

use Symfony\Component\Form\Forms;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class FormFactory 
{
    private $csrfTokenManager;
    private $validator;

    private static $factory = NULL;

    private function __construct()
    {
    }

    /**
     * @return \Symfony\Component\Form\FormFactory 
     */
    public static function factory()
    {
        if(\is_null(self::$factory)){
            // Set up the CSRF Token Manager
            $csrfTokenManager = new CsrfTokenManager();

            // Set up the Validator component
            $validator = Validation::createValidator();

            $factory = Forms::createFormFactoryBuilder()
                ->addExtension(new CsrfExtension($csrfTokenManager))
                ->addExtension(new ValidatorExtension($validator))
                ->getFormFactory();

            self::$factory = $factory;
        }

        return self::$factory;
    }
    
    public function getCsrfTokenManager() 
    {
        return $this->csrfTokenManager;
    }

    public function getValidator() 
    {
        return $this->validator;
    }

    public function setCsrfTokenManager($csrfTokenManager) 
    {
        $this->csrfTokenManager = $csrfTokenManager;
        return $this;
    }

    public function setValidator($validator) 
    {
        $this->validator = $validator;
        return $this;
    }
}