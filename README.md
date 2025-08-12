# Lead Management API – Symfony 6.4

A high-throughput, role-based, and secure **Lead Management API** built with **Symfony 6.4**.  
Supports **API key authentication**, **role-based access control**, and **asynchronous lead processing** with **Symfony Messenger**.  
Designed to handle **1,000+ lead submissions per minute**.

---

## 🚀 Features

### ✅ Security
- **API Key Authentication** with expiration (`expires_at`) check
- **Role-Based Access Control (RBAC)** for `/api` and `/admin` routes
- **Custom JSON error responses** for authentication and authorization errors
- **Global Exception Handling** – Converts all exceptions to JSON and logs full details with `LoggerInterface`

### ✅ Leads Management
- Create leads with **fixed fields** and **dynamic custom fields**
- Validation using Symfony’s `Validator` component
- Asynchronous saving via **Symfony Messenger** for high throughput

### ✅ Logging & Tracking
- Full API request & response logging in MySQL
- Logs include:
  - Endpoint, HTTP method, payload
  - Authenticated API client ID
  - Response status & body
- **Centralized exception logging** with stack traces

### ✅ Scalability
- Clean separation of concerns (Controller → Service → Entity)
- Indexed MySQL schema for fast lookups
- Messenger queue for offloading DB writes

---

## 🛠 Tech Stack

- **Symfony 6.4** – MVC framework
- **Doctrine ORM** – Database mapping
- **Symfony Messenger** – Async job processing
- **MySQL** – Relational database
- **Docker** – Local development



---

## 🔐 Authentication & Roles

| Role                | Permissions                                                      |
|---------------------|------------------------------------------------------------------|
| `ROLE_ADMIN`        | Manage users, manage leads, access `/admin` and `/api` endpoints |
| `ROLE_API_CLIENT`   | Submit leads only  `/api/'                                               |

**Authentication** is done via an API key in the request header:


```
X-AUTH-Token: <API_KEY>
```

---

## 📦 Endpoints Overview

| Method | Endpoint                | Role Required       | Description                  | Status Code |
|--------|-------------------------|---------------------|------------------------------|-------------|
| POST      | `/api/tracking/`                     | `ROLE_API_CLIENT`   | Submit a new lead           | 201, 400, 403, 500    |
| GET       | `/admin/api/user/`                   | `ROLE_ADMIN`        | List application users      | 200, 403, 500         |
| GET       | `/admin/api/user/{id}`               | `ROLE_ADMIN`        | find one user by id         | 200, 403, 404, 500    |
| DELETE    | `/admin/api/user/{id}`               | `ROLE_ADMIN`        | Remove a user               | 204, 404, 403, 500    |
| PUT/PATCH | `/admin/api/user/{id}`               | `ROLE_ADMIN`        | Update a user               | 204, 403, 400, 404, 500   |
| POST      | `/admin/api/user/`                   | `ROLE_ADMIN`        | Register a user             | 201, 403, 400, 401, 500    |
| POST      | `/admin/api/user/{id}/access_tokens` | `ROLE_ADMIN`        | Generate a token for a user | 200, 403, 400, 404, 500    |


---

## ⚡ High-Throughput Processing

Leads are dispatched to **Symfony Messenger** via:

```php
$this->bus->dispatch(new StoreLeadMessage($leadDto,$apiRquestLogId));
```

The handler processes and saves them in the background, allowing the API to respond instantly.

---

## 🗄 Database Schema

- `leads` – Common lead fields
- `app_users` – App main user authenticator with roles
- `app_user_api_keys` – has token keys for each user with possibility to use expiration date or to disable
- `api_request_logs` – API request metadata
- `api_response_logs` – API response metadata

---

## 🧪 Error Handling

### Global Exception Subscriber
All exceptions are intercepted by `ExceptionListener`, which:
1. Logs the full exception details with stack trace using `LoggerInterface`
2. Converts the error into a clean JSON response

**Example JSON error:**
```json
{
    "status": "Invalid",
    "message": "This value `isActive` should be of type bool."
}
```

**HTTP Codes:**
- **401 Unauthorized** – Missing or invalid API key
- **403 Forbidden** – Authenticated but insufficient role
- **400 Bad Request** – Validation errors
- **404 Not Found** – Resource missing
- **422 Unprocessable Entity** – Semantic validation errors (optional)
- **500 Internal Server Error** – Internal exception

---

## ⚙️ Setup Instructions

1. **Clone the repo**
   ```bash
   git clone https://github.com/samba2013/LeadSyncPro.git
   cd LeadSyncPro
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **update/create your `.env`**
   ```env
MYSQL_DATABASE="leadsync-dev-db"
MYSQL_ROOT_PASSWORD="!changeMe!"
MYSQL_PASSWORD="!change!"
MYSQL_USER="symfony"
MYSQL_VERSION="8.0.31"
MYSQL_CHARSET="utf8mb4"
```

4. **Run Docker**
   ```bash
   docker compose build --pull --no-cache
   docker compose up --wait
   docker exec -it leadsyncpro-php-1 bash
   ```

When you are inside your docker then ...

5. **Generate your first app admin user with token key**
   ```bash
   php bin/console app:create-client-apikey email@domain --full_name="My fullname" -vvv
   ```

6. **Start using the postman collection with the generated api key**

7. **make sure to run the messenger consumer to your backround jobs gets processed (only for leads)**
```bash
bin/console messenger:consume async -vv
```

---

## 📌 Notes & Assumptions
- `expires_at` for API keys is enforced; no new key is generated if the existing one is still valid.
- Logs are synchronous for simplicity; can be moved to Messenger for very high throughput.
- Sensitive data (API keys) are not hashed in DB for this assignment, but in production a **hashed key** approach is recommended.
- All exceptions are captured globally for consistent JSON output and full logging.

---

## 🏆 Why This Design
- **Scalable**: Supports new lead fields without DB changes using json column.
- **Maintainable**: Clear separation between controllers, services, and persistence.
- **Secure**: Role-based control, API key expiration, JSON error responses.
- **Fast**: Async processing with Messenger keeps API latency low.
- **Traceable**: Centralized logging of both normal API calls and exceptions.

---
