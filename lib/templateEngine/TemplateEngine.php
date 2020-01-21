<?php
namespace Library\templateEngine;
class TemplateEngine
{
private $template;
private $variables=array();
private $cierre;
private $apertura;
public function __construct($ruta,$apertura,$cierre)
{
    $this->template=file_get_contents($ruta); //devuelve la cadena de caracteres de un archivo
    $this->cierre=$cierre;
    $this->apertura=$apertura;
}
public function verificarQueNoLaHayanCagado()
{
    $texto=$this->template;
    //verificamos que el numero de aperturas sea igual al numero de cierres
    $contadorDeAperturas=0;
    for($i=0;$i<strlen($texto);$i++)
    {
        $flag=true;
        for($j=0;$j<strlen($this->apertura);$j++)
        {    
            if ($i+$j<strlen($texto))
            {             
                if($texto[$i+$j]!=$this->apertura[$j])
                {
                    $flag=false;
                }
            }
        }
        if($flag)
        {
            $contadorDeAperturas++;
        }    
    }
    $contadorDeCierres=0;
    for($i=0;$i<strlen($texto);$i++)
    {
        $flag=true;
        for($j=0;$j<strlen($this->cierre);$j++)
        {                
            if ($i+$j<strlen($texto))
            {             
                if($texto[$i+$j]!=$this->apertura[$j])
                {
                    $flag=false;
                }
            }
        }
        if($flag)
        {
            $contadorDeCierres++;
        }    
    }
    if($contadorDeAperturas==$contadorDeCierres)
    {
        return true;
    }
    else
    {
        return false;
    }
}
public function cuantasVariables()
{
    $listaDeVariables= array();
    $auxiliar="";
    $bool=true;
    $texto=$this->template;
    for ($i=0;$i<strlen($texto);$i++)
    {
        if ($bool)
        {
            $dentro=true;
            for ($j=0;$j<strlen($this->apertura);$j++)
            {
                if ($i+$j<strlen($texto))
                {
                    if ($texto[$i+$j]!=$this->apertura[$j])
                    {
                        $dentro=false;
                    }
                }        
            }
            if ($dentro)
            {
                $bool=false;
            }
        }
        else
        {
            $fuera=true;
            for ($j=strlen($this->cierre);$j>0;$j--)
            {
                if ($texto[$i-$j]!=$this->cierre[strlen($this->cierre)-$j])
                {
                    $fuera=false;
                }    
            }
            if ($fuera)
            {
                $bool=true;
                $flag=true;
                foreach($listaDeVariables as $value)
                {
                    if ($value==$auxiliar)
                    {
                        $flag=false;
                    }
                }
                if($flag)
                {
                    $listaDeVariables[]=$auxiliar;
                    
                }
                $auxiliar="";
            }
        }
        if (!$bool)
        {
        $auxiliar.=$texto[$i];
        }   
    }
    return count($listaDeVariables);
}
public function addVariable($variable,$dato)
{
  $this->variables[$variable]=$dato;
}
public function render()
{
    $texto=$this->template;
    foreach($this->variables as $key=>$value)
    {
        $texto=str_replace($this->apertura.$key.$this->cierre,$value,$texto); //reemplaza
    }
    //todo lo que sigue es por si el usuario decide no usar uno de los templates
    $auxiliar="";
    $bool=true;
    if ($this->apertura!=$this->cierre)
    {
        if($this->verificarQueNoLaHayanCagado())
        {
            for ($i=0;$i<strlen($texto);$i++)
            {
                if ($bool)
                {
                    $dentro=true;
                    for ($j=0;$j<strlen($this->apertura);$j++)
                    {
                        if($i+$j<strlen($texto))
                        {
                            if ($texto[$i+$j]!= $this->apertura[$j])
                            {
                                $dentro=false;
                            }
                        }        
                    }
                    if ($dentro)
                    {
                        $bool=false;
                    }
                }
                else
                {
                    $fuera=true;
                    for ($j=strlen($this->cierre);$j>0;$j--)
                    {
                        if ($texto[$i-$j]!=$this->cierre[strlen($this->cierre)-$j])
                        {
                            $fuera=false;
                        }    
                    }
                    if ($fuera)
                    {
                        $bool=true;
                    }
                }
                if ($bool)
                {
                    $auxiliar.=$texto[$i];
                }  
            }
            if($auxiliar!="")
            {
                return $auxiliar;
            }
        }
        else
        {
            return "tenes una apertura o cierre de mas,alguno la cago";
        }
   
    }
    else
        //programar si debe devolver error si el numero de apertura/cierres es impar 
        //en el caso de que no lo sean devolver el programa bien hecho
    {
        //contamos la cantidad de cierres y aperturas, en el caso de que sean iguales
        $contadorDeAperturasCierres=0;
        for($i=0;$i<strlen($texto);$i++)
        {
            $flag=true;
            for($j=0;$j<strlen($this->apertura);$j++)
            {   
                if($i+$j<strlen($texto))
                {             
                    if($texto[$i+$j]!=$this->apertura[$j])
                    {
                        $flag=false;
                    }
                }
            }
            if($flag)
            {
                $contadorDeAperturasCierres++;
            }    
        }
        if($contadorDeAperturasCierres%2==0)
        {
            $textoAux="";
            $fuera=true;  
            for($i=0;$i<strlen($texto);$i++)
            {       
                $flag=true;                    
                for($j=0;$j<strlen($this->apertura);$j++)
                {
                    if($i+$j<strlen($texto))
                    {
                        if($texto[$i+$j]!=$this->apertura[$j])
                        {
                            $flag=false;
                        }
                    }
                }
                if($flag)
                {
                    $fuera=!$fuera;
                }                
                if($fuera)
                {
                $textoAux.=$texto[$i];
                }    
            }
            //luego de haber revisado el texto los cierres van a a continuar en el texto auxiliar, se debe hacer una limpieza
            $textoLimpio="";
            for($i=0;$i<strlen($texto);$i++)
            {
                $esUnCierre=true;
                for($j=0;$j<strlen($this->apertura);$j++)
                {
                    if($i+$j<strlen($texto))
                    {
                        if($texto[$i+$j]!=$this->apertura[$j])
                        {
                            $esUnCierre=false;
                        }
                    }                    
                }
                if ($esUnCierre)
                {
                //mueve el indice y se saltea el cierre
                    $i+=(strlen($this->apertura)-1);
                }
                $textoLimpio.=$textoAux[$i];
            }
            return $textoLimpio;
        }
        else
        {
            return "me pusiste un numero distinto de aperuras y cierres LPM";
        }
    }
    
}
}

