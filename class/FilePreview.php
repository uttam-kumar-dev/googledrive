<?php

require_once '../config/config.php';

class FilePreview
{
    private $text_file_extension = ['txt', 'php', 'js', 'png', 'jpg'];
    private $image_file_extension = ['png', 'jpg'];
    private $file_variable = ['{#FILENAME#}', '{#CONTENT#}', '{#DOWNLOAD_LINK#}'];
    private $tmp_path = DOC_PATH . 'file_system/tmp/';
    private $file;
    private $ext;
    private $file_obj;
    private $file_id;
    private $dir_mapping = [];
    private $mime_type;
    public function __construct($file, $file_obj)
    {
        $this->file = $file;
        $this->ext = $file_obj->extension;
        $this->file_obj = $file_obj;
        $this->file_id = $file_obj->uuid;
        $this->mime_type = $file_obj->file_type;
    }

    private function is_previewable()
    {

        return in_array($this->ext, $this->text_file_extension) || in_array($this->ext, $this->image_file_extension);
    }
    public function canPreview()
    {

        return $this->is_previewable();
    }

    private function isImageFile()
    {
        return in_array($this->ext, $this->image_file_extension);
    }

    private function  isTextFile()
    {
        return in_array($this->ext, $this->text_file_extension);
    }

    private function get_file_data()
    {
        $file_name = basename($this->file);

        if ($this->isImageFile()) {
            $content = '<img src="' . $this->file . '"/>';
        } else if ($this->isTextFile()) {
            $content = '<pre>' . file_get_contents($this->file) . '</pre>';
        }

        $download_url = BASE_URL . 'd/' . $this->file_id . '?d=1';

        return [$file_name, $content, $download_url]; //the sequence as it is with $this->file_variable;
    }

    private function copyFiles($folder_id, $child_folder, $dirname)
    {

        $check_files = ORM::for_table('files')->where('is_deleted', 0)->where('folder_id', $folder_id)->find_many();

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
        $directories = ORM::for_table('folders')->where('parent_id', $folder_id)->where('is_deleted', 0)->find_many();

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

            $folder = ORM::for_table('folders')->where('id', $this->file_obj->id)->find_one();
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

    public function download()
    {

        $this->manageFileOrFolder();

    }

    public function buildPreviewOrDownload()
    {
        $data = file_get_contents(DOC_PATH . 'components/file_preview.html');

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

$file_path = 'file_system/user_5/';
$file_obj = get_folder('b3d9ae39-80ec-4bc5-96af-32b3336c76fb', false);

$test = new FilePreview($file_path, $file_obj);

$test->download();
