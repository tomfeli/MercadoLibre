<?php
interface dibujable
{
    public function dibujar();
}
interface nivelable
{
    public function getLevel();
}


class head implements dibujable,nivelable
{
    private $contenido= array();
    private $nivel=2;

    public function agregarContenido($cabeza)
    {
        if ($this->getLevel()<$cabeza->getLevel())
        {
            $this->contenido[]=$cabeza;
        }
    }
    public function dibujar()
    {
        echo "<head>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar(); 
        }
        echo "</head>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class title implements dibujable,nivelable
{
    private $contenido;
    private $nivel= 5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<title>". $this->contenido. "</title>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class body implements dibujable,nivelable
{
    private $contenido= array ();
    private $nivel=2;
    public function agregarContenido($cabeza)
    {
        if ($this->getLevel()<$cabeza->getLevel())
        {
            $this->contenido[]=$cabeza;
        }
    }
    public function dibujar()
    {
        echo "<body>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar(); 
        }
        echo "</body>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class header implements dibujable,nivelable
{
    private $contenido= array();
    private $nivel=3;

    public function agregarContenido( $content)
    {
        if ($this->getLevel()<$content->getLevel())
        {
            $this->contenido[]=$content;
        }
    }
    public function dibujar()
    {
        echo "<header>";
        foreach($this->contenido as $contenido)
        {
             $contenido->dibujar(); 
        }
        echo "</header>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class main implements dibujable,nivelable
{
    private $contenido= array ();
    private $nivel=3;

    public function agregarContenido( $content)
    {
        if ($this->getLevel()<$content->getLevel())
        {
            $this->contenido[]=$content;
        }
    }
    public function dibujar()
    {
        echo "<main>";
        foreach($this->contenido as $contenido)
        {
             $contenido->dibujar();
        }
        echo "</main>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class footer implements dibujable,nivelable
{
    private $contenido= array ();
    private $nivel=3;

    public function agregarContenido( $content)
    {
        if ($this->getLevel()<$content->getLevel())
        {
            $this->contenido[]=$content;
        }
    }
    public function dibujar()
    {
        echo "<footer>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar();
        }
        echo "</footer>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class p implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<p>". $this->contenido. "</p>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
} 
class h1 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<h1>". $this->contenido. "</h1>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class h2 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<h2>". $this->contenido. "</h2>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class h3 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        if ($this->getLevel()<$content->getLevel())
        {
            $this->contenido[]=$content;
        }
    }
    public function dibujar()
    {
        echo "<h3>". $this->contenido. "</h3>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class h4 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<h4>". $this->contenido. "</h4>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class h5 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<h5>". $this->contenido. "</h5>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class h6 implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<h6>". $this->contenido. "</h6>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class div implements dibujable,nivelable
{
    private $contenido= array ();
    private $nivel=3;
    public function agregarContenido(bodyable $content)
    {
        $this->contenido[]=$content;
    }
    public function dibujar()
    {
        echo "<div>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar(); 
        }
        echo "</div>";
    }
}

class li implements dibujable
{
    private $contenido;
    public function __construct($content)
    {
        $this->contenido=$content;
    }
    public function dibujar()
    {
        echo "<li>". $this->contenido. "</li>";
    }

}
class ul implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function agregarContenido(li $content)
    {
        $this->contenido[]=$content;
    }

    public function dibujar()
    {
        echo "<ul>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar();
        }
        echo "</ul>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }

}
class ol implements dibujable,nivelable
{
    private $contenido;
    private $nivel=5;
    public function agregarContenido(li $content)
    {
        $this->contenido[]=$content;
    }

    public function dibujar()
    {
        echo "<ol>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar();
        }
        echo "</ol>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}
class html implements dibujable,nivelable
{
    private $contenido= array ();
    private $nivel=1;
    public function __construct(head $head,body $body)
    {
        $this->contenido[0]=$head;
        $this->contenido[1]=$body;
    }
    public function dibujar()
    {
        echo "<html>";
        foreach($this->contenido as $contenido)
        {
            $contenido->dibujar(); 
        }
        echo "</html>";
    }
    public function getLevel()
    {
        return $this->nivel;
    }
}

