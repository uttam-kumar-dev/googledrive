<?php

class FilePreview{
    private $text_file_extension = ['txt', 'php', 'js', 'png', 'jpg'];
    private $image_file_extension = ['png', 'jpg'];
    private $file_variable = ['{#FILENAME#}', '{#CONTENT#}', '{#DOWNLOAD_LINK#}'];
    private $file;
    private $ext;
    private $file_id;
    public function __construct($file, $extension, $file_uuid)
    {
        $this->file = $file;
        $this->ext = $extension;
        $this->file_id = $file_uuid;
    }

    private function is_previewable(){

        return in_array($this->ext, $this->text_file_extension) || in_array($this->ext, $this->image_file_extension);

    }
    public function canPreview(){

        return $this->is_previewable();
    }

    private function isImageFile(){
        return in_array($this->ext, $this->image_file_extension);
    }

    private function  isTextFile() {
        return in_array($this->ext, $this->text_file_extension);
    }

    private function get_file_data(){
        $file_name = basename($this->file);

        if($this->isImageFile()){
            $content = '<img src="'.$this->file.'"/>';
        }else if($this->isTextFile()){
            $content = '<pre>'.file_get_contents($this->file).'</pre>';
        }

        $download_url = BASE_URL.'d/'.$this->file_id.'?d=1';

        return [$file_name, $content, $download_url]; //the sequence as it is $this->file_variable;
    }
    public function buildPreview(){
        $data = file_get_contents(DOC_PATH.'components/file_preview.html');

        $final_output = str_replace($this->file_variable, $this->get_file_data(), $data);

        echo $final_output;
        die;
    }
}