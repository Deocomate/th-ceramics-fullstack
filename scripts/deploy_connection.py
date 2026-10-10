import os
import sys

import paramiko


def _require(name):
    value = os.environ.get(name)
    if not value:
        sys.exit(f"Missing environment variable {name}. Set TH_DEPLOY_HOST, TH_DEPLOY_USER and TH_DEPLOY_PASSWORD before running this script.")
    return value


def connect(timeout=15):
    hostname = _require('TH_DEPLOY_HOST')
    username = _require('TH_DEPLOY_USER')
    password = _require('TH_DEPLOY_PASSWORD')

    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    print(f"Connecting to {username}@{hostname}...")
    client.connect(hostname, port=22, username=username, password=password, timeout=timeout)
    return client
