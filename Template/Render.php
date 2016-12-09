<?php
namespace Main\Template;

class Render
{
    private $loader;

    private $mainTemplatePath;

    /**
    * @var array templates Paths
    */
    private $paths = [];
    
    private $cache;

    public function __construct($cache = false) 
    {
        $this->setMainTemplatePath(\APP_ROOT . 'Template'. \DIRECTORY_SEPARATOR . 'views');
        $this->setCache($cache);
        $this->addPath($this->getMainTemplatePath());
    }
    
    public function render($template, $context) 
    {
        $cache = ($this->getCache() === false ? [] : ['cache' => \CACHE_ROOT . \DIRECTORY_SEPARATOR . 'cache']);
        $twig = new \Twig_Environment($this->getLoader(), $cache);
        
        return $twig->render($template, $context);
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
    
    private function getCache() 
    {
        return $this->cache;
    }

    private function setCache($cache) 
    {
        $this->cache = $cache;
        return $this;
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
}