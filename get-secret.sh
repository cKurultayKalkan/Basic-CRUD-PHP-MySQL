#!/bin/bash

aws secretsmanager get-secret-value --secret-id "/cloudfront/rds-$STACK_NAME" --output text --query SecretString