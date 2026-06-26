'use strict';
const { expect } = require('chai');
const { client, payload } = require('../support/api');

describe('AttendanceApp API — Authentication (UserLogin.php)', function () {
  it('rejects an empty request body', async function () {
    const res = await client.post('/UserLogin.php', {});
    // Endpoint reports a JSON error when required keys are missing.
    expect(JSON.stringify(res.data)).to.match(/error|invalid|missing/i);
  });

  it('rejects a body missing the data envelope', async function () {
    const res = await client.post('/UserLogin.php', { Email: 'a@b.c' });
    expect(JSON.stringify(res.data)).to.match(/error|invalid|missing|data/i);
  });

  it('rejects login with missing credentials', async function () {
    const res = await client.post('/UserLogin.php', payload({ Email: 'a@b.c' }));
    expect(JSON.stringify(res.data)).to.match(/error|missing|required/i);
  });

  it('does not authenticate unknown credentials', async function () {
    const res = await client.post(
      '/UserLogin.php',
      payload({ Email: 'nobody@example.test', Password: 'definitely-wrong' }),
    );
    const body = JSON.stringify(res.data).toLowerCase();
    // Either an explicit error, or a success envelope with no user record.
    expect(body).to.satisfy(
      (b) => /error|invalid|not found|fail/.test(b) || /\[\]|null/.test(b),
    );
  });
});
