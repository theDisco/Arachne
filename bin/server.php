<?php

declare(ticks = 1);

require '../vendor/autoload.php';

$address = '127.0.0.1';
$port = 8899;

if (($sock = socket_create(AF_INET, SOCK_STREAM, 0)) < 0) {
    print "failed to create socket: " . socket_strerror(socket_last_error()) . "\n";
    exit(1);
}

if (@socket_bind($sock, $address, $port) === false) {
    print "failed to bind socket: " . socket_strerror(socket_last_error()) . "\n";
    exit(1);
}

if (@socket_listen($sock, 0) === false) {
    print "failed to listen to socket: " . socket_strerror(socket_last_error()) . "\n";
    exit(1);
}

socket_set_nonblock($sock);
socket_getsockname($sock, $addr, $port);
print "Server Listening on $addr:$port\n";

while (true) {
    $connection = socket_accept($sock);

    if ($connection) {
        socket_getpeername($connection, $raddr, $rport);
        print "Received Connection from $raddr:$rport\n";
        $request = socket_read($connection, 1024);

        $parser = new GuzzleHttp\Message\MessageParser;
        $request = $parser->parseRequest($request);
        $path = ltrim($request['request_url']['path'], '/');
        $parts = explode('/', $path);
        $body = [];

        if (count($parts) == 2) {
            $body[$parts[0]] = $parts[1];
        }

        $response = new GuzzleHttp\Message\Response(
            200,
            ['Server' => 'Google Frontend'],
            GuzzleHttp\Stream\Stream::factory(json_encode($body))
        );

        socket_write($connection, $response->__toString());
        socket_close($connection);
    }
}


pcntl_signal(SIGTERM, function () use ($sock) {
    socket_close($sock);
});

pcntl_signal(SIGHUP, function () use ($sock) {
    socket_close($sock);
});