<?php 


class Log {
    private string $dirSrc;

    public function __construct()
    {   
        // I'm not letting the directory source to be changed
        // to keep it fixed in only one place.
        $this -> dirSrc = "./Log/data/";
    }

    public function read_file($filename): string {
        $path = $this -> dirSrc . $filename;
        $file = fopen($path, "r");
        $data = fread($file, filesize($path));
        fclose($file);
        return $data;
    }

    public function write_file(string $filename, string $text): void {
        $path = $this -> dirSrc . $filename;
        $file = fopen($path, "a+");
        fwrite($file, $text . "\n");
        fclose($file);
        return;
    }

    public function getSourceDirectory(): string {
        return $this -> dirSrc;
    }

}

?>