import sys

from deploy_connection import connect

client = connect()

try:
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
