<?php
class FilePreview
{
    private $text_file_extension = ['txt', 'php', 'js', 'png', 'jpg'];
    private $image_file_extension = ['png', 'jpg', 'jpeg'];
    private $file_variable = ['{#FILENAME#}', '{#CONTENT#}', '{#DOWNLOAD_LINK#}'];
    private $tmp_path = DOC_PATH . 'file_system/tmp/';
    private $file;
    private $ext;
    private $file_obj;
    private $file_id;
    private $dir_mapping = [];
    private $mime_type;
    private $db;
    public function __construct($file, $file_obj, $db)
    {
        $this->file = $file;
        $this->ext = $file_obj->file_extension;
        $this->file_obj = $file_obj;
        $this->file_id = $file_obj->uuid;
        $this->mime_type = $file_obj->file_type;
        $this->db = $db;
    }

    private function is_previewable()
    {

        return in_array($this->ext, $this->text_file_extension) || in_array($this->ext, $this->image_file_extension);
    }
    public function canPreview()
    {

        return $this->is_previewable();
    }

    public function isImageFile()
    {
        return in_array($this->ext, $this->image_file_extension);
    }

    public function  isTextFile()
    {
        return in_array($this->ext, $this->text_file_extension);
    }

    private function get_file_data()
    {
        $file_name = str_replace('FOLDER_ID_'.$this->file_obj->folder_id.'_','',basename($this->file));
        if ($this->isImageFile()) {
            $content = '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($this->file)) . '"/>';
        } else if ($this->isTextFile()) {
            $content = '<pre>' . htmlspecialchars(file_get_contents($this->file)) . '</pre>';
        }

        $download_url = BASE_URL . 'd/' . $this->file_id . '?d=1';

        return [$file_name, $content, $download_url]; //the sequence as it is with $this->file_variable;
    }

    private function copyFiles($folder_id, $child_folder, $dirname)
    {

        $check_files = $this->db::for_table('files')->where('is_deleted', 0)->where('folder_id', $folder_id)->find_many();

        foreach ($check_files as $file) {

            $user_dir = 'user_' . $this->file_obj->user_id . '/';

            $file_prefix = 'FOLDER_ID_' . $folder_id . '_';

            if (file_exists(DOC_PATH . 'file_system/' . $user_dir . $file_prefix . $file->title)) {
                copy(DOC_PATH . 'file_system/' . $user_dir . $file_prefix . $file->title, $dirname . '/' . $child_folder . $file->title);
            }
        }
    }

    private function buildPath($file_obj)
    {

        $parent_folder = $this->file_obj->id;

        $path = explode('/' . $parent_folder . '/', $file_obj->path)[1];

        $dir_structure = '';
        foreach (explode('/', $path) as $p) {
            $dir_structure .= $this->dir_mapping[$p] . '/';
        }

        return $this->dir_mapping[$parent_folder] . '/' . $dir_structure;
    }

    private function recursiveTraversal($folder_id, $dirname)
    {
        $directories = $this->db::for_table('folders')->where('parent_id', $folder_id)->where('is_deleted', 0)->find_many();

        foreach ($directories as $dir) {

            $this->dir_mapping[$dir->id] = $dir->title;

            $child_folder = $this->buildPath($dir);

            if (!file_exists($dirname . '/' . $child_folder)) {
                mkdir($dirname . '/' . $child_folder, 0755, true);
            }

            $this->copyFiles($dir->id, $child_folder, $dirname);

            $this->recursiveTraversal($dir->id, $dirname);
        }
    }

    private function manageFileOrFolder()
    {

        if (!isset($this->file_obj->file_type)) { //means this is folder, so we need to zip the files and folders;

            //now start with creating temproray directory with parant folder name;
            $dirname = $this->tmp_path . $this->file_id . '_PENDING'; //this is the main directory
            if (file_exists($dirname)) {
                $dirname .= '_' . rand(10000, 9999999);
            }
            mkdir($dirname . '/', 0755, true);

            $folder = $this->db::for_table('folders')->where('id', $this->file_obj->id)->find_one();
            $child_folder = $folder->title . '/';

            mkdir($dirname . '/' . $child_folder, 0755, true);

            $this->copyFiles($folder->id, $child_folder, $dirname);

            $this->dir_mapping[$folder->id] = $folder->title;

            $this->recursiveTraversal($folder->id, $dirname);

            $temp_dir = $dirname;
            $dirname = str_replace('PENDING', 'COMPLETED', $dirname . '/');
            rename($temp_dir . '/', $dirname);

            $this->buildZip($dirname, $folder->title);
        }
    }

    private function buildZip($dir, $filename){

        require_once 'Zipper.php';

        $zip = new Zipper($dir, $filename.'.zip');

        $zip->zipAndStreamDirectory();

    }
    
    private function fileDownload(){
        header('Content-Description: File Transfer');
        header('Content-Type: '.$this->file_obj->file_type);
        header('Content-Disposition: attachment; filename="'.basename($this->file_obj->title).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($this->file));

        readfile($this->file);
    }

    public function download()
    {
        if(!$this->is_dir()){
            $this->fileDownload();
            die;
        }

        $this->manageFileOrFolder();

    }

    private function buildDirectoryPreview($data){

        $fd_id = $this->file_obj->uuid;

        ob_start();

        include_once "../components/directory_preview.php";

        $data_render = ob_get_clean();

        echo str_replace($this->file_variable, ['ABCD', $data_render, 'OK'], $data);

    }

    private function is_dir() : bool {
        return !isset($this->file_obj->file_type);
    }
    

    public function buildPreviewOrDownload()
    {
        $data = file_get_contents(DOC_PATH . 'components/file_preview.html');

        if($this->is_dir()){
            $this->buildDirectoryPreview($data);
            die;
        }

        if(!$this->is_previewable()){
            $this->download();
        }

        $final_output = str_replace($this->file_variable, $this->get_file_data(), $data);

        if ($this->canPreview() && !isset($_GET['d'])) {
            echo $final_output;
            die;
        }

        if (isset($_GET['d']) && $_GET['d'] == 1) {
            $this->download();
        }
    }
}
