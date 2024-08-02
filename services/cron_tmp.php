<?php

/**
 * Recursively delete a directory and its contents.
 *
 * @param string $dirPath The path to the directory to delete.
 * @return void
 */
function deleteDirectoryRecursively($dirPath) {
    if (!is_dir($dirPath)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            rmdir($item->getRealPath());
        } else {
            unlink($item->getRealPath());
        }
    }

    rmdir($dirPath);
}

/**
 * Find and delete directories containing 'TMP' in their name.
 *
 * @param string $baseDir The base directory to start the search.
 * @return void
 */
function deleteTmpDirectories($baseDir) {
    if (!is_dir($baseDir)) {
        // echo "The specified base directory does not exist.";
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isDir() && stripos($fileInfo->getFilename(), 'COMPLETED') !== false) {
            deleteDirectoryRecursively($fileInfo->getRealPath());
        }
    }
}

// Example usage
$baseDir = '../file_system/tmp/'; // Replace with your base directory
deleteTmpDirectories($baseDir);
?>
