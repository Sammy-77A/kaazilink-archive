const http = require('http');

const data = JSON.stringify({
  response: {
    status: 'Failed',
    amount: 1000,
    external_reference: 'TEST_NON_EXISTENT_REF',
    message: 'User cancelled transaction'
  }
});

const req = http.request({
  hostname: '127.0.0.1',
  port: 8000,
  path: '/api/payments/callback/payhero',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': Buffer.byteLength(data),
    'Accept': 'application/json'
  }
}, (res) => {
  let body = '';
  res.on('data', chunk => body += chunk);
  res.on('end', () => {
    console.log(`Live HTTP Callback Test -> Status: ${res.statusCode} | Response: ${body}`);
  });
});

req.on('error', (e) => {
  console.error(`Request error: ${e.message}`);
});

req.write(data);
req.end();
