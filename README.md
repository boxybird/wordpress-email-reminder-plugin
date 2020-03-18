# Wordpress Email Reminder Plugin

---

### Features

- Adds 'Last Login' and 'Emails Sent' to Users admin columns.
- Send email to Users based on:
    - Days since last logged-in.
    - Total emails already sent.

---

### Notes

- Administrator are excluded from email queue.
- Users must login as least once after plugin is installed to be added to email queue. *(This may be changed in the future)*
- Only one email per 24 hours sent regardless of admin settings to curb spamming.
- Uses wp_mail() function to send email. To avoid landing in your users spam folder, make sure you set your SMTP settings.

---

### Todos

---

### License
[MIT license](https://opensource.org/licenses/MIT)
