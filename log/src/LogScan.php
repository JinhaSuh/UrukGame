<?php

namespace App;

class LogScan
{
    private array $files;
    private array $dirs;
    private array $exclude_files = array();

    public function addFiles($ff)
    {
        $this->files[] = $ff;
    }

    public function addDirs($dir)
    {
        $this->dirs[] = $dir;
    }

    public function addExcludeFile($ff)
    {
        $this->exclude_files[] = $ff;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getExcludedFiles()
    {
        return $this->exclude_files;
    }

    //dir의 모든 log 파일 읽기
    public function listDirFiles($dir)
    {
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        foreach ($ffs as $ff) {
            if (is_dir($dir . '/' . $ff)) {
                $this->addDirs($ff);
                $this->listDirFiles($dir . '/' . $ff);
            } else if (strpos($ff, "current") !== false) {
                $dir_name = substr($ff, 0, -8);
                exec("readlink /game/log/scribe/default_primary/" . $dir_name . "/" . $ff, $output, $error);
                $this->addExcludeFile($dir . '/' . $output[0]);
            } else {
                $this->addFiles($dir . '/' . $ff);
            }
        }

    }

}
