const http = require('http');

const urls = [
  { name: 'Services Directory (Public)', path: '/services' },
  { name: 'Provider Profile 15 (Public)', path: '/provider/15' },
  { name: 'Provider Hub (Auth redirect)', path: '/provider/dashboard' },
  { name: 'Client Bookings (Auth redirect)', path: '/dashboard/bookings' },
  { name: 'Provider Onboarding (Auth redirect)', path: '/provider/onboarding' },
  { name: 'Admin KYC (Auth redirect)', path: '/admin/kyc/5' },
];

function check(urlObj) {
  return new Promise((resolve) => {
    http.get('http://127.0.0.1:8000' + urlObj.path, (res) => {
      console.log(`GET ${urlObj.path.padEnd(25)} -> Status: ${res.statusCode} | Location: ${res.headers.location || 'N/A'}`);
      resolve({ path: urlObj.path, status: res.statusCode, location: res.headers.location });
    }).on('error', (err) => {
      console.log(`GET ${urlObj.path} -> Error: ${err.message}`);
      resolve({ path: urlObj.path, error: err.message });
    });
  });
}

(async () => {
  console.log('=== HTTP REQUESTS AGAINST LIVE APP (127.0.0.1:8000) ===');
  for (const u of urls) {
    await check(u);
  }
})();
