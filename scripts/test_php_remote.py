import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('demo.thceramics.vn', username='demot2342', password='vZXUZ3P2L8MxU4xD')

sftp = client.open_sftp()
with sftp.file('/home/demo.thceramics.vn/public_html/public/php_info_test.php', 'w') as f:
    f.write('''<?php
header("Content-Type: application/json");
echo json_encode([
    "upload_max_filesize" => ini_get("upload_max_filesize"),
    "post_max_size" => ini_get("post_max_size"),
    "memory_limit" => ini_get("memory_limit"),
    "max_execution_time" => ini_get("max_execution_time"),
    "server" => $_SERVER["SERVER_SOFTWARE"] ?? "unknown"
], JSON_PRETTY_PRINT);
''')
sftp.close()

stdin, stdout, stderr = client.exec_command('curl -k -s -v https://127.0.0.1/php_info_test.php -H "Host: demo.thceramics.vn"')
print("=== CURL OUTPUT ===")
print(stdout.read().decode())
print(stderr.read().decode())

client.close()
