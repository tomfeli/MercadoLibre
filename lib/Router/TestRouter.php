<?php
require_once("../../vendor/autoload.php");
require_once("Router.php");
use PHPUnit\Framework\TestCase;
final class TestRouter extends TestCase
{
 function testAndaTodo()
 {
     $this->assertTrue(True);
 }
 function testExisteRouter()
 {
     $this->assertTrue(class_exists("Router"));
 }
 function testAddRoutExist()
 {
    $rout= new Router();
    $this->assertTrue(method_exists($rout,"addRout"));
 }
 function testMatchRoutExist()
 {
    $rout= new Router();
    $this->assertTrue(method_exists($rout,"matchRout"));
 }
 function testAddRout()
 {
     $rout= new Router();
     $rout->addRout("/auto/\d+/\w+\.html$","ferrari,toyota y muchos mas");
     $match=$rout->matchRout("/auto/2434/listaDeTipos.html");
     $this->assertEquals("ferrari,toyota y muchos mas",$match);
 }
 function testAddMasDeUno()
 {
    $rout= new Router();
    $rout->addRout("/auto/\w+/\w+\.html$","ferrari,toyota y muchos mas");
    $rout->addRout("/moto/\w+/\w+\.html$","harley,yamaha y muchos mas");
    $match=$rout->matchRout("/auto/nuwniwqno/hurbvw.html");
    $this->assertEquals("ferrari,toyota y muchos mas",$match);
    $match=$rout->matchRout("/moto/bcywv5556/cqidw.html");
    $this->assertEquals("harley,yamaha y muchos mas",$match);
 }
 function testAddDosKeysIguales()
 {
    $rout= new Router();
    $rout->addRout("/auto/\w+/\w+\.html$","ferrari,toyota y muchos mas");
    $rout->addRout("/auto/\w+/\w+\.html$","harley,yamaha y muchos mas");
    $match=$rout->matchRout("/auto/nuwniwqno/hurbvw.html");
    $this->assertEquals("ferrari,toyota y muchos mas",$match);
 }
}