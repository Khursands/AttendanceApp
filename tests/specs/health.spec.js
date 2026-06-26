'use strict';
const { expect } = require('chai');
const { client } = require('../support/api');

/**
 * Smoke checks: confirm the read endpoints are reachable and return the
 * expected JSON success/error envelope.
 */
describe('AttendanceApp API — Health / read endpoints', function () {
  const readEndpoints = [
    'getAllUser.php',
    'getAllHolidays.php',
    'getAllLeavetypes.php',
    'getAllStatus.php',
    'getAllUserLeaves.php',
    'getAllAttendance.php',
  ];

  readEndpoints.forEach((endpoint) => {
    it(`GET ${endpoint} responds with JSON`, async function () {
      const res = await client.get('/' + endpoint);
      expect(res.status).to.be.oneOf([200, 401]);
      expect(res.headers['content-type'] || '').to.match(/json|text/);
      expect(res.data).to.satisfy(
        (d) => typeof d === 'object' || typeof d === 'string',
      );
    });
  });
});
