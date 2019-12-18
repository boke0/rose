<?php

namespace Boke0\Rose;
use \Psr\Container\ContainerInterface;

class Container implements ContainerInterface{
    public function __construct(){
        $this->resolver=[];
        $this->entries=[];
        //$this->delegates=[];
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
        if(!$this->has($id)){
            throw new NotFoundException("エントリが登録されていません。");
        }
        if(!isset($this->entries[$id])){
            if(isset($this->resolver[$id])){
                $this->entries[$id]=$this->resolver[$id]($this);
            }
            //後にデリゲートコンテナの探索の処理を追加
        }
        return $this->entries[$id];
    }
    /**
     * エントリが登録されているか確認します。
     *
     * @param $id 確認するエントリ名
     * @return boolean エントリが存在するかどうか
     */
    public function has($id){
        if(isset($this->resolver[$id])) return TRUE;
        //後にデリゲートコンテナの探索の処理を追加
        return FALSE;
    }
}
