#!/usr/bin/env bash
function getlogs() {
    printf '*****************************\n'
    printf 'Fetching error.log and route-vars.log\n'
    printf '*****************************\n'
    printf 'error.log: \n'
    sudo tail /var/log/nginx/error.log | awk '{print "\033[0;31m" $0 "\033[0m"}'
    printf '*****************************\n'
    printf 'route-vars.log\n'
    sudo tail /var/log/nginx/route-var.log | awk '{print "\033[0;33m" $0 "\033[0m"}'
}
