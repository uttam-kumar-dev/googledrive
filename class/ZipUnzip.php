<?php
/**
 * This class is used to convert multiple files into zip and streams to browser byte by byte, 
 * It also responsible for unzip files
 * @author : Uttam Kumar
 */
class ZipUnzip{

    private $max_size  = '50MB';
    private $file_system = null;

    public function __construct(FileSystem $file_system){
        $this->file_system = $file_system;
    }

    public function zip(array $files, bool $download = false){
        
        if(empty($files)){
            throw new Exception('Files not empty');
        }

        foreach($files as $file){

            if(!file_exists($file)){
                throw new Exception('File not found -> '. $file);
            }

        }

    }

}