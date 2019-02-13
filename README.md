# DyanmicDNS Hostnames in .htaccess

## What is it?

This is a tool for querying DynamicDNS hostnames for their IPv4 (optionally IPv6) addresses and adding them to a .htaccess file to allow access to a resource.

## Features

+ Supports IPv4 & IPv6
+ Supports multiple IPs per hostname
+ Supports Apache 2.2 and 2.4 syntax
+ Supports multiple hostnames
+ Won't overwrite contents of an existing .htaccess file
+ Can perform a backup of the .htaccess
+ Places comments in the .htaccess so you know which hostname an IP came from

## Usage

```
         dynamic.php --htaccess <file> --hostnames <file> [--ipv6] [--backup] [--compat|--litespeed]

         -h|--help        This help message
         --htaccess       Path to the .htaccess file you'd like to put the IPs in
         --hostnames      Path to the file containing a list of dynamic hosts, one entry per line
         --ipv6           Optional: Perform IPv6 lookup on each hostname as well
         --backup         Optional: Make a backup (default prefix .bak) of the .htaccess file
         --compat         Optional: Apache 2.2 syntax
         --litespeed      Optional: Same as --compat, enabling Apache 2.2 syntax for Litespeed Web Server
```