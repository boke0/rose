<?php

namespace Boke\Rose\Test;
use \Boke0\Rose\Container;
use \Boke0\Rose\CompositContainer;
use \PHPUnit\Framework\TestCase;

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
    public function __construct(a $a,b $b){
        $this->a=$a;
        $this->b=$b;
    }
}
class CompositContainerTest extends TestCase{
    public function buildContainer(){
        $composit=new CompositContainer();
        $containerA=new Container();
        $containerB=new Container();
        $composit->add($containerA);
        $composit->add($containerB);
        $containerA->add(a::class,function($c){
            return new a($c->get(b::class));
        });
        $containerA->add(c::class,function($c){
            return new c();
        });
        $containerB->add(b::class,function($c){
            return new b($c->get(c::class));
        });
        $containerB->add(d::class,function($c){
            return new d($c->get(a::class),$c->get(b::class));
        });
        return $composit;
    }
    public function testA(){
        $c=$this->buildContainer();
        return $this->assertEquals($c->get(a::class),new a(new b(new c())));
    }
    public function testB(){
        $c=$this->buildContainer();
        return $this->assertEquals($c->get(b::class),new b(new c()));
    }
    public function testC(){
        $c=$this->buildContainer();
        return $this->assertEquals($c->get(c::class),new c());
    }
    public function testD(){
        $c=$this->buildContainer();
        return $this->assertEquals($c->get(d::class),new d(new a(new b(new c())),new b(new c)));
    }
}
