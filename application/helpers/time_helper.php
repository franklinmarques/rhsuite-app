<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * SecToTime
 *
 * Converte segundos em horas
 *
 * @access    public
 * @param string    timestamp
 * @param string    segundos
 * @return    string
 */
if (!function_exists('secToTime')) {
    function secToTime($timestamp = '', $mostrarSecs = true)
    {
        if (strlen($timestamp) == 0) {
            return $timestamp;
        }

        $seconds = intval($timestamp); //Converte para inteiro
        $negative = $seconds < 0 ? '-' : ''; //Verifica se é um valor negativo

        if ($negative) {
            $seconds = -$seconds; //Converte o negativo para positivo para poder fazer os calculos
        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours * 3600)) / 60);

        if (!$mostrarSecs) {
            return $negative . sprintf('%02d:%02d', $hours, $mins);
        }

        $secs = floor($seconds % 60);

        return $negative . sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }
}
// ------------------------------------------------------------------------

/**
 * timeToSec
 *
 * Converte horas em segundos
 *
 * @access    public
 * @param string    time
 * @return    string
 */
if (!function_exists('timeToSec')) {
    function timeToSec($time = '')
    {
        if (strlen($time) == 0) {
            return $time;
        }

        $arrTime = explode(':', $time);

        $horas = ($arrTime[0] ?? 0) * 3600;
        $minutos = ($arrTime[1] ?? 0) * 60;
        $segundos = ($arrTime[2] ?? 0);

        if ($horas < 0) {
            return $horas - $minutos - $segundos;
        }

        return $horas + $minutos + $segundos;
    }
}
