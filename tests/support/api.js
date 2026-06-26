'use strict';
require('dotenv').config();
const axios = require('axios');

/**
 * Base URL of the deployed AttendanceApp PHP backend. Each endpoint is a single
 * PHP script served from this root (e.g. `${BASE_URL}/UserLogin.php`).
 */
const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';

const client = axios.create({
  baseURL: BASE_URL,
  // The API returns 200 with a JSON error envelope, so don't throw on 4xx.
  validateStatus: () => true,
  headers: { 'Content-Type': 'application/json' },
  timeout: 15000,
});

/** Endpoints expect a JSON body of the shape { data: {...} }. */
function payload(data) {
  return { data };
}

module.exports = { BASE_URL, client, payload };
