# DyanmicDNS Hostnames in .htaccess

## What is it?

This is a tool for querying DynamicDNS hostnames for their IPv4 (optionally IPv6) addresses and adding them to a .htaccess file to allow access to a resource.

## Why?

You might want to lock down the admin folder for Wordpress a bit more (and you really really should) or other software to only your contributors, but they don't all have static IP addresses and you don't want to go to the expense of a VPN setup.  DynamicDNS services are free or cheap, built in to most routers these days, or trivially easy to setup on a laptop etc.

Unfortunately whilst Apache will let you add a hostname in to your .htaccess to allow it access it doesn't work in the way most users expect.  All it does is check that the reverse DNS (rDNS) for the IP address of a visitor, matches the hostname you've put in there - and that's triviallly easy for an attacker to manipulate.  It doesn't lookup the hostname entered and find the IP address and match against that.

## Features

+ Supports IPv4 & IPv6
+ Supports multiple IPs per hostname
+ Supports Apache 2.2 and 2.4 syntax
+ Supports multiple hostnames
+ Won't overwrite contents of an existing .htaccess file
+ Can perform a backup of the .htaccess
+ Places comments in the .htaccess so you know which hostname an IP came from
+ Uses PHP validators to check the hostname before using it, and to check the returned IP addresses

## Requirements

+ PHP7

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