<?php
namespace Framework\Template;

use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\Translator;

class Twig
{
    private $loader;

    private $mainTemplatePath;

    /**
    * @var array templates Paths
    */
    private $paths = [];
    
    private $conf;
    
    private $debug;

    public function __construct(array $conf = []) 
    {
        $debug      = (isset($conf['debug']) ?: false);

        $this->setMainTemplatePath(\APP_ROOT . 'Template'. \DIRECTORY_SEPARATOR . 'views');
        $this->setConf($conf);
        $this->setDebug($debug);

        $this->addPath($this->getMainTemplatePath());
        $this->addPath(\VENDOR_TWIG_BRIDGE_DIR . '/Resources/views/Form');
    }

    public function render($template, $context) 
    {
        $twigEnv = new \Twig_Environment($this->getLoader(), $this->getConf());

        if(isset($this->conf['forms']) === true){
            $twig = $this->setFormExtensions($twigEnv);
        } else {
            $twig = $twigEnv;
        }
        
        if($this->getDebug() === false){
            return $twig->render($template, $context);
        } else {
            return $twig->render('json_encode.twig', ['data' => $context]);
        }
    }
    
    public function loader($paths = []) 
    {
        if(\is_array($paths) === true and \count($paths)){
            foreach($paths as $path) {
                if(\is_dir($path) === true){
                    $this->addPath($path);
                }
            }
        } elseif(\is_array ($paths) === false and \is_dir($paths) === true){
            $this->addPath($paths);
        }

        $this->setLoader(new \Twig_Loader_Filesystem($this->getPaths()));
        return $this;
    }
    
    private function setFormExtensions(\Twig_Environment $twig) 
    {
        $csrfTokenManager = new CsrfTokenManager();

        $translator = new Translator('en');
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', \VENDOR_FORM_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');
        $translator->addResource('xlf', \VENDOR_VALIDATOR_DIR . '/Resources/translations/validators.en.xlf', 'en', 'validators');

        $formEngine = new TwigRendererEngine([\DEFAULT_FORM_THEME]);
        $formEngine->setEnvironment($twig);

        $twig->addExtension(new TranslationExtension($translator));
        $twig->addExtension(
            new FormExtension(new TwigRenderer($formEngine, $csrfTokenManager))
        );
        
        return $twig;
    }
    
    private function addPath($path) 
    {
        \array_push($this->paths, $path);
    }

    public function getLoader() 
    {
        return $this->loader;
    }

    public function setLoader($loader) 
    {
        $this->loader = $loader;
        return $this;
    }
    
    private function getPaths() 
    {
        return $this->paths;
    }

    private function getMainTemplatePath() 
    {
        return $this->mainTemplatePath;
    }

    private function setMainTemplatePath($mainTemplatePath) 
    {
        $this->mainTemplatePath = $mainTemplatePath;
        return $this;
    }
    
    public function getDebug() 
    {
        return $this->debug;
    }

    public function setDebug($debug) 
    {
        $this->debug = $debug;
        return $this;
    }

    public function getConf()
    {
        return $this->conf;
    }

    public function setConf(array $conf) 
    {
        $this->conf = $conf;
        return $this;
    }
}