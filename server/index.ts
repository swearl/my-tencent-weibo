import express from 'express';
import http from 'http';

const app: express.Application = express();
const httpServer: http.Server = http.createServer(app);
const defaultPort = 42008;
const port: number | string = process.env.PORT || defaultPort;

httpServer.listen(port, () => {
  console.log(`Listen on port: ${port}`);
});

app.use('/', express.static(__dirname + '/../client'));
