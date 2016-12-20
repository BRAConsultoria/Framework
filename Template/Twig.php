<?php
namespace Framework\Template;

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
    }
    
    public function render($template, $context) 
    {
        $twig = new \Twig_Environment($this->getLoader(), $this->getConf());

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