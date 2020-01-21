<?php
require_once("../../vendor/autoload.php");
require_once("TemplateEngine.php");
use PHPUnit\Framework\TestCase;
final class TestTemplateEngine extends TestCase
{
 function testAndaTodo()
 {
     $this->assertTrue(True);
 }
 function testExisteTemplateEngine()
 {
     $this->assertTrue(class_exists("TemplateEngine"));
 }
 function testCuantasVariables()
 {
    $te= new TemplateEngine("index.template","{{","}}");
    $cantDeVariables=$te->cuantasVariables();
    $this->assertEquals(4,$cantDeVariables);
 }
 function testCuantasVariablesConVariablesRepetidas()
 {
    $te= new TemplateEngine("indexConVariableRepetida","{{","}}");
    $cantDeVariables=$te->cuantasVariables();
    $this->assertEquals(3,$cantDeVariables);
 }
 function testVerificarQueNoLaHayanCagado()
 {
    $te= new TemplateEngine("indexCagado.template","{{","}}");
    $laCagaron=$te->verificarQueNoLaHayanCagado();
    $this->assertFalse($laCagaron);
 }
 
}