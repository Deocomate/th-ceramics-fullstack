import paramiko
import sys

hostname = "demo.thceramics.vn"
username = "demot2342"
password = "vZXUZ3P2L8MxU4xD"

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print(f"Connecting to {username}@{hostname}...")
    client.connect(hostname, port=22, username=username, password=password, timeout=15)
    print("Connected successfully!")
    
    cmd = sys.argv[1] if len(sys.argv) > 1 else "uname -a; whoami; pwd; php -v"
    stdin, stdout, stderr = client.exec_command(cmd)
    
    out = stdout.read().decode('utf-8', errors='replace')
    err = stderr.read().decode('utf-8', errors='replace')
    
    print("=== STDOUT ===")
    print(out)
    if err:
        print("=== STDERR ===")
        print(err)
        
finally:
    client.close()
