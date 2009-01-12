munin-http-tunnel
=================
[munin](http://munin.projects.linpro.no/) is a really nice system
monitoring tool, like Nagios or even more like cacti (it's optimized)
to track system values over time as opposed to just current state.

The tool consists of a small daemon running on the nodes to be watched
and listening on tcp port 4949 and a set of scripts running on the 
central server which connect to the nodes and collect the data.

The beauty behind munin is its simplicity which makes everything really
easy to set up and to extend.

The one drawback though is that everything runs on a special TCP port,
as this means that you cannot use it to watch nodes behind restrictive
firewalls.

There are options for tunneling via SSH of course, but these won't work
in the really restrictive installations which only allow SSH-access via
one-time-pads or proprietary VPN clients.

In comes munin-http-tunnel which consists of a small PHP-script to run
on the server you intend to watch and a small perl-script on a server
(or the master server for that matter) your munin master server can
access.

When you are watching a http-server, chances are, that http is 
accessible from the outside, so this will always work. Even through 
additional HTTP-proxies.

Requirements
------------
The proxy-server is written in Perl to match what munin itself is 
written in. It requires LWP in addition to Net::Server (which is)
needed by munin itself.

The remote end is written in PHP because the server I've written this
solution for only runs PHP. You can easily replace the 10 lines by
whatever else you like.

Installation
------------
1. copy `remote_end/munin.php` to your webserver in a directory that's
   accessible from the web. Use whatever means provided by your 
   webserver to secure the access (though authentication isn't just)
   implemented in the client at the time of this writing)
2. Copy `local_end/proxy` somewhere on the master server
3. Edit `local_end/server1.conf` and rename it to something sensible
4. Run proxy and pass it the path to your configuration file.
5. Add the new node to your munin.conf

Configuration
-------------
While the remote end does not need to be configured, the local end had 
to be.

The configuration file accepts all the parameters you would expect from 
any Net::Server implementation, though there are two more parameters 
you need to set:

* `remote_url`:
  is the publicly accessible URL of your munin.php script.
* `hostname`:
  is the hostname the proxy should report to the munin master 
  scripts. It must match the hostname in munin.conf.

So let's say that you want to watch example.com and you want to use 
this HTTP tunnel.

1. Install munin on example.com and configure it as usual
2. Place munin.php somewhere so it's accessible as 
   http://www.example.com/munin.php
3. On you master machine, create a config-file where you set
   remote_url to http://www.example.com/munin.php
   and hostname to www.example.com (or whatever)
   and set a port by setting "port 4950" (or whatever)
4. Add this to munin.conf:

        [www.example.com]
           address localhost
           port 4950
        
Done!

License
-------
(c) 2009 by Philip Hofstetter, licensed under the MIT license
