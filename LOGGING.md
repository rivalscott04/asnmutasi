# Logging System Documentation

Sistem logging yang komprehensif dan mudah digunakan untuk aplikasi ASN Mutasi.

## Fitur Utama

- **Multiple Log Levels**: Emergency, Alert, Critical, Error, Warning, Notice, Info, Debug
- **Specialized Logging**: Request, Authentication, Validation, File Operations, Database Operations
- **Automatic Log Rotation**: Berdasarkan ukuran file dan tanggal
- **Performance Monitoring**: Timer untuk mengukur performa operasi
- **Error Handling**: Automatic logging untuk PHP errors dan exceptions
- **CLI Management**: Command line tools untuk mengelola log files
- **Helper Functions**: Global functions untuk kemudahan penggunaan

## Struktur File

```
core/Support/
├── Logger.php          # Main logger class
└── helpers.php         # Global helper functions

app/
├── Commands/
│   └── LogCommand.php  # CLI command untuk log management
└── Middleware/
    └── ErrorHandlingMiddleware.php  # Error handling middleware

storage/logs/           # Directory untuk menyimpan log files
├── app-2024-01-15.log  # Daily log files
├── app-2024-01-14.log
└── ...

log.php                 # CLI script untuk log management
```

## Penggunaan

### 1. Basic Logging

```php
use Core\Support\Logger;

// Basic log levels
Logger::info('User logged in', ['user_id' => 123]);
Logger::error('Database connection failed', ['error' => $e->getMessage()]);
Logger::warning('High memory usage detected', ['memory' => memory_get_usage()]);
Logger::debug('Processing request', ['data' => $requestData]);
```

### 2. Specialized Logging

```php
// Request logging
Logger::request('POST', '/api/users', ['name' => 'John']);

// Authentication logging
Logger::auth('login_success', 123, ['ip' => '192.168.1.1']);

// Validation logging
Logger::validation(['email' => 'required'], ['form' => 'registration']);

// File operation logging
Logger::file('upload', 'document.pdf', ['size' => 1024000]);

// Database query logging (automatic in BaseModel)
Logger::query('SELECT * FROM users WHERE id = ?', [123], 0.05);
```

### 3. Helper Functions

```php
// Simple logging
log_info('User action completed');
log_error('Something went wrong', ['error' => $error]);
log_warning('Warning message');
log_debug('Debug information');

// Performance monitoring
$timer = start_timer('database_operation');
// ... your code here ...
end_timer('database_operation', true, 'Database query completed');

// Exception logging
try {
    // risky operation
} catch (Exception $e) {
    log_exception($e, ['context' => 'user_registration']);
}
```

### 4. Automatic Logging

Sistem secara otomatis akan log:

- **HTTP Requests**: Semua incoming requests (via BaseController)
- **Validation Errors**: Ketika validasi gagal (via BaseController)
- **Database Operations**: Insert, Update, Delete operations (via BaseModel)
- **PHP Errors**: Error, Warning, Notice (via ErrorHandlingMiddleware)
- **Uncaught Exceptions**: Semua uncaught exceptions (via ErrorHandlingMiddleware)
- **Fatal Errors**: Fatal errors dan shutdown errors (via ErrorHandlingMiddleware)

## CLI Management

### Melihat Log Terbaru

```bash
# Melihat 50 log terakhir
php log.php tail

# Melihat 100 log terakhir
php log.php tail 100

# Melihat hanya error logs
php log.php tail 50 error
```

### Statistik Log

```bash
# Melihat statistik log files
php log.php stats
```

Output:
```
=== Log Statistics ===

Log Directory: C:\path\to\storage\logs
Total Files: 5
Total Size: 2.5 MB
Raw Size: 2621440 bytes

=== Individual Files ===
app-2024-01-15.log         1.2 MB 2024-01-15 14:30:25
app-2024-01-14.log         800 KB 2024-01-14 23:59:59
...
```

### Membersihkan Log Lama

```bash
# Hapus log lebih dari 30 hari
php log.php clear

# Hapus log lebih dari 7 hari
php log.php clear 7
```

### Testing Logging

```bash
# Test semua fungsi logging
php log.php test
```

### Help

```bash
# Melihat bantuan
php log.php help
```

## Konfigurasi

### Log Levels

Sesuai dengan PSR-3 standard:

