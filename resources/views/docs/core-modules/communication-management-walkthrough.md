# Communication Management

Send **messages, circulars, and notifications** to students, teachers, and staff. Manage recipients per message and configure notification preferences per user.

---

## Features

- **Messages** — create and broadcast school-wide or targeted messages
- **Message Recipients** — assign specific users to receive a message
- **Circulars** — publish official circulars to the school community
- **Notification Preferences** — per-user notification channel settings

---

## Messages

Navigate to `/message` to see all messages.

### Create a Message

1. Click **New Message**
2. Fill in:
   - **Message Type** — `Announcement`, `Alert`, `Reminder`, or `Notice`
   - **Priority** — `Normal`, `High`, or `Urgent`
   - **Title** — subject of the message
   - **Content** — full message body
3. Click **Create Message**

> Messages are templates. Add **Recipients** separately to control who receives each message.

---

## Message Recipients

Assign individual users to a message for targeted delivery.

Navigate to `/messageRecipient`.

### Add a Recipient

1. Click **Add Recipient**
2. Fill in:
   - **Message** — select the message to attach to
   - **User** — select the receiving user
   - **Status** — `Sent`, `Pending`, or `Read`
3. Click **Save**

> You can add multiple recipients to a single message, one record at a time.

---

## Circulars

Circulars are formal school announcements published to a target audience with an issue date.

Navigate to `/circular`.

### Publish a Circular

1. Click **New Circular**
2. Fill in:
   - **Title** — circular heading (e.g. `Annual Day Notice`)
   - **Content** — full text of the circular
   - **Target Audience** — `All`, `Students`, `Staff`, or `Parents`
   - **Issued Date** — official date of issue
3. Click **Publish Circular**

---

## Notification Preferences

Navigate to `/notificationSetting` to view and configure which notification channels each user has enabled.

Preferences typically include:

- **Email notifications** — on/off per event type
- **In-app notifications** — on/off per event type
- **SMS/Push** — if configured for your deployment

Changes take effect immediately for the selected user.

---

## Tips

- Use **Announcements** for general school updates.
- Use **Alerts** for urgent situations (school closure, weather).
- Use **Circulars** when you need a dated, official school document.
- Check **Notification Preferences** to ensure users will receive notifications on their preferred channel.
