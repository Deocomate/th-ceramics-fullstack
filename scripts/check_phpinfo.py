import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('demo.thceramics.vn', username='demot2342', password='vZXUZ3P2L8MxU4xD')

sftp = client.open_sftp()
with sftp.file('/home/demo.thceramics.vn/public_html/public/php_info_test.php', 'w') as f:
    f.write('''<?php
ob_start();
phpinfo(INFO_CONFIGURATION);
$pinfo = ob_get_clean();
echo $pinfo;
''')
sftp.close()

stdin, stdout, stderr = client.exec_command("curl -k -s https://127.0.0.1/php_info_test.php -H 'Host: demo.thceramics.vn' | grep -iE 'user_ini|upload_max_filesize|post_max_size|memory_limit'")
print(stdout.read().decode())
client.close()
