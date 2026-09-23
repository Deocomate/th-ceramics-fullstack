import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('demo.thceramics.vn', username='demot2342', password='vZXUZ3P2L8MxU4xD')

htaccess_append = """
php_value upload_max_filesize 128M
php_value post_max_size 128M
php_value memory_limit 512M
php_value max_execution_time 300
php_value max_input_time 300
"""

sftp = client.open_sftp()
with sftp.file('/home/demo.thceramics.vn/public_html/.htaccess', 'r') as f:
    content1 = f.read().decode('utf-8')

with sftp.file('/home/demo.thceramics.vn/public_html/.htaccess', 'w') as f:
    f.write(content1 + "\n" + htaccess_append)

with sftp.file('/home/demo.thceramics.vn/public_html/public/.htaccess', 'r') as f:
    content2 = f.read().decode('utf-8')

with sftp.file('/home/demo.thceramics.vn/public_html/public/.htaccess', 'w') as f:
    f.write(content2 + "\n" + htaccess_append)

sftp.close()

stdin, stdout, stderr = client.exec_command('pkill -u demot2342 lsphp || killall -u demot2342 lsphp || true')
print(stdout.read().decode())

stdin, stdout, stderr = client.exec_command('curl -k -s https://127.0.0.1/php_info_test.php -H "Host: demo.thceramics.vn"')
print("=== AFTER HTACCESS ===")
print(stdout.read().decode())

client.close()
