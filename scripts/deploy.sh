#!/bin/bash
ssh -oStrictHostKeyChecking=no -p $DEPLOY_SSH_PORT -i /tmp/deploy_rsa $DEPLOY_SSH_USER@$DEPLOY_SERVER_HOST 'cd ~/httpdocs; git pull origin master'
ssh -oStrictHostKeyChecking=no -p $DEPLOY_SSH_PORT -i /tmp/deploy_rsa $DEPLOY_SSH_USER@$DEPLOY_SERVER_HOST 'cd ~/httpdocs; php artisan migrate'
