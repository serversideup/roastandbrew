#!/bin/bash

# Pull code from Github
ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -p $DEPLOY_SSH_PORT -i /tmp/deploy_rsa $DEPLOY_SSH_USER@$DEPLOY_SERVER_HOST 'cd ~/httpdocs; git pull origin master'

# Run migrations
ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -p $DEPLOY_SSH_PORT -i /tmp/deploy_rsa $DEPLOY_SSH_USER@$DEPLOY_SERVER_HOST 'cd ~/httpdocs; php artisan migrate'