1. **EMERGENCY** (0): System is unusable
2. **ALERT** (1): Action must be taken immediately
3. **CRITICAL** (2): Critical conditions
4. **ERROR** (3): Error conditions
5. **WARNING** (4): Warning conditions
6. **NOTICE** (5): Normal but significant condition
7. **INFO** (6): Informational messages
8. **DEBUG** (7): Debug-level messages

### Log Rotation

- **Daily Rotation**: Log files dibuat per hari (app-YYYY-MM-DD.log)
- **Size Limit**: File akan di-rotate jika melebihi 10MB
- **Retention**: Log lama dapat dihapus menggunakan CLI command

### Format Log

```
[2024-01-15 14:30:25] INFO: User logged in {"user_id":123,"ip":"192.168.1.1"}
[2024-01-15 14:30:26] ERROR: Database error {"error":"Connection timeout","query":"SELECT * FROM users"}
```

Format: `[timestamp] LEVEL: message {json_context}`

## Best Practices

### 1. Gunakan Level yang Tepat

```php
// ✅ Good
Logger::info('User successfully registered', ['user_id' => $userId]);
Logger::error('Failed to send email', ['error' => $e->getMessage()]);
Logger::debug('Processing payment', ['amount' => $amount]);

// ❌ Bad
Logger::error('User clicked button'); // Ini bukan error
Logger::info('Database connection failed'); // Ini seharusnya error
```

### 2. Sertakan Context yang Berguna

```php
// ✅ Good
Logger::error('Payment failed', [
    'user_id' => $userId,
    'amount' => $amount,
    'payment_method' => $method,
    'error' => $e->getMessage()
]);

// ❌ Bad
Logger::error('Payment failed');
```

### 3. Jangan Log Sensitive Data

```php
// ✅ Good
Logger::info('User login attempt', [
    'user_id' => $userId,
    'ip' => $ip,
    'user_agent' => $userAgent
]);

// ❌ Bad
Logger::info('User login', [
    'password' => $password, // JANGAN!
    'credit_card' => $cc     // JANGAN!
]);
```

### 4. Gunakan Helper Functions untuk Kemudahan

```php
// ✅ Simple dan clean
log_info('Operation completed');
log_error('Something went wrong', ['error' => $error]);

// ✅ Juga OK, tapi lebih verbose
Logger::info('Operation completed');
Logger::error('Something went wrong', ['error' => $error]);
```

### 5. Monitor Performance

```php
// ✅ Monitor operasi yang lambat
$timer = start_timer('heavy_operation');
try {
    // heavy operation here
    $result = processLargeData($data);
    end_timer('heavy_operation', true, 'Heavy operation completed');
    return $result;
} catch (Exception $e) {
    end_timer('heavy_operation', false, 'Heavy operation failed');
    throw $e;
}
```

## Troubleshooting

### Log Files Tidak Terbuat

1. Pastikan directory `storage/logs` ada dan writable
2. Check permissions pada directory
3. Pastikan PHP memiliki akses write ke directory

### Log Files Terlalu Besar

1. Gunakan `php log.php clear` untuk membersihkan log lama
2. Adjust log level di production (hanya ERROR dan WARNING)
3. Implement log rotation yang lebih agresif

### Performance Issues

1. Hindari logging di dalam loop yang besar
2. Gunakan log level yang tepat (DEBUG hanya untuk development)
3. Monitor ukuran log files secara berkala

### Memory Issues

1. Jangan log data yang sangat besar
2. Gunakan `unset()` untuk variable besar setelah logging
3. Monitor memory usage dengan `log_performance()`

## Integrasi dengan Monitoring Tools

Log files dapat diintegrasikan dengan tools monitoring seperti:

- **ELK Stack** (Elasticsearch, Logstash, Kibana)
- **Grafana + Loki**
- **Splunk**
- **Datadog**
- **New Relic**

Format JSON dalam context memudahkan parsing oleh tools tersebut.

## Maintenance

### Daily Tasks

```bash
# Check log statistics
php log.php stats

# Check for errors in recent logs
php log.php tail 100 error
```

### Weekly Tasks

```bash
# Clean old logs (older than 30 days)
php log.php clear 30

# Check disk usage
php log.php stats
```

### Monthly Tasks

```bash
# Archive old logs if needed
# Review log patterns for optimization
# Update log retention policies
```

Sistem logging ini dirancang untuk memberikan visibilitas yang baik terhadap aplikasi sambil tetap menjaga performa dan kemudahan maintenance.