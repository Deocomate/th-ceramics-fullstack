import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('demo.thceramics.vn', username='demot2342', password='vZXUZ3P2L8MxU4xD')

user_ini_content = """; PHP upload & execution limits for LiteSpeed / CyberPanel
upload_max_filesize = 128M
post_max_size = 128M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
max_file_uploads = 100
"""

sftp = client.open_sftp()

# 1. Write .user.ini in /home/demo.thceramics.vn/public_html/.user.ini
with sftp.file('/home/demo.thceramics.vn/public_html/.user.ini', 'w') as f:
    f.write(user_ini_content)

# 2. Write .user.ini in /home/demo.thceramics.vn/public_html/public/.user.ini
with sftp.file('/home/demo.thceramics.vn/public_html/public/.user.ini', 'w') as f:
    f.write(user_ini_content)

sftp.close()

# 3. Kill demot2342 lsphp processes to force reload
stdin, stdout, stderr = client.exec_command('pkill -u demot2342 lsphp || killall -u demot2342 lsphp || true')
print(stdout.read().decode())

# 4. Check curl response
stdin, stdout, stderr = client.exec_command('curl -k -s https://127.0.0.1/php_info_test.php -H "Host: demo.thceramics.vn"')
print("=== AFTER USER.INI ===")
print(stdout.read().decode())

client.close()
