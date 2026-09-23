import paramiko

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect('demo.thceramics.vn', username='demot2342', password='vZXUZ3P2L8MxU4xD')

sftp = client.open_sftp()
with sftp.file('/home/demo.thceramics.vn/public_html/public/test_docroot.php', 'w') as f:
    f.write('''<?php
echo "DOCUMENT_ROOT: " . ($_SERVER["DOCUMENT_ROOT"] ?? "") . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER["SCRIPT_FILENAME"] ?? "") . "\n";
echo "PWD: " . getcwd() . "\n";
''')
sftp.close()

stdin, stdout, stderr = client.exec_command("curl -k -s https://127.0.0.1/test_docroot.php -H 'Host: demo.thceramics.vn'")
print(stdout.read().decode())
client.close()
