import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  vus: 10,             // number of virtual users
  duration: '10s',     // duration of test
  thresholds: {
    http_req_duration: ['p(95)<200'], // FAIL if 95% of requests > 200ms
  },
};

export default function () {
  const url = 'https://example.com'; // ❗ Replace with your app URL
  const res = http.get(url);
  check(res, {
    'status is 200': (r) => r.status === 200,
  });
  sleep(1);
}
