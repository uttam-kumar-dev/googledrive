<?php

class AccessControl{

    private $user_id = null;
    private $file_obj;

    public const YES = 1;
    public const NO = 0;
    public const AUTHENTICATION_NEEDED = 2;
    public const PRIVATE_FILE = 3;
    public $original_user = null;

    public function __construct($file_obj, $user_id)
    {
        $this->user_id = $user_id;
        $this->file_obj = $file_obj;
    }

    private function is_owner(){

        return $this->file_obj->user_id == $this->user_id;
    }

    private function have_permission(){

        $file = ORM::for_table('file_sharing_access')->where('file_id', $this->file_obj->uuid)->find_one();


        if(!$file) return false;

        $this->original_user = $file->user_id;

        if($file->share_with == 0){
            return true;
        }

        return $file->share_with == $this->user_id ? true : self::AUTHENTICATION_NEEDED;

    }

    public function canRead(){

        if($this->is_owner()){
            return true;
        }

        if($this->file_obj->is_private == 1){
            return self::PRIVATE_FILE;
        }

        return $this->have_permission();

    }

    public function get_user_id(){
        return $this->original_user;

    }

}