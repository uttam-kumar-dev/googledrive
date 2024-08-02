<?php
class Zipper {
    private $zip;
    private $tempZipFile;
    private $zipFileName;
    private $tempFilePrefix;
    private $sourceDir;

    /**
     * Constructor to initialize the class with parameters.
     *
     * @param string $sourceDir The path to the directory to zip.
     * @param string $zipFileName The name of the ZIP file to be downloaded.
     * @param string $tempFilePrefix The prefix for the temporary file.
     */
    public function __construct($sourceDir, $zipFileName = 'directory.zip', $tempFilePrefix = 'zip_efedr') {
        $this->zip = new ZipArchive();
        $this->sourceDir = realpath($sourceDir); // Ensure sourceDir is an absolute path
        $this->zipFileName = $zipFileName;
        $this->tempFilePrefix = $tempFilePrefix;

        if (!$this->validateDirectory($this->sourceDir)) {
            throw new InvalidArgumentException('Invalid source directory: ' . $sourceDir);
        }
    }

    /**
     * Zip the directory and send it directly to the browser.
     *
     * @return void
     */
    public function zipAndStreamDirectory() {
        // Create a temporary file for the ZIP
        $this->tempZipFile = tempnam(sys_get_temp_dir(), $this->tempFilePrefix);

        if ($this->createInMemoryZip()) {
            // Send headers
            $this->sendHeaders();

            // Output the ZIP file content
            $this->outputZipFile();
        } else {
            echo 'Failed to create ZIP.';
        }

        // Clean up the temporary file
        unlink($this->tempZipFile);
    }

    /**
     * Create a ZIP file in a temporary location.
     *
     * @return bool Returns true on success or false on failure.
     */
    private function createInMemoryZip() {
        if ($this->zip->open($this->tempZipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return false;
        }

        $this->addDirectoryToZip($this->sourceDir);
        $this->zip->close();
        
        return true;
    }

    /**
     * Send headers for downloading the ZIP file.
     */
    private function sendHeaders() {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $this->zipFileName . '"');
        header('Content-Length: ' . filesize($this->tempZipFile));
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    /**
     * Output the ZIP file content.
     */
    private function outputZipFile() {
        // Clear any previous output buffers
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Output the content of the ZIP file
        readfile($this->tempZipFile);
    }

    /**
     * Validate that the source directory exists and is a directory.
     *
     * @param string $sourceDir The path to the source directory.
     * @return bool Returns true if valid, false otherwise.
     */
    private function validateDirectory($sourceDir) {
        return $sourceDir !== false && is_dir($sourceDir);
    }

    /**
     * Add all files and subdirectories from the source directory to the ZIP file.
     *
     * @param string $sourceDir The path to the source directory.
     */
    private function addDirectoryToZip($sourceDir) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $this->addFileToZip($file, $sourceDir);
            }
        }
    }

    /**
     * Add a single file to the ZIP archive.
     *
     * @param SplFileInfo $file The file to add.
     * @param string $sourceDir The path to the source directory.
     */
    private function addFileToZip(SplFileInfo $file, $sourceDir) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($sourceDir) + 1);
        $this->zip->addFile($filePath, $relativePath);
    }
}

?>
