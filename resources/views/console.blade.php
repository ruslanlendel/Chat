<!DOCTYPE html>
<html>
<head>
    <title>Ubuntu Console</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: black;
            color: lightgreen;
            font-family: monospace;
            padding: 10px;
        }
        #console {
            width: 100%;
            height: 90vh;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        #input-line {
            display: flex;
        }
        #prompt {
            white-space: pre;
        }
        #input {
            background: none;
            border: none;
            color: lightgreen;
            outline: none;
            flex: 1;
            caret-color: lightgreen;
        }
    </style>
</head>
<body>
    <div id="console">
        <pre>
Welcome to Ubuntu 20.04.1 LTS (GNU/Linux 5.4.0-42-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

Last login: {{ date('D M d H:i:s Y') }} from 127.0.0.1
        </pre>
        <div id="output">
            @foreach($history as $item)
                <div><span id="prompt">user@ubuntu:~$ </span>{{ $item->command }}</div>
                <div>{{ $item->result }}</div>
            @endforeach
        </div>
        <div id="input-line">
            <span id="prompt">user@ubuntu:~$ </span>
            <input type="text" id="input" autofocus autocomplete="off" spellcheck="false" />
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        $(document).ready(function() {
            var input = $('#input');
            var output = $('#output');
            var consoleDiv = $('#console');

            var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                forceTLS: true
            });

            var channel = pusher.subscribe('console-channel');

            channel.bind('command', function(data) {
                if(data.userId !== '{{ session()->getId() }}') {
                    output.append('<div><span id="prompt">user@ubuntu:~$ </span>' + data.command + '</div>');
                }
                if (data.result) {
                    output.append('<div>' + data.result + '</div>');
                }
                consoleDiv.scrollTop(consoleDiv[0].scrollHeight);
            });

            channel.bind('typing', function(data) {
                if (data.id !== '{{ session()->getId() }}') {
                    input.val(data.text);
                }
            });

            input.on('input', function() {
                var text = input.val();
                $.post('/typing', {
                    _token: '{{ csrf_token() }}',
                    text: text
                });
            });

            input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    var command = input.val();
                    output.append('<div><span id="prompt">user@ubuntu:~$ </span>' + command + '</div>');
                    $.post('/command', {
                        _token: '{{ csrf_token() }}',
                        command: command
                    });
                    input.val('');
                    consoleDiv.scrollTop(consoleDiv[0].scrollHeight);
                }
            });
        });
    </script>
</body>
</html>