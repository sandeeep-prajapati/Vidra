# Webhook Integration

Configure **real-time HTTP callbacks** so external systems receive instant notifications when school events occur — new students enrolled, fees paid, attendance marked, and more.

---

## Features

- **Webhook Settings** — configure the endpoint URL and toggle the webhook on/off
- **Webhook Logs** — review the history of all outbound webhook calls

---

## Webhook Settings

Navigate to `/webhook/settings`.

### Configure the Endpoint

- **Endpoint URL** — the HTTPS URL that receives event payloads (e.g. `https://your-server.example/webhook`)
- **Active** — toggle to enable or disable all webhook delivery

### Save Configuration

Click **Save Settings** to apply your changes immediately.

> The endpoint must respond with HTTP `200` within 10 seconds. Failed deliveries are logged in **Webhook Logs**.

---

## Webhook Logs

Navigate to `/webhook/logs` to review outbound calls.

Each log entry shows:

- **Event** — the type of school event that triggered the call
- **Endpoint** — URL the payload was sent to
- **Status** — HTTP response code returned by your server
- **Payload** — the JSON body that was sent
- **Sent At** — timestamp of the attempt

Use the logs to debug failed deliveries — check the **Status** column and compare the **Payload** against your server's expected format.

---

## Payload Format

All webhook events follow this JSON envelope:

```json
{
  "event": "student.created",
  "school_id": 1,
  "timestamp": "2026-05-10T09:00:00Z",
  "data": {
    "id": 101,
    "first_name": "Ravi",
    "last_name": "Kumar"
  }
}
```

The `event` field identifies the trigger. The `data` object contains the relevant record.

---

## Common Event Types

- `student.created` — new student registered
- `student.updated` — student record edited
- `fee.payment.recorded` — fee payment received
- `attendance.marked` — attendance record saved
- `leave.approved` — leave request approved
- `exam.results.published` — student marks finalized

---

## Tips

- Always use **HTTPS** for your endpoint — plain HTTP may be rejected.
- Return HTTP `200` as quickly as possible; do heavy processing asynchronously.
- Use the **Webhook Logs** to replay or debug events during integration testing.
- Disable the webhook (`Active = off`) during server maintenance to avoid log clutter.
