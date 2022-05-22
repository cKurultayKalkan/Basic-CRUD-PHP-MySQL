#!/bin/bash

aws secretsmanager get-secret-value --secret-id /upwork/cloudfront/rds --output text --query SecretString