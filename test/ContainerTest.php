<?php

namespace Boke0\Rose\Test;
use \Boke0\Rose\Container;
use \PHPUnit\Framework\TestCase;
use \Mockery;

class a{
    public function __construct(b $b){
        $this->b=$b;
    }
}
class b{
    public function __construct(c $c){
        $this->c=$c;
    }
}
class c{}
class d{
    public function __construct($a,$b){
        $this->a=$a;
        $this->b=$b;
    }
}
class ContainerTest extends TestCase{
    public function buildContainer(){
        $container=new Container();
        $container->add(a::class,function($con){
            return new a($con->get(b::class));
        });
        $container->add(b::class,function($con){
            return new b($con->get(c::class));
        });
        $container->add(c::class,function($con){
            return new c();
        });
        $container->add(d::class,function($con){
            return new d($con->get(a::class),$con->get(b::class));
        });
        return $container;
    }
    public function testA(){
        $container=$this->buildContainer();
        return $this->assertEquals($container->get(a::class),new a(new b(new c())));
    }
    public function testB(){
        $container=$this->buildContainer();
        return $this->assertEquals($container->get(b::class),new b(new c()));
    }
    public function testC(){
        $container=$this->buildContainer();
        return $this->assertEquals($container->get(c::class),new c());
    }
    public function testD(){
        $container=$this->buildContainer();
        return $this->assertEquals($container->get(d::class),new d(new a(new b(new c())),new b(new c())));
    }
}
