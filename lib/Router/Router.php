<?php
namespace Library\Router;
class Router
{
    private $rutas= array();

    public function addRout($path,\Library\Controllers\Controller $target)
    {
        if (!array_key_exists("#".$path."#m",$this->rutas))
        {
            $this->rutas["#".$path."#m"]=$target;
            return true;
        }
        return false;
    }
    public function matchRout($path)
    {
        foreach($this->rutas as $regex=>$target)
        {
            
            $r=preg_match_all($regex,$path);
            if($r>0)
            {
                return $target;
            }
        }
        return null;
    }
}