import express from 'express';
import { createProxyMiddleware } from 'http-proxy-middleware';
import { createServer } from 'vite';

const PHP_TARGET = 'http://localhost:8000';
const PORT = 3000;

const app = express();

// PHP backend proxy’leri
app.use('/admin', createProxyMiddleware({
  target: PHP_TARGET + '/admin',
  changeOrigin: true
}));

app.use('/ajax', createProxyMiddleware({
  target: PHP_TARGET + '/ajax',
  changeOrigin: true
}));

// Vite dev server
const vite = await createServer({
  server: { middlewareMode: true }
});

app.use(vite.middlewares);

app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});