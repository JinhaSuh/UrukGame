<?php

namespace App;

class LogScan
{
    private array $files;
    private array $dirs;

    public function addFiles($ff)
    {
        $this->files[] = $ff;
    }

    public function addDirs($dir)
    {
        $this->dirs[] = $dir;
    }

    public function getFiles()
    {
        return $this->files;
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
            } else if (strpos($ff, "current") !== false)
                continue;
            else {
                $this->addFiles($dir . '/' . $ff);
            }
        }
    }

    //TODO : 특정 날짜의 log만 읽어들이기
}
