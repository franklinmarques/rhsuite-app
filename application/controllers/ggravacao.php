<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gravacao extends MY_Controller
{

    public function save_audio()
    {
        /*         * ************************************** Áudio *************************************** */

        foreach (array('audio') as $type) {
            if (isset($_FILES["audio-blob"])) {

                $fileName = $_POST["${type}-filename"];
                $uploadDirectory = './arquivos/media/' . $fileName;

                if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                    echo("Error");
                }

                exec("ffmpeg -i $uploadDirectory -ar 16000 $uploadDirectory");

                echo(base_url('arquivos/media/') . '/' . $fileName);
            }
        }
    }

    public function save_video()
    {
        /*         * ************************************** Vídeo *************************************** */

        // because we've different ffmpeg commands for windows & linux
        // that's why following script is used to fetch target OS
        $OSList = array
            (
            'Windows 3.11' => 'Win16',
            'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98' => '(Windows 98)|(Win98)',
            'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
            'Windows Server 2003' => '(Windows NT 5.2)',
            'Windows Vista' => '(Windows NT 6.0)',
            'Windows 7' => '(Windows NT 7.0)',
            'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME' => 'Windows ME',
            'Open BSD' => 'OpenBSD',
            'Sun OS' => 'SunOS',
            'Linux' => '(Linux)|(X11)',
            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS/2' => 'OS/2',
            'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );
        // Loop through the array of user agents and matching operating systems
        foreach ($OSList as $CurrOS => $Match) {
            // Find a match
            if (eregi($Match, $_SERVER['HTTP_USER_AGENT'])) {
                // We found the correct match
                break;
            }
        }

        // if it is audio-blob
        if (isset($_FILES["audio-blob"])) {
            $uploadDirectory = './arquivos/media/' . $_POST["filename"] . '.wav';
            if (!move_uploaded_file($_FILES["audio-blob"]["tmp_name"], $uploadDirectory)) {
                echo("Problema ao gravar arquivo de áudio!\n");
            } else {
                // if it is video-blob
                if (isset($_FILES["video-blob"])) {
                    $uploadDirectory = './arquivos/media/' . $_POST["filename"] . '.webm';
                    if (!move_uploaded_file($_FILES["video-blob"]["tmp_name"], $uploadDirectory)) {
                        echo("Problema ao gravar arquivo de vídeo!\n");
                    } else {
                        $audioFile = './arquivos/media/' . $_POST["filename"] . '.wav';
                        $videoFile = './arquivos/media/' . $_POST["filename"] . '.webm';
                        $mergedFile = './arquivos/media/' . $_POST["filename"] . '-final.webm';

                        // ffmpeg depends on yasm
                        // libvpx depends on libvorbis
                        // libvorbis depends on libogg
                        // make sure that you're using newest ffmpeg version!

                        if (!strrpos($CurrOS, "Windows")) {
                            $cmd = '-i ' . $audioFile . ' -i ' . $videoFile . ' -map 0:0 -map 1:0 ' . $mergedFile;
                        } else {
                            $cmd = ' -i ' . $audioFile . ' -i ' . $videoFile . ' -c:v mpeg4 -c:a vorbis -b:v 64k -b:a 12k -strict experimental ' . $mergedFile;
                        }

                        exec('ffmpeg ' . $cmd, $out, $ret);

                        if ($ret) {
                            echo "Problema na conversão do arquivos!\n";
                        } else {
                            echo "Arquivo convertido com sucesso!\n";

                            // Deletar arquivos
                            unlink($audioFile);
                            unlink($videoFile);
                        }
                    }
                }
            }
        }
    }

}
