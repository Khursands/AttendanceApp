'use strict';
const { expect } = require('chai');
const { client, payload } = require('../support/api');

/**
 * Contract checks around user creation and lookup. Validation paths run
 * unconditionally; the happy-path create is opt-in via RUN_MUTATIONS=1 so the
 * suite is safe to run against a shared/staging database by default.
 */
describe('AttendanceApp API — Users', function () {
  it('getAllUser returns a JSON envelope', async function () {
    const res = await client.get('/getAllUser.php');
    expect(res.data).to.exist;
  });

  it('addUser rejects a payload missing required fields', async function () {
    const res = await client.post('/addUser.php', payload({ Email: 'partial@example.test' }));
    expect(JSON.stringify(res.data)).to.match(/error|missing|required/i);
  });

  it('getUserbyId with an invalid id reports not found', async function () {
    const res = await client.post('/getUserbyId.php', payload({ Id: -1 }));
    expect(JSON.stringify(res.data)).to.match(/error|not found|null|\[\]/i);
  });

  (process.env.RUN_MUTATIONS === '1' ? it : it.skip)(
    'creates a user when all fields are provided',
    async function () {
      const unique = Date.now();
      const res = await client.post(
        '/addUser.php',
        payload({
          Username: `qa_user_${unique}`,
          Email: `qa_user_${unique}@example.test`,
          Phone: '03000000000',
          Password: 'Str0ngP@ss',
          RoleId: 1,
        }),
      );
      expect(JSON.stringify(res.data)).to.not.match(/error/i);
    },
  );
});
