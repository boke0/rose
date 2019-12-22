<?php

namespace Boke0\Rose;
use \Psr\Container\ContainerInterface;

class Container implements ContainerInterface{
    private $delegates;
    public function __construct(){
        $this->resolver=[];
        $this->entries=[];
    }
    /**
     * エントリーを追加します。
     * PSR準拠のエントリーはインターフェース名をIDに指定してください。
     *
     * @param string $id
     * @param callable $function
     * @throws ContainerException エントリがすでに存在している場合のエラー
     */
    public function add($id,$function){
        if($this->has($id)){
            throw new ContainerException("すでにエントリが登録されています");
        }
        $this->resolver[$id]=$function;
    }
    /**
     * 登録済みのエントリーの取得を行います。
     *
     * @param $id 取得するエントリ名
     * @throws NotFoundException エントリが存在しない場合のエラー
     * @return object 解決済みインスタンス
     */
    public function get($id){
        if(isset($this->resolver[$id])){
            return $this->resolver[$id]($this);
        }else if($this->delegates!=NULL&&$this->delegates->has($id)){
            return $this->delegates->get($id);
        }
        throw new NotFoundException("エントリが登録されていません。");
    }
    /**
     * エントリが登録されているか確認します。
     *
     * @param $id 確認するエントリ名
     * @return boolean エントリが存在するかどうか
     */
    public function has($id){
        if(isset($this->resolver[$id])){
            return TRUE;
        }
    }
    /**
     * デリゲートコンテナの登録を行います。
     *
     * @param ContainerInterface $container コンポジットコンテナ
     */
    public function delegates(CompositContainer $container){
        $this->delegates=$container;
    }
}
