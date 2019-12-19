<?php

namespace Boke0\Rose;
use Psr\Container\ContainerInterface;

class CompositContainer implements ContainerInterface{
    public function __construct(){
        $this->children=[];
    }
    /**
     * コンテナの登録を行います。同時に、対象コンテナにデリゲート先として自身を登録します。
     * 
     * @param Container $container 追加対象のコンテナ
     */
    public function add(Container $container){
        $container->delegates($this);
        array_push($this->children,$container);
    }
    /**
     * 子コンテナからエントリの取得を行います。
     *
     * @param $id エントリのID
     * @throws NotFoundException どのコンテナからもエントリが見つからなかった場合のエラー
     * @return object
     */
    public function get($id){
        foreach($this->children as $child){
            if($child->has($id)) return $child->get($id);
        }
        throw new NotFoundException();
    }
    /**
     * 子コンテナにエントリが存在するかどうかの確認を行います。
     *
     * @param $id エントリのID
     * @return boolean エントリが存在するかどうか
     */
    public function has($id){
        foreach($this->children as $child){
            if($child->has($id)) return TRUE;
        }
        return FALSE;
    }
}
