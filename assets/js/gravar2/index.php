<!DOCTYPE html>
<html lang="pt-br">

<head>
    <script src="RecordRTC.js"></script>
</head>
<body>
<section class="experiment">
    <div class="inner" style="height: 5em;">
        <audio id="audio" autoplay controls></audio>
        <button id="record-audio">Gravar</button>
        <button id="pause-resume-audio" disabled>Pausar</button>
        <button type="button" onclick="window.location.reload();">Limpar</button>
        <button id="stop-recording-audio" disabled>Salvar</button>
        <h2 id="audio-url-preview"></h2>
    </div>
</section>

<div id="container" style="display: none;"></div>

<script>
    (function () {
        var params = {},
            r = /([^&=]+)=?([^&]*)/g;

        function d(s) {
            return decodeURIComponent(s.replace(/\+/g, ' '));
        }

        var match, search = window.location.search;
        while (match = r.exec(search.substring(1)))
            params[d(match[1])] = d(match[2]);

        window.params = params;
    })();
</script>

<script>

    //Define Navegador
    var isFirefox = !!navigator.mozGetUserMedia;

    //Div armazena o áudio
    //var container = document.getElementById('container');
    var container = document.getElementById('container');

    //Variáveis de Áudio
    var audioStream;
    var recorder;

    var recordAudio = document.getElementById('record-audio'),
        pauseResumeAudio = document.getElementById('pause-resume-audio'),
        stopRecordingAudio = document.getElementById('stop-recording-audio');

    var audioConstraints = {
        audio: true,
        video: false
    };

    //Função do botão gravar
    recordAudio.onclick = function () {
        if (!audioStream)
            navigator.getUserMedia(audioConstraints, function (stream) {
                if (window.IsChrome) stream = new window.MediaStream(stream.getAudioTracks());
                audioStream = stream;

                // "audio" is a default type
                recorder = window.RecordRTC(stream, {
                    type: 'audio',
                    bufferSize: typeof params.bufferSize == 'undefined' ? 16384 : params.bufferSize,
                    sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : params.sampleRate,
                    leftChannel: params.leftChannel || false,
                    disableLogs: params.disableLogs || false
                });
                recorder.startRecording();
            }, function () {
            });
        else {
            audio.src = URL.createObjectURL(audioStream);
            audio.muted = true;
            audio.play();
            if (recorder) recorder.startRecording();
        }

        window.isAudio = true;

        this.disabled = true;
        stopRecordingAudio.disabled = false;
        pauseResumeAudio.disabled = false;
    };

    //Função do botão stop
    stopRecordingAudio.onclick = function () {
        this.disabled = true;
        recordAudio.disabled = false;
        audio.src = '';

        fileName = Math.round(Math.random() * 99999999) + 99999999;

        if (!isFirefox) {
            recorder.stopRecording(function () {
                PostBlob(recorder.getBlob(), 'audio', fileName + '.wav');
            });
        } else {
            recorder.stopRecording(function () {
                PostBlob(recorder.getBlob(), 'audio', fileName + '.wav');
            });
        }
    };

    //Função do botão pause
    pauseResumeAudio.onclick = function () {
        if (!recorder) return;

        if (this.innerHTML === 'Pausar') {
            this.innerHTML = 'Retomar';
            recorder.pauseRecording();
            return;
        }

        this.innerHTML = 'Pausar';
        recorder.resumeRecording();
    };

    //Função para salvar o áudio
    function PostBlob(blob, fileType, fileName) {
        // FormData
        var formData = new FormData();
        formData.append(fileType + '-filename', fileName);
        formData.append(fileType + '-blob', blob);

        // progress-bar
        var hr = document.createElement('hr');
        container.appendChild(hr);
        var strong = document.createElement('strong');
        strong.id = 'percentage';
        strong.innerHTML = fileType + ' upload progress: ';
        container.appendChild(strong);
        var progress = document.createElement('progress');
        container.appendChild(progress);

        // POST the Blob using XHR2
        xhr('save.php', formData, progress, percentage, function (fileURL) {
            container.appendChild(document.createElement('hr'));
            var mediaElement = document.createElement(fileType);

            var source = document.createElement('source');
            var href = location.href.substr(0, location.href.lastIndexOf('/') + 1);
            audio.src = href + fileURL;
            audio.autoplay = false;

            source.src = href + fileURL;

            if (fileType == 'video') source.type = 'video/webm; codecs="vp8, vorbis"';
            if (fileType == 'audio') source.type = !!navigator.mozGetUserMedia ? 'audio/ogg' : 'audio/wav';

            mediaElement.appendChild(source);

            mediaElement.controls = true;
            container.appendChild(mediaElement);
            mediaElement.autoplay = false;
            //mediaElement.play();

            progress.parentNode.removeChild(progress);
            strong.parentNode.removeChild(strong);
            hr.parentNode.removeChild(hr);
        });
    }

    //Função de Upload
    function xhr(url, data, progress, percentage, callback) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                callback(request.responseText);
            }
        };

        if (url.indexOf('RecordRTC-to-PHP/delete.php') == -1) {
            request.upload.onloadstart = function () {
                percentage.innerHTML = 'Upload: iniciado...';
            };

            request.upload.onprogress = function (event) {
                progress.max = event.total;
                progress.value = event.loaded;
                percentage.innerHTML = 'Upload: Progresso ' + Math.round(event.loaded / event.total * 100) + "%";
            };

            request.upload.onload = function () {
                percentage.innerHTML = 'Arquivo Salvo!';
            };
        }

        request.open('POST', url);
        request.send(data);
    }
</script>
</body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: Wesley
 * Date: 14/04/15
 * Time: 15:08
 */ 