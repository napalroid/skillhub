import http from 'node:http';
import net from 'node:net';

const webPort = Number(process.env.SKILLHUB_WEB_PORT || 8001);
const reverbPort = Number(process.env.SKILLHUB_REVERB_PORT || 8080);
const proxyPort = Number(process.env.SKILLHUB_PROXY_PORT || 8002);

const server = http.createServer((request, response) => {
    const upstream = http.request({
        host: '127.0.0.1',
        port: webPort,
        method: request.method,
        path: request.url,
        headers: request.headers,
    }, (upstreamResponse) => {
        response.writeHead(upstreamResponse.statusCode ?? 502, upstreamResponse.headers);
        upstreamResponse.pipe(response);
    });

    upstream.on('error', () => {
        if (!response.headersSent) response.writeHead(502, { 'Content-Type': 'text/plain; charset=utf-8' });
        response.end('SkillHub web server tidak tersedia. Jalankan php artisan serve --port=8001.');
    });

    request.pipe(upstream);
});

server.on('upgrade', (request, socket, head) => {
    if (!request.url?.startsWith('/app/')) {
        socket.destroy();
        return;
    }

    const upstream = net.connect(reverbPort, '127.0.0.1');
    upstream.on('connect', () => {
        const headers = Object.entries(request.headers)
            .map(([name, value]) => `${name}: ${Array.isArray(value) ? value.join(', ') : value}`)
            .join('\r\n');
        upstream.write(`${request.method} ${request.url} HTTP/${request.httpVersion}\r\n${headers}\r\n\r\n`);
        if (head.length) upstream.write(head);
        socket.pipe(upstream).pipe(socket);
    });

    const close = () => {
        socket.destroy();
        upstream.destroy();
    };
    upstream.on('error', close);
    socket.on('error', close);
});

server.listen(proxyPort, '127.0.0.1', () => {
    console.log(`SkillHub proxy aktif: http://127.0.0.1:${proxyPort} → Laravel ${webPort}, Reverb ${reverbPort}`);
});
